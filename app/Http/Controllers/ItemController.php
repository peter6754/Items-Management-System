<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemStatus;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::paginate(15);
        return view('items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:Allowed,Prohibited',
        ]);

        Item::create($request->only(['name', 'description', 'status']));

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:Allowed,Prohibited',
        ]);

        $item->update($request->only(['name', 'description', 'status']));

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }

    /**
     * Generate 1000 items with equal distribution of statuses.
     */
    public function generate()
    {
        $data = [];
        $now = now();
        $batchSize = 100;
        
        for ($i = 0; $i < 1000; $i++) {
            $data[] = [
                'name' => fake()->words(3, true),
                'description' => fake()->sentence(),
                'status' => $i % 2 === 0 ? 'Allowed' : 'Prohibited',
                'created_at' => $now,
                'updated_at' => $now,
            ];
            
            if (count($data) >= $batchSize) {
                \DB::table('items')->insert($data);
                $data = [];
            }
        }
        
        if (!empty($data)) {
            \DB::table('items')->insert($data);
        }

        return redirect()->route('items.index')->with('success', '1000 items generated successfully.');
    }

    /**
     * Clear all items from the table.
     */
    public function clear()
    {
        Item::truncate();
        return redirect()->route('items.index')->with('success', 'All items cleared successfully.');
    }
}
