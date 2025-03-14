<?php

namespace App\Observers;

use App\Models\OrderItem;
use App\Models\OutletProduct;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Support\Facades\Log;

class OrderItemObserver
{
    /**
     * Handle the OrderItem "created" event.
     */
    public function created(OrderItem $orderItem): void
    {
        // Load the parent order to get the outlet_id and status
        $order = $orderItem->order;
        
        if (!$order || !$order->outlet_id) {
            Log::warning('Cannot update stock: Order or outlet_id not found for OrderItem ' . $orderItem->id);
            return;
        }

        // Only deduct stock for active statuses
        $activeStatuses = ['new', 'processing', 'delivered'];
        if (in_array($order->status, $activeStatuses)) {
            $this->deductOutletProductStock($orderItem);
        } else {
            Log::info("No stock deduction for OrderItem {$orderItem->id} as order status is {$order->status}");
        }
    }

    /**
     * Handle the OrderItem "updated" event.
     */
    public function updated(OrderItem $orderItem): void
    {
        // Check if scheduled_date was changed
        if ($orderItem->isDirty('scheduled_date')) {
            // Get the order
            $order = $orderItem->order;
            
            // Get the user
            $user = $order ? $order->user : null;
            
            // Only send notification if we have a user and an order
            if ($user && $order) {
                Log::info("Scheduled date changed for OrderItem {$orderItem->id}. Sending notification to user {$user->id}");
                
                try {
                    // Send notification with status 'rescheduled' to indicate the date change
                    $user->notify(new OrderStatusUpdated($order, 'rescheduled'));
                    
                    Log::info("Scheduled date change notification sent successfully for OrderItem {$orderItem->id}");
                } catch (\Exception $e) {
                    Log::error("Failed to send scheduled date change notification: " . $e->getMessage(), [
                        'order_id' => $order->id,
                        'user_id' => $user->id,
                        'scheduled_date' => $orderItem->scheduled_date
                    ]);
                }
            } else {
                Log::warning("Cannot send scheduled date change notification: " . 
                    (!$order ? "Order not found" : "User not found") . 
                    " for OrderItem {$orderItem->id}");
            }
        }
    }

    /**
     * Handle the OrderItem "deleted" event.
     */
    public function deleted(OrderItem $orderItem): void
    {
        // Load the parent order to get the outlet_id and status
        $order = $orderItem->order;
        
        if (!$order || !$order->outlet_id) {
            Log::warning('Cannot restore stock: Order or outlet_id not found for deleted OrderItem ' . $orderItem->id);
            return;
        }

        // Only restore stock for active statuses
        $activeStatuses = ['new', 'processing', 'delivered'];
        if (in_array($order->status, $activeStatuses)) {
            $this->restoreOutletProductStock($orderItem);
        } else {
            Log::info("No stock restoration for deleted OrderItem {$orderItem->id} as order status is {$order->status}");
        }
    }

    /**
     * Deduct stock when item is created
     */
    private function deductOutletProductStock(OrderItem $orderItem): void
    {
        $order = $orderItem->order;
        
        $outletProduct = OutletProduct::where([
            'outlet_id' => $order->outlet_id,
            'product_id' => $orderItem->product_id,
        ])->first();

        if ($outletProduct) {
            // Log before state
            Log::info("Deducting stock for new order item: outlet {$order->outlet_id}, product {$orderItem->product_id}: Current stock: {$outletProduct->stock}, Ordered quantity: {$orderItem->quantity}");
            
            // Deduct the ordered quantity from the outlet product stock
            $outletProduct->stock -= $orderItem->quantity;
            $outletProduct->save();
            
            // Log after state
            Log::info("Stock updated. New stock: {$outletProduct->stock}");
        } else {
            Log::warning("OutletProduct not found for outlet_id: {$order->outlet_id}, product_id: {$orderItem->product_id}");
        }
    }
    
    /**
     * Restore stock when item is deleted
     */
    private function restoreOutletProductStock(OrderItem $orderItem): void
    {
        $order = $orderItem->order;
        
        $outletProduct = OutletProduct::where([
            'outlet_id' => $order->outlet_id,
            'product_id' => $orderItem->product_id,
        ])->first();

        if ($outletProduct) {
            // Log before state
            Log::info("Restoring stock for deleted order item: outlet {$order->outlet_id}, product {$orderItem->product_id}: Current stock: {$outletProduct->stock}, Returning quantity: {$orderItem->quantity}");
            
            // Restore the ordered quantity to the outlet product stock
            $outletProduct->stock += $orderItem->quantity;
            $outletProduct->save();
            
            // Log after state
            Log::info("Stock restored. New stock: {$outletProduct->stock}");
        } else {
            Log::warning("OutletProduct not found for restoration on delete: outlet_id: {$order->outlet_id}, product_id: {$orderItem->product_id}");
        }
    }
}