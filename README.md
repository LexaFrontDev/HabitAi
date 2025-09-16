# 🚀 HabitAi

**HabitAi** is an application designed to boost productivity with the help of Artificial Intelligence (AI).

Project is currently under **active development**.

[🇷🇺 Читать на русском](README.ru.md)

---

## 🛠️ Tech Stack

* **Frontend:** Flutter, React, Vue
* **Backend:** Symfony (PHP 8.3)
* **Architecture:** Clean Architecture, Domain-Driven Design (DDD), Atomic Design

## ✨ Features

* 📌 **Task Management**
* 🧭 **Eisenhower Matrix** for prioritization
* ⏳ **Pomodoro Timer** for focused work
* 🌍 **Multilingual Support** (5 languages)
* ⚡ **Frontend improvements**:

    * **Cache-Then-Network (CTN)** system for optimized data fetching
    * **IndexedDB (IDB)** integration for offline access and reduced server load
    * Centralized caching and efficient state management

---

## 📦 Installation & Usage

### 1. Clone the repository

```bash
git clone https://github.com/LexaFrontDev/HabitAi.git
cd HabitAi
composer install
npm install
```

### 2. Start Docker containers

```bash
make up-docker-build
```

### 3. Enter PHP container

```bash
make sh
```

### 4. Run migrations

```bash
php bin/console doctrine:migrations:migrate -n
```

### 5. Load fixtures

```bash
php bin/console app:resources:sync
exit
```

### 6. Start frontend

```bash
npm run
```

### 7. Stop and remove containers

```bash
make down
```

---

## 📌 Project Status

🔧 Under active development.
Stay tuned for updates and new releases!

