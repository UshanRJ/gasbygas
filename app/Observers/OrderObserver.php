<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OutletProduct;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Only process if status changed
        if ($order->status !== $order->getOriginal('status')) {
            $newStatus = $order->status;
            $oldStatus = $order->getOriginal('status');
            
            Log::info("Order #{$order->id} status changed from {$oldStatus} to {$newStatus}");
            
            // Define status groups
            $activeStatuses = ['new', 'processing', 'delivered'];
            $inactiveStatuses = ['cancelled', 'rescheduled'];
            
            // Case 1: Moving from active to inactive status (restore stock)
            if (in_array($oldStatus, $activeStatuses) && in_array($newStatus, $inactiveStatuses)) {
                Log::info("Restoring stock for order #{$order->id} (changed from active to inactive status)");
                $this->restoreOutletProductStock($order);
            }
            
            // Case 2: Moving from inactive to active status (deduct stock)
            elseif (in_array($oldStatus, $inactiveStatuses) && in_array($newStatus, $activeStatuses)) {
                Log::info("Deducting stock for order #{$order->id} (changed from inactive to active status)");
                $this->deductOutletProductStock($order);
            }
            
            // Case 3: Moving between statuses within the same group (no stock change)
            else {
                Log::info("No stock change needed for order #{$order->id} (status change within same group)");
            }
        }
    }

    /**
     * Restore the outlet product stock when an order is cancelled/rescheduled
     */
    private function restoreOutletProductStock(Order $order): void
    {
        if (!$order->outlet_id) {
            Log::warning("Cannot restore stock: no outlet_id for order #{$order->id}");
            return;
        }

        // Reload the relationship to ensure we have the latest data
        $order->load('orderItems');

        foreach ($order->orderItems as $orderItem) {
            $outletProduct = OutletProduct::where([
                'outlet_id' => $order->outlet_id,
                'product_id' => $orderItem->product_id,
            ])->first();

            if ($outletProduct) {
                // Log before restoration
                Log::info("Restoring stock for outlet {$order->outlet_id}, product {$orderItem->product_id}: Current stock: {$outletProduct->stock}, Returning quantity: {$orderItem->quantity}");
                
                // Restore the quantity back to stock
                $outletProduct->stock += $orderItem->quantity;
                $outletProduct->save();
                
                // Log after restoration
                Log::info("Stock restored. New stock: {$outletProduct->stock}");
            } else {
                Log::warning("OutletProduct not found for restoration: outlet_id: {$order->outlet_id}, product_id: {$orderItem->product_id}");
            }
        }
    }
    
    /**
     * Deduct the outlet product stock when an order is moved back to active status
     */
    private function deductOutletProductStock(Order $order): void
    {
        if (!$order->outlet_id) {
            Log::warning("Cannot deduct stock: no outlet_id for order #{$order->id}");
            return;
        }

        // Reload the relationship to ensure we have the latest data
        $order->load('orderItems');

        foreach ($order->orderItems as $orderItem) {
            $outletProduct = OutletProduct::where([
                'outlet_id' => $order->outlet_id,
                'product_id' => $orderItem->product_id,
            ])->first();

            if ($outletProduct) {
                // Check if there's enough stock
                if ($outletProduct->stock < $orderItem->quantity) {
                    Log::warning("Insufficient stock for order #{$order->id}, product {$orderItem->product_id}: Available: {$outletProduct->stock}, Required: {$orderItem->quantity}");
                    continue; // Skip this item if not enough stock
                }
                
                // Log before deduction
                Log::info("Deducting stock for outlet {$order->outlet_id}, product {$orderItem->product_id}: Current stock: {$outletProduct->stock}, Deducting quantity: {$orderItem->quantity}");
                
                // Deduct the quantity from stock
                $outletProduct->stock -= $orderItem->quantity;
                $outletProduct->save();
                
                // Log after deduction
                Log::info("Stock deducted. New stock: {$outletProduct->stock}");
            } else {
                Log::warning("OutletProduct not found for deduction: outlet_id: {$order->outlet_id}, product_id: {$orderItem->product_id}");
            }
        }
    }
}