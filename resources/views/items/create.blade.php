@extends('layouts.app')

@section('title', 'Create Item')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-gray-900">Create New Item</h1>
    </div>

    <form action="{{ route('items.store') }}" method="POST" class="p-6">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Name <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="name" 
                   id="name" 
                   value="{{ old('name') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                   required>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                Description
            </label>
            <textarea name="description" 
                      id="description" 
                      rows="4"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                Status <span class="text-red-500">*</span>
            </label>
            <select name="status" 
                    id="status"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror"
                    required>
                <option value="">Select Status</option>
                <option value="Allowed" {{ old('status') === 'Allowed' ? 'selected' : '' }}>Allowed</option>
                <option value="Prohibited" {{ old('status') === 'Prohibited' ? 'selected' : '' }}>Prohibited</option>
            </select>
            @error('status')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('items.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700">
                Create Item
            </button>
        </div>
    </form>
</div>
@endsection