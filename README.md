# Items Management System

Система управления элементами с интеграцией Google Sheets для автоматической синхронизации данных.

## 🚀 Возможности

- **CRUD интерфейс** для управления элементами (Items)
- **Статус enum** с значениями `Allowed` и `Prohibited`
- **Генерация тестовых данных** - 1000 записей одной кнопкой
- **Интеграция с Google Sheets** - автоматическая синхронизация каждую минуту
- **Сохранение пользовательских комментариев** в Google Sheets
- **Консольные команды** с progress bar для работы с данными
- **Реактивные обновления** через Model Observers
- **HTTP API** /fetch, /fetch/id

## 📋 Требования

- PHP 8.2+
- Composer
- Node.js 18+ & NPM
- SQLite

## ⚡ Быстрый старт

Сonsole Сloude Google:
  - Зайдите в https://console.cloud.google.com/
  - Создайте проект или выберите существующий
  - Включите Google Sheets API
  - Создайте Service Account и сгенерируйте json key
  - Скачайте JSON файл с ключами и на его основе создайте в корне проекта google-credentials.json!!!!!!!!!
