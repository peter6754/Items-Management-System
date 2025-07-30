@extends('layouts.app')

@section('title', 'Items List')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Items</h1>
            <div class="flex space-x-2">
                <a href="{{ route('items.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    Add New Item
                </a>
                <form action="{{ route('items.generate') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Generate 1000 Items
                    </button>
                </form>
                <form action="{{ route('items.clear') }}" method="POST" class="inline" 
                      onsubmit="return confirm('Are you sure you want to clear all items?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Clear All Items
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Name
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Description
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Created At
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($items as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $item->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ Str::limit($item->description, 100) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $item->status->value === 'Allowed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $item->status->value }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $item->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('items.show', $item) }}" 
                               class="text-blue-600 hover:text-blue-900">View</a>
                            <a href="{{ route('items.edit', $item) }}" 
                               class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('items.destroy', $item) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No items found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($items->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $items->links() }}
        </div>
    @endif
</div>
@endsection