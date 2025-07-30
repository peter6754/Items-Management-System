<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Setting;
use Revolution\Google\Sheets\Facades\Sheets;
use Illuminate\Support\Facades\Log;

class GoogleSheetsService
{
    private $spreadsheetId;
    private $sheetName = 'Items';

    public function __construct()
    {
        $this->spreadsheetId = $this->extractSpreadsheetId(Setting::get('google_sheets_url'));
    }

    private function extractSpreadsheetId($url)
    {
        if (!$url) {
            return null;
        }

        preg_match('/\/spreadsheets\/d\/([a-zA-Z0-9-_]+)/', $url, $matches);
        return $matches[1] ?? null;
    }

    public function syncItems()
    {
        if (!$this->spreadsheetId) {
            Log::warning('Google Sheets URL not configured');
            return false;
        }

        try {
            $allowedItems = Item::allowed()->get();
            
            $existingComments = $this->getExistingComments();
            
            $this->clearDataOnly();
            
            $this->addHeaders();
            
            if ($allowedItems->isNotEmpty()) {
                $rows = $allowedItems->map(function ($item) use ($existingComments) {
                    $row = [
                        $item->id,
                        $item->name,
                        $item->description,
                        $item->status->value,
                        $item->created_at->format('Y-m-d H:i:s'),
                        $item->updated_at->format('Y-m-d H:i:s'),
                        $existingComments[$item->id] ?? ''
                    ];
                    return $row;
                })->toArray();

                $this->appendRows($rows);
            }

            Log::info('Google Sheets sync completed', ['items_synced' => $allowedItems->count()]);
            return true;

        } catch (\Exception $e) {
            Log::error('Google Sheets sync failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    private function clearSheet()
    {
        try {
            Sheets::spreadsheet($this->spreadsheetId)
                ->sheet($this->sheetName)
                ->clear();
        } catch (\Exception $e) {
            Log::warning('Failed to clear sheet, it might not exist yet', ['error' => $e->getMessage()]);
        }
    }

    private function clearDataOnly()
    {
        try {
            Sheets::spreadsheet($this->spreadsheetId)
                ->sheet($this->sheetName)
                ->clear();
        } catch (\Exception $e) {
            Log::warning('Failed to clear sheet data', ['error' => $e->getMessage()]);
        }
    }

    private function getExistingComments()
    {
        try {
            $data = Sheets::spreadsheet($this->spreadsheetId)
                ->sheet($this->sheetName)
                ->get();
            
            $comments = [];
            if (!empty($data) && count($data) > 1) {
                foreach (array_slice($data, 1) as $row) {
                    if (isset($row[0]) && isset($row[6])) {
                        $itemId = $row[0];
                        $comment = $row[6] ?? '';
                        if (!empty($comment)) {
                            $comments[$itemId] = $comment;
                        }
                    }
                }
            }
            
            return $comments;
        } catch (\Exception $e) {
            Log::warning('Failed to get existing comments', ['error' => $e->getMessage()]);
            return [];
        }
    }

    private function addHeaders()
    {
        $headers = [['ID', 'Name', 'Description', 'Status', 'Created At', 'Updated At', 'Comments']];
        
        Sheets::spreadsheet($this->spreadsheetId)
            ->sheet($this->sheetName)
            ->append($headers);
    }

    private function appendRows($rows)
    {
        Sheets::spreadsheet($this->spreadsheetId)
            ->sheet($this->sheetName)
            ->append($rows);
    }

    public function testConnection()
    {
        if (!$this->spreadsheetId) {
            return false;
        }

        try {
            $sheets = Sheets::spreadsheet($this->spreadsheetId)->sheetList();
            return !empty($sheets);
        } catch (\Exception $e) {
            Log::error('Google Sheets connection test failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function addItem(Item $item)
    {
        if (!$this->spreadsheetId || $item->status !== \App\Models\ItemStatus::Allowed) {
            return;
        }

        try {
            $row = [
                $item->id,
                $item->name,
                $item->description,
                $item->status->value,
                $item->created_at->format('Y-m-d H:i:s'),
                $item->updated_at->format('Y-m-d H:i:s'),
                ''
            ];

            Sheets::spreadsheet($this->spreadsheetId)
                ->sheet($this->sheetName)
                ->append([$row]);

            Log::info('Item added to Google Sheets', ['item_id' => $item->id]);
        } catch (\Exception $e) {
            Log::error('Failed to add item to Google Sheets', ['item_id' => $item->id, 'error' => $e->getMessage()]);
        }
    }

    public function removeItem(Item $item)
    {
        if (!$this->spreadsheetId) {
            return;
        }

        try {
            $this->syncItems();
            Log::info('Item removed from Google Sheets (via full sync)', ['item_id' => $item->id]);
        } catch (\Exception $e) {
            Log::error('Failed to remove item from Google Sheets', ['item_id' => $item->id, 'error' => $e->getMessage()]);
        }
    }

    public function updateItem(Item $item, $previousStatus = null)
    {
        if (!$this->spreadsheetId) {
            return;
        }

        try {
            if ($previousStatus === \App\Models\ItemStatus::Allowed && $item->status !== \App\Models\ItemStatus::Allowed) {
                $this->syncItems();
                Log::info('Item status changed from Allowed to Prohibited, removed from sheet', ['item_id' => $item->id]);
            } elseif ($previousStatus !== \App\Models\ItemStatus::Allowed && $item->status === \App\Models\ItemStatus::Allowed) {
                $this->addItem($item);
                Log::info('Item status changed to Allowed, added to sheet', ['item_id' => $item->id]);
            } elseif ($item->status === \App\Models\ItemStatus::Allowed) {
                $this->syncItems();
                Log::info('Allowed item updated in sheet', ['item_id' => $item->id]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to update item in Google Sheets', ['item_id' => $item->id, 'error' => $e->getMessage()]);
        }
    }

    public function fetchDataWithComments($limit = null)
    {
        if (!$this->spreadsheetId) {
            return [];
        }

        try {
            $data = Sheets::spreadsheet($this->spreadsheetId)
                ->sheet($this->sheetName)
                ->get();

            if (empty($data) || count($data) <= 1) {
                return [];
            }

            $rows = array_slice($data, 1);
            if ($limit && $limit > 0) {
                $rows = array_slice($rows, 0, $limit);
            }

            $result = [];
            foreach ($rows as $row) {
                if (isset($row[0])) {
                    $result[] = [
                        'id' => $row[0] ?? '',
                        'name' => $row[1] ?? '',
                        'description' => $row[2] ?? '',
                        'status' => $row[3] ?? '',
                        'created_at' => $row[4] ?? '',
                        'updated_at' => $row[5] ?? '',
                        'comment' => $row[6] ?? ''
                    ];
                }
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('Failed to fetch data from Google Sheets', ['error' => $e->getMessage()]);
            return [];
        }
    }
}