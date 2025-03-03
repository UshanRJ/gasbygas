<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\OutletProduct;
use Filament\Notifications\Notification;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->disabled(fn ($record) => !in_array($record->status, ['new', 'cancelled']))
                ->tooltip(fn ($record) => !in_array($record->status, ['new', 'cancelled']) 
                    ? 'Orders in processing, delivered, or rescheduled status cannot be deleted' 
                    : null),
        ];
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $originalStatus = $this->record->status;
        $newStatus = $data['status'];
        
        // Define status groups
        $activeStatuses = ['new', 'processing', 'delivered'];
        $inactiveStatuses = ['cancelled', 'rescheduled'];
        
        // Only validate stock if:
        // 1. Moving from inactive to active status OR
        // 2. Staying in active status AND changing quantities
        $needsStockValidation = 
            (in_array($newStatus, $activeStatuses) && in_array($originalStatus, $inactiveStatuses)) ||
            (in_array($newStatus, $activeStatuses) && in_array($originalStatus, $activeStatuses));
        
        if ($needsStockValidation && !empty($data['outlet_id']) && !empty($data['orderItems'])) {
            $outletId = $data['outlet_id'];
            
            foreach ($data['orderItems'] as $index => $orderItem) {
                if (empty($orderItem['product_id']) || empty($orderItem['quantity'])) {
                    continue;
                }
                
                $productId = $orderItem['product_id'];
                $quantity = $orderItem['quantity'];
                
                // Get the original quantity if this is an existing order item
                $originalQuantity = 0;
                if (isset($orderItem['id'])) {
                    $originalOrderItem = $this->record->orderItems->find($orderItem['id']);
                    if ($originalOrderItem && $originalOrderItem->product_id == $productId) {
                        $originalQuantity = $originalOrderItem->quantity;
                    }
                }
                
                // Calculate additional stock needed
                $additionalStock = 0;
                
                if (in_array($originalStatus, $inactiveStatuses) && in_array($newStatus, $activeStatuses)) {
                    // Moving from inactive to active - need full quantity
                    $additionalStock = $quantity;
                } else if (in_array($originalStatus, $activeStatuses) && in_array($newStatus, $activeStatuses)) {
                    // Staying in active status - only need additional if quantity increased
                    $additionalStock = max(0, $quantity - $originalQuantity);
                }
                
                if ($additionalStock > 0) {
                    // Check outlet product stock
                    $outletProduct = OutletProduct::where([
                        'outlet_id' => $outletId,
                        'product_id' => $productId,
                    ])->first();
                    
                    if (!$outletProduct || $outletProduct->stock < $additionalStock) {
                        // Stock not available, abort with error
                        Notification::make()
                            ->title('Insufficient stock!')
                            ->body("There is not enough additional stock available for the selected product. Need {$additionalStock} more units.")
                            ->danger()
                            ->persistent()
                            ->send();
                        
                        $this->halt();
                        break;
                    }
                }
            }
        }
        
        return $data;
    }
}