# 🎯 SmartCEMES - Community Extension and Management System

![Laravel](https://img.shields.io/badge/Laravel-11-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![Livewire](https://img.shields.io/badge/Livewire-3-purple)
![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-3-38B2AC)

A comprehensive Laravel-based system for managing community needs assessments, extension programs, and monitoring activities with **AI-powered demographic analysis**.

**GitHub Repository:** https://github.com/Nikkorod04/smartcemes

---

## 📋 Quick Navigation

- 🚀 [Quick Start](#-quick-start)
- 🎯 [Features](#-features)
- 📦 [Prerequisites](#-prerequisites)
- 📖 [Full Setup Guide](README_SETUP.md)
- 🐛 [Troubleshooting](README_SETUP.md#-troubleshooting)
- 🤝 [Contributing](#-contributing)

---

## 🚀 Quick Start

### Adi pagclone

```bash
# 1. Clone the repository
git clone https://github.com/Nikkorod04/smartcemes.git
cd smartcemes

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Database setup
# First, create a MySQL database:
# CREATE DATABASE smartcemes;

# Then run migrations
php artisan migrate
php artisan db:seed  # Optional: adds sample data

# 5. Build frontend
npm run build

# 6. Start the server
php artisan serve

# Visit http://localhost:8000
```

**That's it!** 🎉

For detailed setup guide with troubleshooting, see [README_SETUP.md](README_SETUP.md)

---

## ✨ Features

### 📊 Community Assessment Management
- **11-Section Assessment Form** covering:
  - Demographics (population, families, housing)
  - Economic data (income, employment, livelihood)
  - Educational, health, and social services
  - Problems and opportunities identification
  - Themes analysis

- **Real-time Calculations** - Automatic data analysis and insights
- **Assessment Caching** - 24-hour TTL prevents redundant API calls
- **Export Capabilities** - Download assessment data in multiple formats

### 🤖 AI-Powered Analytics
- **HuggingFace Integration** - Mistral-7B-Instruct model for demographic analysis
- **Smart Fallbacks** - Generates assessment-specific insights even when API fails
- **Auto-Summary Generation** - Creates comprehensive community profiles automatically
- **Cached Results** - Efficient API usage with smart caching

### 👥 Program Management
- **Create & Edit Programs** - Plan extension programs by community
- **Beneficiary Categories** - Track programs for:
  - Women, Youth, Farmers
  - Indigenous Peoples, PWD, Senior Citizens
  - Students, General Community
- **Timeline Tracking** - Plan dates for programs
- **Community Targeting** - Assign programs to specific communities
- **Status Management** - Track program lifecycle (planning → active → completed)

### 📈 Monitoring & Evaluation
- **Activity Tracking** - Log program activities and events
- **Attendance Management** - Track beneficiary attendance
- **Impact Assessment** - Measure program outcomes
- **Real-time Dashboard** - Monitor all programs at a glance

### 🔐 Security & Access Control
- **Authentication System** - Secure user login
- **Role-Based Access** - Different user levels
- **Data Encryption** - Sensitive information protection
- **Audit Trail** - Track changes and activities

### 📱 User Interface
- **Responsive Design** - Works on desktop, tablet, and mobile
- **Livewire Components** - Real-time updates without page refresh
- **Tailwind CSS Styling** - Modern, clean interface
- **Dark Mode Support** - Easy on the eyes

---

## 📦 Prerequisites

Before installation, ensure you have:

| Tool | Version | Purpose |
|------|---------|---------|
| **PHP** | 8.2+ | Backend runtime |
| **Composer** | Latest | PHP package manager |
| **Node.js** | 18+ | Frontend tooling |
| **npm** | 9+ | JavaScript dependencies |
| **MySQL** | 8.0+ | Database |
| **Git** | Latest | Version control |

### Check Your Setup

```bash
php --version
composer --version
node --version
npm --version
mysql --version
git --version
```

---

## 🏗️ System Architecture

```
┌─────────────────────────────────────┐
│     Browser / Frontend (UI)          │
│  Tailwind CSS + Alpine.js            │
└────────────┬────────────────────────┘
             │
┌────────────▼────────────────────────┐
│    Laravel Blade Templates           │
│  Livewire Components                 │
└────────────┬────────────────────────┘
             │
┌────────────▼────────────────────────┐
│      Business Logic Layer            │
│  Controllers, Services, Models       │
└────────────┬────────────────────────┘
             │
┌────────────▼────────────────────────┐
│       Database Layer                 │
│  Eloquent ORM + MySQL                │
└─────────────────────────────────────┘
             │
┌────────────▼────────────────────────┐
│    External AI Service               │
│  HuggingFace API                     │
└─────────────────────────────────────┘
```

---

## 📂 Project Structure

```
smartcemes/
├── app/
│   ├── Http/Controllers/           # Request handlers
│   ├── Livewire/                   # Real-time components
│   ├── Models/                     # Database models
│   ├── Services/                   # Business logic
│   │   └── HuggingFaceAIService.php
│   └── Observers/                  # Event listeners
├── database/
│   ├── migrations/                 # Schema changes
│   ├── seeders/                    # Sample data
│   └── factories/                  # Test data
├── resources/
│   ├── views/                      # Blade templates
│   │   ├── livewire/               # Livewire components
│   │   └── components/             # Reusable components
│   ├── css/                        # Tailwind CSS
│   └── js/                         # Alpine.js
├── routes/
│   ├── web.php                     # Web routes
│   ├── api.php                     # API routes
│   └── auth.php                    # Auth routes
├── tests/                          # Unit & Feature tests
├── storage/                        # Uploads & cache
└── config/                         # Application config
```

---

## 🔑 Technology Stack

### Backend
- **Laravel 11** - PHP web framework
- **Livewire 3** - Real-time UI components
- **Eloquent ORM** - Database abstraction
- **HuggingFace API** - AI/ML integration

### Frontend
- **Blade** - Server-side templating
- **Alpine.js** - Lightweight JavaScript framework
- **Tailwind CSS** - Utility-first CSS framework
- **Vite** - Frontend build tool

### Database
- **MySQL 8.0+** - Relational database
- **Laravel Migrations** - Schema versioning

### Testing & Quality
- **PHPUnit** - Unit testing framework
- **Laravel Pint** - PHP code formatter
- **Pest** - Testing framework (optional)

---

## 🚀 Running the Application

### Development Mode (Recommended)

```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Start Vite dev server (optional, for hot reload)
npm run dev

# Visit http://localhost:8000
```

### Production Build

```bash
# Build optimized assets
npm run build

# Clear caches
php artisan config:cache
php artisan route:cache

# Deploy to hosting environment
```

---

## 📝 Database Schema

### Key Tables
- `communities` - Community information
- `community_needs_assessments` - Assessment data
- `community_assessment_summaries` - AI-generated summaries
- `extension_programs` - Program definitions
- `program_activities` - Activities & events
- `activity_attendances` - Beneficiary tracking
- `users` - User accounts

### Example: Get Communities

```bash
php artisan tinker
>>> \App\Models\Community::pluck('name', 'id')
```

---

## 🛠️ Common Commands

```bash
# Laravel Artisan
php artisan migrate              # Run database migrations
php artisan db:seed             # Seed sample data
php artisan tinker              # PHP shell
php artisan test                # Run tests

# Cache & Queue
php artisan cache:clear         # Clear all caches
php artisan config:cache        # Cache configuration
php artisan queue:work          # Process queued jobs

# Development
npm run dev                      # Watch frontend files
npm run build                    # Production build
composer update                  # Update dependencies
```

---

## 📥 Importing Community Data to JotForm

To use communities in a JotForm dropdown:

```bash
# Run the community export script
php get-communities.php

# Copy the CSV format output
# Paste into JotForm dropdown field settings
```

Expected output:
```
Value,Label
"1","Diit Tacloban (Tacloban City, Leyte)"
"2","San Jose Tacloban (Tacloban City, Leyte)"
...
```

---

## 🐛 Common Issues & Solutions

### Port 8000 Already in Use
```bash
php artisan serve --port=8001
```

### Database Connection Error
- Verify MySQL is running
- Check `.env` credentials
- Ensure database exists

### Composer Dependency Conflict
```bash
composer install --ignore-platform-reqs
```

### Node Modules Issues
```bash
rm -rf node_modules package-lock.json
npm install
npm run build
```

For more solutions, see [README_SETUP.md](README_SETUP.md#-troubleshooting)

---

## 🤝 Contributing

We welcome contributions! Here's how:

1. **Fork the repository** on GitHub
2. **Clone your fork** 
   ```bash
   git clone https://github.com/YOUR_USERNAME/smartcemes.git
   ```
3. **Create a feature branch**
   ```bash
   git checkout -b feature/amazing-feature
   ```
4. **Commit your changes**
   ```bash
   git commit -m "Add amazing feature"
   ```
5. **Push to your fork**
   ```bash
   git push origin feature/amazing-feature
   ```
6. **Open a Pull Request** on the main repository

---

## 📞 Need Help?

1. **Installation Issues?** → See [README_SETUP.md](README_SETUP.md)
2. **Technical Details?** → Check [SYSTEM_BLUEPRINT.txt](SYSTEM_BLUEPRINT.txt)
3. **Specific Feature?** → Review [API_DOCUMENTATION.md](mdff/API_DOCUMENTATION.md)
4. **Assessment Guide?** → See [CESO_FORMS.md](CESO_FORMS.md)

---

## 📄 License

This project is created for CAPSTONE purposes by Nikko Rodriguez.

---

## 👨‍💻 Built By

**Nikko Rodriguez**
- GitHub: [@Nikkorod04](https://github.com/Nikkorod04)
- Email: 2204866@lnu.edu.ph

---

## ✅ Quick Checklist for Your Friends

Before your friends start, make sure they have:

- [ ] Git installed and GitHub account
- [ ] PHP 8.2+, Composer, Node.js, MySQL installed
- [ ] About 500MB disk space available
- [ ] 15-20 minutes for first-time setup

Then they can simply:
```bash
git clone https://github.com/Nikkorod04/smartcemes.git
cd smartcemes
# Follow the Quick Start section above
```

---

**Happy coding!** 🚀

