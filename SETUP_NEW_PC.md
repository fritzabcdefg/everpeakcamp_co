# Complete Setup Guide for New PC

## Prerequisites - Install These First

### 1. PHP 8.2+
- Download from: https://www.php.net/downloads
- Choose "Windows Builds" → Latest stable release
- During installation: Enable extensions (cURL, GD, OpenSSL, PDO)
- Add PHP to PATH environment variables

### 2. Composer
- Download from: https://getcomposer.org/download
- Run installer (Windows)
- Verify installation: `composer --version`

### 3. Node.js & npm
- Download from: https://nodejs.org (LTS version recommended)
- Verify installation: `node --version` and `npm --version`

### 4. Database
- **PostgreSQL 12+** (RECOMMENDED - project uses DATE_TRUNC)
  - Download: https://www.postgresql.org/download/windows
  - Remember DB password during installation
  OR
- **MySQL 8.0+**
  - Download: https://dev.mysql.com/downloads/mysql

### 5. Git (Optional but Recommended)
- Download: https://git-scm.com/download/win
- Verify: `git --version`

---

## Setup Steps (Run in Order)

### Step 1: Open PowerShell and Navigate to Project
```powershell
cd c:\everpeakcamp_co
```

### Step 2: Install PHP Dependencies
```powershell
composer install
```

### Step 3: Install Node Dependencies
```powershell
npm install
```

### Step 4: Create Environment File
```powershell
# Copy example to .env
copy .env.example .env
```

### Step 5: Generate Application Key
```powershell
php artisan key:generate
```

### Step 6: Configure Database in .env
Open `.env` file and update these lines:

**For PostgreSQL:**
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=everpeakcamp
DB_USERNAME=postgres
DB_PASSWORD=your_password_here
```

**For MySQL:**
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=everpeakcamp
DB_USERNAME=root
DB_PASSWORD=your_password_here
```

### Step 7: Create Database (in your DB tool, not PowerShell)
- PostgreSQL: Create database called `everpeakcamp`
- MySQL: Create database called `everpeakcamp`

### Step 8: Run Database Migrations
```powershell
php artisan migrate
```

### Step 9: Seed Database with Test Data
```powershell
php artisan db:seed
```

### Step 10: Build Frontend Assets
```powershell
npm run build
```

### Step 11: Start Development Server
```powershell
php artisan serve
```

You should see:
```
Laravel development server started on http://127.0.0.1:8000
```

**Open browser to:** http://127.0.0.1:8000

---

## Test Accounts (After Seeding)

### Admin Account
- Email: `admin@everpeakcamp.test`
- Password: `password`

### Customer Account
- Email: `customer@everpeakcamp.test`
- Password: `password`

---

## Development Workflow

### For CSS/JS Changes
```powershell
npm run dev
# This watches for changes and rebuilds automatically
# Keep this running in background while developing
```

### For Backend Changes
```powershell
php artisan serve
# Keep this running to test changes
```

---

## Essential Commands During Development

### When You Make Changes to Routes
```powershell
# Clear route cache (if routes not showing up)
php artisan route:clear

# View all available routes
php artisan route:list

# Cache routes for production (optional during development)
php artisan route:cache
```

### When You Make Changes to Config Files
```powershell
php artisan config:clear
```

### When You Make Changes to Cache/Views
```powershell
php artisan cache:clear
php artisan view:clear
```

### When You Make Changes to Models/Migrations
```powershell
# Run new migrations
php artisan migrate

# Reset and re-seed database (WARNING: Deletes all data)
php artisan migrate:fresh --seed

# Rollback last migration
php artisan migrate:rollback
```

---

## Full Development Command Sequence

Use this sequence when you've made multiple changes:

```powershell
# 1. Stop the current server (Ctrl+C in terminal)

# 2. Clear all caches
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear

# 3. Rebuild frontend (if CSS/JS changed)
npm run build
# OR for development with watch:
npm run dev

# 4. Run migrations (if database changed)
php artisan migrate

# 5. View available routes (optional - to verify)
php artisan route:list

# 6. Start the server again
php artisan serve
```

Visit: **http://127.0.0.1:8000**

---

## Quick Reference - Command Meanings

| Command | Purpose |
|---------|---------|
| `php artisan serve` | Start development server on http://127.0.0.1:8000 |
| `php artisan route:list` | View all routes (helpful for debugging) |
| `php artisan route:clear` | Clear route cache (use if routes not working) |
| `php artisan route:cache` | Cache routes for production (improves speed) |
| `php artisan config:clear` | Clear config cache |
| `php artisan cache:clear` | Clear all caches |
| `php artisan view:clear` | Clear compiled views |
| `php artisan migrate` | Run pending migrations |
| `php artisan migrate:fresh --seed` | Reset DB and re-seed (DELETES ALL DATA) |
| `npm run dev` | Watch CSS/JS for changes and rebuild |
| `npm run build` | Build production assets |

---

## File/Folder Permissions

Make sure these folders are writable (Windows usually handles this):
- `storage/`
- `bootstrap/cache/`

---

## Environment Variables Reference

Key settings in `.env`:
```
APP_NAME=EverpeakCamp
APP_ENV=local
APP_DEBUG=true
APP_KEY=base64:xxxxx (generated by artisan key:generate)
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=everpeakcamp
DB_USERNAME=postgres
DB_PASSWORD=yourpassword

MAIL_MAILER=log
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_email@example.com
MAIL_PASSWORD=your_password

SCOUT_DRIVER=collection
```

---

## Troubleshooting

### "php command not found"
- PHP not in PATH. Reinstall and check "Add PHP to PATH"

### Composer command not found
- Composer not installed or not in PATH

### npm run build fails
- Run: `npm install` again
- Delete `node_modules/` folder and reinstall

### Database connection error
- Verify DB is running
- Check credentials in `.env`
- Confirm database was created

### Migrations fail
- Make sure database exists
- Check credentials in `.env`
- Run: `php artisan migrate:fresh --seed` (WARNING: Deletes all data)

---

## Quick Reference - Copy/Paste Commands

```powershell
# Full setup in order:
cd c:\everpeakcamp_co
composer install
npm install
copy .env.example .env
php artisan key:generate
# EDIT .env with your database credentials here
php artisan migrate
php artisan db:seed
npm run build
php artisan serve
```

Then visit: **http://127.0.0.1:8000**

---

## Additional Notes

- Scout search uses "collection" driver (works offline, use Algolia/Meilisearch for production)
- Charts library (consoletvs/charts) installed but may need rendering fixes
- All required packages in `composer.json` and `package.json`
- Database uses PostgreSQL conventions (DATE_TRUNC) but can work with MySQL
