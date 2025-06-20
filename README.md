# Sales ERP System

A comprehensive Laravel-based Sales Enterprise Resource Planning (ERP) system with role-based access control, product management, and sales order processing.

##  Features

### Core Functionality
- **Dashboard** - Real-time sales analytics and low stock alerts
- **Product Management** - Complete CRUD operations for inventory
- **Sales Order Processing** - Create, view, and manage sales orders
- **PDF Generation** - Export sales orders as PDF documents
- **Role-Based Access Control** - Admin and Salesperson roles with different permissions

### API Features
- **RESTful API** with Laravel Sanctum authentication
- **Role-based API access** with proper authorization

### Technologies Used
- **Laravel 12** - PHP framework
- **Laravel Sanctum** - API authentication
- **Bootstrap 5** - Frontend framework
- **MySQL** - Database
- **DomPDF** - PDF generation
- **jQuery** - JavaScript framework

## Requirements

- PHP 8.2 or higher
- Composer
- MySQL database

##  Installation

### 1. Clone the Repository
```bash
git clone https://github.com/riyapatel2206/sales-erp.git
cd sales-erp
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install
```

### 3. Environment Setup
```bash
# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration
Edit your `.env` file with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

```
### 5. Database Migration & Seeding
```bash
# Run migrations
php artisan migrate

# Seed database with sample data
php artisan db:seed

# Clear cache
php artisan config:clear
php artisan route:clear
php artisan cache:clear

```

### 6. Start Development Server
```bash
# Start Laravel server
php artisan serve

# The application will be available at http://127.0.0.1:8000
```

## 👥 User Roles & Permissions

### Admin Role
- Full access to all features
- Manage products (Create, Read, Update)
- View all sales orders
- Access dashboard with complete analytics

### Salesperson Role
- Create and manage own sales orders
- Cannot manage other users' orders

##  Default Login Credentials

After running the seeder, you can use these credentials:

### Admin User
- **Email:** admin@saleserp.com
- **Password:** password

### Salesperson User
- **Email:** sales@saleserp.com
- **Password:** password

### Features
- **Real-time Product Selection** with stock availability
- **Automatic Price Calculation** based on product prices
- **Stock Validation** prevents ordering more than available
- **Order Summary** with itemized totals
- **PDF Export** for order details

## API Documentation

### Authentication

#### Login
```bash
POST /api/login
Content-Type: application/json

{
    "email": "admin@saleserp.com",
    "password": "password"
}
```

### API Endpoints

#### Products
```bash
# Get all products
GET /api/products
Authorization: Bearer {token}

```

#### Sales Orders
```bash
# Create sales order
POST /api/sales
Authorization: Bearer {token}
Content-Type: application/json

{
    "products": [
        {
            "product_id": 1,
            "quantity": 2
        }
    ]
}

# Get specific sales order
GET /api/sales/{id}
Authorization: Bearer {token}

```

#### Authentication Management
```bash
# Logout current session
POST /api/logout
Authorization: Bearer {token}

```

### Manual Testing Routes
```bash

# API Routes
http://127.0.0.1:8000/api/login
http://127.0.0.1:8000/api/logout
http://127.0.0.1:8000/api/products
http://127.0.0.1:8000/api/sales
http://127.0.0.1:8000/api/sales/2
```

### Version 1.0.0
- Initial release with core ERP functionality
- Role-based access control implementation
- RESTful API with Sanctum authentication
- Product and sales order management
- PDF generation for orders
- dashboard with analytics

---

**Built with using Laravel 12**