# 🚀 HabitAi

**HabitAi** — это приложение, созданное для повышения продуктивности людей с помощью искусственного интеллекта (AI).

Проект находится в **активной разработке**.

[🇬🇧 Read in English](README.md)

## 🛠️ Технологический стек

* **Frontend:** Flutter, React, Vue
* **Backend:** Symfony (PHP 8.3)
* **Архитектура:** Clean Architecture, Domain-Driven Design (DDD), Atomic Design

## ✨ Основные возможности

* 📌 **Управление задачами** (Task Management)
* 🧭 **Матрица Эйзенхауэра** для приоритизации задач
* ⏳ **Pomodoro-таймер** для фокусированной работы
* 🌍 **Мультиязычность** (поддержка 5 языков)
* ⚡ **Новые улучшения фронтенда:**

    * Реализована система **Cache-Then-Network (CTN)** для быстрой загрузки данных
    * Интеграция **IndexedDB (IDB)** для офлайн-доступа и снижения нагрузки на сервер
    * Централизованное кэширование и оптимизированное управление состоянием

---

## 📦 Установка и запуск

### 1. Клонирование репозитория

```bash
git clone https://github.com/LexaFrontDev/HabitAi.git
cd HabitAi
composer install
npm install
```

### 2. Поднятие Docker-контейнеров

```bash
make up-docker-build
```

### 3. Подключение к PHP-контейнеру

```bash
make sh
```

### 4. Запуск миграций

```bash
php bin/console doctrine:migrations:migrate -n
```

### 5. Загрузка фикстур

```bash
php bin/console app:resources:sync
exit
```

### 6. Адрес фронтенда

```bash
http://localhost:10001/
```

### 7. Остановка и удаление контейнеров

```bash
make down
```

---

## 📌 Статус проекта

🔧 В активной разработке.
Следите за обновлениями и новыми релизами!

