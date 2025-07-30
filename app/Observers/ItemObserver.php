<?php

namespace App\Observers;

use App\Models\Item;
use App\Models\ItemStatus;
use App\Services\GoogleSheetsService;

class ItemObserver
{
    /**
     * Handle the Item "created" event.
     */
    public function created(Item $item): void
    {
        if ($item->status === ItemStatus::Allowed) {
            $service = new GoogleSheetsService();
            $service->addItem($item);
        }
    }

    /**
     * Handle the Item "updated" event.
     */
    public function updated(Item $item): void
    {
        $previousStatus = $item->getOriginal('status');
        
        if ($previousStatus !== null) {
            $service = new GoogleSheetsService();
            $service->updateItem($item, $previousStatus);
        }
    }

    /**
     * Handle the Item "deleted" event.
     */
    public function deleted(Item $item): void
    {
        $service = new GoogleSheetsService();
        $service->removeItem($item);
    }

    /**
     * Handle the Item "restored" event.
     */
    public function restored(Item $item): void
    {
        if ($item->status === ItemStatus::Allowed) {
            $service = new GoogleSheetsService();
            $service->addItem($item);
        }
    }

    /**
     * Handle the Item "force deleted" event.
     */
    public function forceDeleted(Item $item): void
    {
        $service = new GoogleSheetsService();
        $service->removeItem($item);
    }
}
