<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\GoogleSheetsService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $googleSheetsUrl = Setting::get('google_sheets_url');
        return view('settings.index', compact('googleSheetsUrl'));
    }

    /**
     * Update Google Sheets settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'google_sheets_url' => 'required|url',
        ]);

        Setting::set('google_sheets_url', $request->google_sheets_url);

        $service = new GoogleSheetsService();
        if ($service->testConnection()) {
            return redirect()->route('settings.index')
                ->with('success', 'Google Sheets URL updated and connection verified successfully.');
        } else {
            return redirect()->route('settings.index')
                ->with('warning', 'Google Sheets URL updated, but connection could not be verified. Please check the URL and permissions.');
        }
    }

    /**
     * Test Google Sheets connection.
     */
    public function testConnection()
    {
        $service = new GoogleSheetsService();
        $connected = $service->testConnection();

        if ($connected) {
            return redirect()->route('settings.index')
                ->with('success', 'Google Sheets connection test passed.');
        } else {
            return redirect()->route('settings.index')
                ->with('error', 'Google Sheets connection test failed. Please check the URL and permissions.');
        }
    }

    /**
     * Manually trigger sync.
     */
    public function sync()
    {
        $service = new GoogleSheetsService();
        $result = $service->syncItems();

        if ($result) {
            return redirect()->route('settings.index')
                ->with('success', 'Manual sync completed successfully.');
        } else {
            return redirect()->route('settings.index')
                ->with('error', 'Manual sync failed. Please check the logs.');
        }
    }
}
