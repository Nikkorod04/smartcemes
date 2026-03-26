# SmartCEMES - Community Extension and Management System

A comprehensive Laravel-based system for managing community needs assessments, extension programs, and monitoring activities with AI-powered demographic analysis.

## 📋 Table of Contents

- [Project Overview](#project-overview)
- [Key Features](#key-features)
- [Prerequisites](#prerequisites)
- [Installation Guide](#installation-guide)
- [Environment Setup](#environment-setup)
- [Database Setup](#database-setup)
- [Running the Application](#running-the-application)
- [Project Structure](#project-structure)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)

## 🎯 Project Overview

SmartCEMES is a full-stack community management system built with Laravel, Livewire, and Alpine.js. It provides tools for:

- **Community Needs Assessment** - Comprehensive demographic data collection
- **Extension Programs Management** - Plan, track, and evaluate programs
- **AI-Powered Analytics** - Automated insights from assessment data
- **Monitoring & Evaluation** - Track program activities and beneficiary impact

## ✨ Key Features

- 🤖 **AI Analysis** - HuggingFace-powered demographic insights with fallback summaries
- 📊 **Assessment Dashboard** - 11-section community assessment form with calculations
- 👥 **Program Management** - Create and manage extension programs by community
- 📈 **Data Visualization** - Real-time analytics and reporting
- 🔒 **Authentication** - Secure user management with role-based access
- 📱 **Responsive Design** - Mobile-friendly UI with Tailwind CSS
- ⚡ **Real-time Updates** - Livewire components for interactive UI

## 📦 Prerequisites

Before you begin, ensure you have installed:

- **PHP** 8.2 or higher
- **Composer** (PHP package manager)
- **Node.js** 18.x or higher and **npm**
- **MySQL** 8.0 or higher (or any Laravel-compatible database)
- **Git** (for cloning the repository)

### Verify Installations

```bash
php --version
composer --version
node --version
npm --version
mysql --version
```

## 🚀 Installation Guide

### Step 1: Clone the Repository

```bash
git clone https://github.com/Nikkorod04/smartcemes.git
cd smartcemes
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

### Step 3: Install Node Dependencies

```bash
npm install
```

### Step 4: Generate Application Key

```bash
php artisan key:generate
```

This creates a unique encryption key for your application. You should see:
```
Application key set successfully.
```

### Step 5: Build Frontend Assets

```bash
npm run build
```

Or for development with file watching:
```bash
npm run dev
```

## ⚙️ Environment Setup

### Step 1: Copy Environment File

```bash
cp .env.example .env
```

### Step 2: Configure Database Connection

Open `.env` file and update these database variables:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smartcemes
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 3: Configure AI Service (Optional)

If you want to use HuggingFace AI for demographic analysis:

```env
HUGGINGFACE_API_KEY=your_huggingface_api_key
```

**Note:** The application works without this key - it will fall back to pre-generated insights.

### Full .env Example

```env
APP_NAME=SmartCEMES
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smartcemes
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@smartcemes.local
MAIL_FROM_NAME="SmartCEMES"
```

## 🗄️ Database Setup

### Step 1: Create Database

Using MySQL command line:

```sql
CREATE DATABASE smartcemes;
```

Or use your database management tool (phpMyAdmin, MySQL Workbench, etc.)

### Step 2: Run Migrations

```bash
php artisan migrate
```

This creates all necessary tables. You should see completed migration messages.

### Step 3: Seed Sample Data (Optional)

Populate the database with sample communities and data:

```bash
php artisan db:seed
```

This creates:
- 34 sample communities (Leyte & Samar provinces)
- Sample extension programs
- Sample assessment data

### Verify Database Setup

```bash
php artisan tinker
>>> DB::connection()->getPdo();
=> PDO Object (connection successful)
>>> quit
```

## ▶️ Running the Application

### Option 1: Development Server (Recommended for Testing)

```bash
php artisan serve
```

Visit: **http://localhost:8000**

### Option 2: Using Vite Dev Server (for Frontend Development)

Open two terminals:

**Terminal 1:**
```bash
php artisan serve
```

**Terminal 2:**
```bash
npm run dev
```

### Option 3: Production Server

Build assets:
```bash
npm run build
```

Deploy to your hosting environment with:
- `.env` configured for production
- Database migrations run on server
- `composer install --optimize-autoloader --no-dev`

## 📁 Project Structure

```
smartcemes/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Request handlers
│   │   └── Requests/         # Form validation
│   ├── Livewire/             # Real-time components
│   ├── Models/               # Database models
│   ├── Services/             # Business logic (HuggingFaceAIService)
│   └── Observers/            # Model event listeners
├── database/
│   ├── migrations/           # Database schema changes
│   ├── seeders/              # Sample data
│   └── factories/            # Test data generation
├── resources/
│   ├── css/                  # Tailwind CSS
│   ├── js/                   # Alpine.js & Alpine components
│   └── views/                # Blade templates & components
├── routes/
│   ├── web.php              # Web routes
│   ├── api.php              # API routes
│   └── auth.php             # Authentication routes
├── tests/                    # Unit & Feature tests
├── storage/                  # File uploads & cache
├── .env.example              # Environment template
├── composer.json             # PHP dependencies
├── package.json              # Node.js dependencies
└── README.md                 # This file
```

## 🔑 Default Credentials

After running seeders, test with:

- **Email:** admin@smartcemes.local  
- **Password:** password

*Note: Change these immediately in production!*

## 🐛 Troubleshooting

### Issue: "No such file or directory" on .env

**Solution:**
```bash
cp .env.example .env
php artisan key:generate
```

### Issue: Database connection refused

**Check:**
- MySQL is running
- `.env` database credentials are correct
- Database exists

**Reset:**
```bash
php artisan migrate:reset
php artisan migrate
php artisan db:seed
```

### Issue: npm dependencies errors

**Solution:**
```bash
rm -r node_modules package-lock.json
npm install
npm run build
```

### Issue: Livewire components not working

**Solution:**
```bash
php artisan livewire:publish --assets
php artisan config:cache
```

### Issue: Composer dependency conflicts

**Solution:**
```bash
composer update
composer dump-autoload
```

### Issue: Port 8000 already in use

Use a different port:
```bash
php artisan serve --port=8001
```

## 📝 Common Commands

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Run tests
php artisan test

# Create new migration
php artisan make:migration create_table_name

# Create new Livewire component
php artisan make:livewire component-name

# Rollback last migration
php artisan migrate:rollback

# Check routes
php artisan route:list
```

## 🤝 Contributing

To contribute to this project:

1. **Fork the repository**
   ```bash
   # On GitHub.com
   ```

2. **Clone your fork**
   ```bash
   git clone https://github.com/YOUR_USERNAME/smartcemes.git
   cd smartcemes
   ```

3. **Create a feature branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

4. **Make your changes and commit**
   ```bash
   git add .
   git commit -m "Add your feature description"
   ```

5. **Push to your fork**
   ```bash
   git push origin feature/your-feature-name
   ```

6. **Create a Pull Request** on the original repository

## 📞 Support

For issues or questions:

1. Check the [Troubleshooting](#troubleshooting) section
2. Review the [SYSTEM_BLUEPRINT.txt](SYSTEM_BLUEPRINT.txt) for technical details
3. Check GitHub Issues

## 📄 License

This project is created for CAPSTONE purposes.

## ✅ Quick Start Checklist

- [ ] Git and GitHub account
- [ ] PHP 8.2+, Composer, Node.js installed
- [ ] MySQL running and accessible
- [ ] Repository cloned
- [ ] `composer install` completed
- [ ] `npm install` completed
- [ ] `.env` file created and configured
- [ ] `php artisan key:generate` run
- [ ] Database created and migrations run
- [ ] `npm run build` completed
- [ ] `php artisan serve` started
- [ ] Accessed http://localhost:8000 in browser

---

**Ready to go!** 🎉 Your friends can now follow these steps to clone and run SmartCEMES locally.
