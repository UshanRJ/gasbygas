<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\OutletProduct;
use Filament\Notifications\Notification;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Validate stock availability before creating the order
        if (!empty($data['outlet_id']) && !empty($data['orderItems'])) {
            $outletId = $data['outlet_id'];

            foreach ($data['orderItems'] as $index => $orderItem) {
                if (empty($orderItem['product_id']) || empty($orderItem['quantity'])) {
                    continue;
                }

                $productId = $orderItem['product_id'];
                $quantity = $orderItem['quantity'];

                // Check outlet product stock
                $outletProduct = OutletProduct::where([
                    'outlet_id' => $outletId,
                    'product_id' => $productId,
                ])->first();

                if (!$outletProduct || $outletProduct->stock < $quantity) {
                    // Stock not available, abort with error
                    $this->halt();

                    Notification::make()
                        ->title('Insufficient stock!')
                        ->body("There is not enough stock available for the selected product.")
                        ->danger()
                        ->persistent()
                        ->send();

                    break;
                }
            }
        }

        return $data;
    }
}
