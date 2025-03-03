<?php

namespace App\Observers;

use App\Models\Schedule;
use App\Models\OutletProduct;

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
        // Only process if status changed to approved
        if ($schedule->status === 'approved' && $schedule->getOriginal('status') !== 'approved') {
            $this->updateOutletProductStock($schedule);
        }
    }

    /**
     * Update the outlet product stock based on the schedule
     */
    private function updateOutletProductStock(Schedule $schedule): void
    {
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
    }
}