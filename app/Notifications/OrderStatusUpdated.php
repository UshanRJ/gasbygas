<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;
    protected $status;

    /**
     * Create a new notification instance.
     */
    public function __construct($order, $status)
    {
        $this->order = $order;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusLabel = match ($this->status) {
            'new' => 'New',
            'processing' => 'Processing',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'rescheduled' => 'Rescheduled',
            default => $this->status
        };

        // Get the first order item
        $orderItem = $this->order->orderItems->first();
        
        // Get the order token
        $orderToken = $orderItem ? $orderItem->order_token : 'N/A';
        
        // Debug: Log all available attributes of the order item
        $attributes = $orderItem ? $orderItem->getAttributes() : [];
        \Illuminate\Support\Facades\Log::info('Order Item Attributes:', $attributes);
        
        // Check for any date-related fields in the order item
        $possibleDateFields = ['scheduled_date', 'scheduledDate', 'schedule_date', 'delivery_date', 'deliveryDate'];
        $scheduledDate = 'Not scheduled';
        
        foreach ($possibleDateFields as $field) {
            if ($orderItem && isset($attributes[$field]) && !empty($attributes[$field])) {
                \Illuminate\Support\Facades\Log::info("Found date in field: {$field}");
                try {
                    $scheduledDate = date('Y-m-d', strtotime($attributes[$field]));
                    break;
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Failed to format date from {$field}: " . $e->getMessage());
                }
            }
        }

        return (new MailMessage)
                    ->subject("Your Order Status Updated: {$statusLabel}")
                    ->greeting("Hello {$notifiable->first_name}!")
                    ->line("Your order status has been updated to: {$statusLabel}")
                    ->line("Order details:")
                    ->line("- Order ID: {$this->order->id}")
                    ->line("- Order Token: {$orderToken}")
                    ->line("- Date Ordered: {$this->order->created_at->format('Y-m-d')}")
                    ->line("- Scheduled Delivery Date: {$scheduledDate}")
                    ->action('View Order Details', url('/orders/' . $this->order->id))
                    ->line('Thank you for choosing GasByGas!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}