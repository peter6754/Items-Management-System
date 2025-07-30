@extends('layouts.app')

@section('title', 'View Item')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Item Details</h1>
            <div class="flex space-x-2">
                <a href="{{ route('items.edit', $item) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    Edit
                </a>
                <form action="{{ route('items.destroy', $item) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                            onclick="return confirm('Are you sure you want to delete this item?')">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="p-6">
        <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-gray-500">ID</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $item->id }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Status</dt>
                <dd class="mt-1">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                        {{ $item->status->value === 'Allowed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $item->status->value }}
                    </span>
                </dd>
            </div>

            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">Name</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $item->name }}</dd>
            </div>

            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">Description</dt>
                <dd class="mt-1 text-sm text-gray-900">
                    {{ $item->description ?: 'No description provided.' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Created At</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $item->created_at->format('M d, Y H:i:s') }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Updated At</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $item->updated_at->format('M d, Y H:i:s') }}</dd>
            </div>
        </dl>

        <div class="mt-6 pt-6 border-t border-gray-200">
            <a href="{{ route('items.index') }}" 
               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                ← Back to Items List
            </a>
        </div>
    </div>
</div>
@endsection