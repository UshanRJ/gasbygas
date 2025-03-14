<?php

namespace App\Observers;

use App\Models\Schedule;
use App\Models\OutletProduct;
use Filament\Notifications\Notification;

class ScheduleObserver
{
    /**
     * Handle the Schedule "created" event.
     */
    public function created(Schedule $schedule): void
    {
        // Check if the schedule is in approved status
        if ($schedule->status === 'approved') {
            $this->updateOutletProductStock($schedule);
        }
    }

    /**
     * Handle the Schedule "updated" event.
     */
    public function updated(Schedule $schedule): void
    {
        // Check if status was changed to approved
        if ($schedule->status === 'approved' && $schedule->getOriginal('status') !== 'approved') {
            $this->updateOutletProductStock($schedule);
        }
        
        // Check if status was changed to cancelled from approved
        if ($schedule->status === 'cancelled' && $schedule->getOriginal('status') === 'approved') {
            $this->deductOutletProductStock($schedule);
        }
    }
    
    /**
     * Handle the Schedule "deleted" event.
     */
    public function deleted(Schedule $schedule): void
    {
        // Only deduct stock if the schedule was in approved status
        if ($schedule->status === 'approved') {
            $this->deductOutletProductStock($schedule);
        }
    }

    /**
     * Update the outlet product stock based on the schedule (add stock)
     */
    private function updateOutletProductStock(Schedule $schedule): void
    {
        // Add logging to track observer execution
        \Illuminate\Support\Facades\Log::info('ScheduleObserver: updateOutletProductStock called', [
            'schedule_id' => $schedule->id,
            'quantity' => $schedule->quantity,
            'status' => $schedule->status
        ]);
        
        // Find existing outlet product or create a new one
        $outletProduct = OutletProduct::firstOrNew([
            'outlet_id' => $schedule->outlet_id,
            'product_id' => $schedule->product_id,
        ]);

        // If this is a new record, set initial stock to 0
        if (!$outletProduct->exists) {
            $outletProduct->stock = 0;
        }

        // Add the schedule quantity to the stock
        $outletProduct->stock += $schedule->quantity;
        
        // Save the outlet product
        $outletProduct->save();
        
        // Show notification if in Filament context
        if (class_exists(Notification::class)) {
            Notification::make()
                ->title('Stock Updated')
                ->body("Added {$schedule->quantity} units to outlet stock")
                ->success()
                ->send();
        }
    }
    
    /**
     * Deduct the outlet product stock when a schedule is deleted or cancelled
     */
    private function deductOutletProductStock(Schedule $schedule): void
    {
        // Find the outlet product
        $outletProduct = OutletProduct::where([
            'outlet_id' => $schedule->outlet_id,
            'product_id' => $schedule->product_id,
        ])->first();
        
        // If outlet product exists
        if ($outletProduct) {
            // Deduct the quantity
            $outletProduct->stock -= $schedule->quantity;
            
            // Ensure stock doesn't go below zero
            if ($outletProduct->stock < 0) {
                $outletProduct->stock = 0;
            }
            
            // Save the outlet product
            $outletProduct->save();
            
            // Show notification if in Filament context
            if (class_exists(Notification::class)) {
                Notification::make()
                    ->title('Stock Updated')
                    ->body("Deducted {$schedule->quantity} units from outlet stock")
                    ->warning()
                    ->send();
            }
        }
    }
}