# Portfolio Management System

ระบบจัดการ Portfolio สำหรับนักศึกษาและอาจารย์

## 🚀 Features

### สำหรับนักศึกษา
- 📝 สร้างและจัดการ Portfolio ส่วนตัว
- 📤 อัปโหลดเอกสารและรูปภาพ
- 🔍 ค้นหาและดู Portfolio ของเพื่อน
- 📊 ติดตามสถานะการอนุมัติ
- 👤 จัดการโปรไฟล์ส่วนตัว

### สำหรับอาจารย์/ผู้ดูแลระบบ
- ✅ อนุมัติ/ไม่อนุมัติ Portfolio
- 📊 ดูสถิติและรายงาน
- 👥 จัดการผู้ใช้งาน
- 🔧 จัดการระบบ
- 📁 จัดการเอกสาร

### ฟีเจอร์ทั่วไป
- 🌐 ระบบค้นหาขั้นสูง
- 📱 Responsive Design
- 🔐 ระบบ Authentication
- 📧 การแจ้งเตือน
- 🎨 UI/UX ที่ทันสมัย

## 🛠️ Technology Stack

- **Backend**: Laravel 12.x
- **Frontend**: Blade Templates + Tailwind CSS
- **Database**: SQLite (Development) / MySQL (Production)
- **Authentication**: Laravel Breeze
- **File Upload**: Laravel Storage
- **Testing**: PHPUnit
- **Build Tool**: Vite

## 📋 Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18
- npm or yarn

## 🚀 Installation

### 1. Clone Repository
```bash
git clone <repository-url>
cd project-portfolio
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Setup
```bash
# Copy environment file
cp env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup
```bash
# Run migrations
php artisan migrate

# Seed database with sample data
php artisan db:seed
```

### 5. Storage Setup
```bash
# Create storage link
php artisan storage:link

# Set permissions
chmod -R 775 storage bootstrap/cache
```

### 6. Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Start Development Server
```bash
php artisan serve
```

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter PersonalInfoTest

# Run with coverage
php artisan test --coverage
```

## 📁 Project Structure

```
project-portfolio/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/           # API Controllers
│   │   │   └── ...            # Web Controllers
│   │   └── Middleware/
│   ├── Models/
│   └── Services/
├── database/
│   ├── factories/             # Model Factories
│   ├── migrations/            # Database Migrations
│   └── seeders/               # Database Seeders
├── resources/
│   ├── views/                 # Blade Templates
│   ├── css/                   # Tailwind CSS
│   └── js/                    # JavaScript
├── routes/
│   ├── web.php               # Web Routes
│   └── api.php               # API Routes
├── tests/
│   ├── Feature/              # Feature Tests
│   └── Unit/                 # Unit Tests
└── ...
```

## 🔧 Configuration

### Environment Variables
```env
APP_NAME="Portfolio Management System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# File Upload Settings
MAX_FILE_SIZE=10240
ALLOWED_FILE_TYPES=jpg,jpeg,png,gif,pdf,doc,docx
UPLOAD_PATH=uploads

# Security Settings
RATE_LIMIT_REQUESTS=60
RATE_LIMIT_MINUTES=1
```

## 📚 API Documentation

### Authentication
```bash
POST /api/v1/login
POST /api/v1/register
POST /api/v1/logout
```

### Portfolios
```bash
GET    /api/v1/portfolios
GET    /api/v1/portfolios/{id}
POST   /api/v1/portfolios
PUT    /api/v1/portfolios/{id}
DELETE /api/v1/portfolios/{id}
```

### Documents
```bash
GET    /api/v1/documents
POST   /api/v1/documents
GET    /api/v1/documents/{id}
PUT    /api/v1/documents/{id}
DELETE /api/v1/documents/{id}
```

## 🚀 Deployment

### Using Deployment Script
```bash
# Production
./deploy.sh production

# Staging
./deploy.sh staging
```

### Manual Deployment
```bash
# Install production dependencies
composer install --no-dev --optimize-autoloader

# Build assets
npm run build

# Run migrations
php artisan migrate --force

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

If you have any questions or need help, please:

1. Check the [Documentation](docs/)
2. Search [Issues](issues)
3. Create a new [Issue](issues/new)

## 🙏 Acknowledgments

- Laravel Framework
- Tailwind CSS
- Bootstrap
- All contributors and users

---

**Made with ❤️ for better education**
