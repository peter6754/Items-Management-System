@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Google Sheets Integration Settings</h1>
        </div>

        <div class="p-6">
            <form action="{{ route('settings.update') }}" method="POST" class="mb-6">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="google_sheets_url" class="block text-sm font-medium text-gray-700 mb-2">
                        Google Sheets URL <span class="text-red-500">*</span>
                    </label>
                    <input type="url" name="google_sheets_url" id="google_sheets_url"
                        value="{{ old('google_sheets_url', $googleSheetsUrl) }}"
                        placeholder="https://docs.google.com/spreadsheets/d/YOUR_SHEET_ID/edit#gid=0"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('google_sheets_url') border-red-500 @enderror"
                        required>
                    @error('google_sheets_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Убедитесь, что Google таблица доступена общедоступна и имеет разрешения редактирования для всех, кто
                        имеет ссылку
                    </p>
                </div>

                <div class="flex justify-start">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700">
                        Save Settings
                    </button>
                </div>
            </form>

            @if($googleSheetsUrl)
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>

                    <div class="flex space-x-4">
                        <form action="{{ route('settings.test') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-green-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-green-700">
                                Test Connection
                            </button>
                        </form>

                        <form action="{{ route('settings.sync') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-purple-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-purple-700">
                                Synchronize
                            </button>
                        </form>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6 mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">How it works?</h3>
                    <div class="text-sm text-gray-600 space-y-2">
                        <p>Элементы со статусом «Разрешено» автоматически синхронизируются с вашей Google Таблицей каждую минуту
                        </p>
                        <p>При изменении статуса элемента с «Разрешено» на «Запрещено» он удаляется из Таблицы</p>
                        <p>При изменении статуса элемента с «Запрещено» на «Разрешено» он добавляется в Таблицу</p>
                        <p>При удалении элемента из базы данных он также удаляется из Таблицы</p>
                        <p>Синхронизация выполняется автоматически в фоновом режиме при запуске планировщика задач Laravel</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection