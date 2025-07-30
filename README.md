# Items Management System

Система управления элементами с интеграцией Google Sheets для автоматической синхронизации данных.

## 🚀 Возможности

- **CRUD интерфейс** для управления элементами (Items)
- **Статус enum** с значениями `Allowed` и `Prohibited`
- **Генерация тестовых данных** - 1000 записей одной кнопкой
- **Интеграция с Google Sheets** - автоматическая синхронизация каждую минуту
- **Сохранение пользовательских комментариев** в Google Sheets
- **Консольные команды** с progress bar для работы с данными
- **HTTP API** для доступа к функциям через браузер
- **Реактивные обновления** через Model Observers

## 📋 Требования

- PHP 8.2+
- Composer
- Node.js 18+ & NPM
- SQLite

## ⚡ Быстрый старт

### 1. Клонирование репозитория
```bash
git clone https://github.com/peter6754/Items-Management-System.git
cd items-management-system
```

### 2. Установка зависимостей
```bash
composer install
npm install
```

### 3. Настройка окружения
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Создание базы данных
```bash
touch database/database.sqlite  # или migrate
php artisan migrate
```

### 5. Сборка фронтенда
```bash
npm run build
```

### 6. Запуск приложения
```bash
# Основное приложение
composer dev

# Или отдельно:
php artisan serve
npm run dev

# Для автосинхронизации (в отдельном терминале)
php artisan schedule:work
```

Приложение будет доступно по адресу: http://localhost:8000

## 🔧 Настройка Google Sheets

1. Создайте новую Google Таблицу
2. Откройте доступ: "Настройки доступа" → "Изменить" → "Все, у кого есть ссылка"
3. Скопируйте URL таблицы
4. На странице http://127.0.0.1:8000/items перейдите в **Settings** → вставьте URL → **Save Settings**
5. Нажмите **Test Connection** для проверки

### Формат URL:
```
https://docs.google.com/spreadsheets/d/YOUR_SHEET_ID/edit#gid=0
```

## 📖 Использование

### Веб-интерфейс
- **Items** - управление элементами (CRUD операции)
- **Settings** - настройка интеграции с Google Sheets

### Консольные команды
```bash
# Синхронизация с Google Sheets
php artisan sheets:sync

# Получение данных из Google Sheets
php artisan sheets:fetch
php artisan sheets:fetch --count=20

# Автоматическая синхронизация каждую минуту
php artisan schedule:work
```

### HTTP API
```bash
# Получить все данные из Google Sheets
GET /fetch

# Получить ограниченное количество записей
GET /fetch/10
```

### Генерация тестовых данных
1. Перейдите на страницу **Items**
2. Нажмите **Generate 1000 Items** для создания тестовых записей используется батчи, что увеличивает скорость создания.
3. Нажмите **Clear All Items** для очистки таблицы

## 🔄 Логика работы

### Синхронизация с Google Sheets
- **Allowed** элементы автоматически синхронизируются каждую минуту
- **Prohibited** элементы исключаются из синхронизации
- Пользовательские комментарии в колонке G сохраняются при обновлениях
- При изменении статуса элемента происходит мгновенная синхронизация

### Структура Google Sheets
| A | B | C | D | E | F | G |
|---|---|---|---|---|---|---|
| ID | Name | Description | Status | Created At | Updated At | Comments |

Колонка **Comments** предназначена для пользовательских комментариев и сохраняется при всех операциях синхронизации.

## 🛠 Разработка

### Структура проекта
```
app/
├── Models/
│   ├── Item.php           # Модель элемента с enum статусом
│   └── Setting.php        # Модель настроек
├── Http/Controllers/
│   ├── ItemController.php # CRUD для элементов
│   ├── SettingController.php # Управление настройками
│   └── FetchController.php # HTTP API для получения данных
├── Services/
│   └── GoogleSheetsService.php # Сервис интеграции с Google Sheets
├── Console/Commands/
│   ├── SyncGoogleSheets.php  # Команда синхронизации
│   └── FetchGoogleSheets.php # Команда получения данных
└── Observers/
    └── ItemObserver.php   # Наблюдатель за изменениями элементов
```

### Консольные команды
- `sheets:sync` - Синхронизация элементов со статусом "Allowed"
- `sheets:fetch [--count=N]` - Получение данных с прогресс-баром

### HTTP роуты
- `GET /` - Главная страница (перенаправление на Items)
- `GET /items` - Список элементов
- `POST /items` - Создание элемента
- `GET /items/{id}` - Просмотр элемента
- `PUT /items/{id}` - Обновление элемента
- `DELETE /items/{id}` - Удаление элемента
- `GET /settings` - Настройки Google Sheets
- `GET /fetch` - Получение всех данных из Google Sheets
- `GET /fetch/{count}` - Получение ограниченного количества данных
