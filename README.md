# SMSHub - Virtual Phone Number SMS Receiver Platform

**Developed by Dexterity Wurayayi**

[![GitHub](https://img.shields.io/badge/GitHub-Repository-blue.svg)](https://github.com/DexterWura/SMSHUB-)
[![License](https://img.shields.io/badge/License-Proprietary-red.svg)](LICENSE)

SMSHub is a comprehensive Laravel-based platform that allows users to receive and view SMS messages sent to virtual phone numbers. The system supports multiple payment gateways, subscription-based access control, and a modern admin panel for managing numbers, plans, and users.

**Repository**: [https://github.com/DexterWura/SMSHUB-](https://github.com/DexterWura/SMSHUB-)

## 🌟 Features

- **Virtual Phone Numbers**: Receive SMS messages to virtual numbers with different access levels
- **Multiple Payment Gateways**: Integrated support for Stripe, PayPal, RazorPay, Paystack, CoinPayments, and Paynow
- **Subscription System**: Flexible plans with various validity periods (minutes, hours, days, weeks, months, years)
- **Wallet System**: User wallet with transaction history and auto-renewal support
- **Admin Panel**: Comprehensive backend for managing numbers, plans, orders, users, and settings
- **Multi-language Support**: Built-in support for multiple languages
- **Theme System**: Customizable themes for the frontend
- **SMS Reception API**: RESTful API endpoint for receiving SMS messages from external gateways

## 📋 Requirements

- PHP >= 8.0
- Composer
- MySQL/MariaDB
- Web Server (Apache/Nginx) or PHP Built-in Server
- Node.js & NPM (for asset compilation)

## 🚀 Localhost Setup

### Step 1: Clone the Repository

```bash
git clone https://github.com/DexterWura/SMSHUB-.git
cd SMSHUB-
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies (if needed)
npm install
```

### Step 3: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Database

Edit the `.env` file and set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smshub
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 5: Run Installation

1. Start your local server:
   ```bash
   php artisan serve
   ```

2. Open your browser and navigate to:
   ```
   http://localhost:8000/installer
   ```

3. Follow the installation wizard:
   - **Step 1**: Enter database credentials
   - **Step 2**: Enter your application name
   - **Step 3**: Create your admin account

4. The installer will automatically:
   - Run database migrations
   - Seed initial data
   - Create storage symlink
   - Mark the system as installed

### Step 6: Access the System

- **Frontend**: `http://localhost:8000`
- **Admin Panel**: `http://localhost:8000/admin`
- **Login**: Use the admin credentials you created during installation

## 🌐 Live Server Setup

### Step 1: Upload Files

Upload all files to your web server via FTP, SFTP, or Git deployment.

### Step 2: Set Permissions

```bash
# Set proper permissions
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### Step 3: Configure Web Server

#### Apache Configuration

Ensure your `.htaccess` file is in place and your virtual host points to the `public` directory:

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /path/to/SMSHUB-/public
    
    <Directory /path/to/SMSHUB-/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx Configuration

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/SMSHUB-/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Step 4: Environment Setup

1. Create `.env` file on the server:
   ```bash
   cp .env.example .env
   ```

2. Edit `.env` with production settings:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   
   DB_CONNECTION=mysql
   DB_HOST=your_db_host
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

3. Generate application key:
   ```bash
   php artisan key:generate
   ```

### Step 5: Install Dependencies

```bash
# Install PHP dependencies (production)
composer install --optimize-autoloader --no-dev

# Compile assets (if needed)
npm run production
```

### Step 6: Run Installation

1. Navigate to: `https://yourdomain.com/installer`
2. Follow the installation wizard (same as localhost)
3. Complete the setup process

### Step 7: Configure Cron Job

Set up a cron job to handle auto-renewals and transaction cleanup:

```bash
# Edit crontab
crontab -e

# Add this line (replace with your actual cron password from settings)
* * * * * curl -s https://yourdomain.com/api/cron/YOUR_CRON_PASSWORD > /dev/null 2>&1
```

Or using PHP CLI:

```bash
* * * * * cd /path/to/SMSHUB- && php artisan schedule:run >> /dev/null 2>&1
```

### Step 8: SSL Certificate (Recommended)

Install an SSL certificate for HTTPS. You can use Let's Encrypt:

```bash
sudo certbot --nginx -d yourdomain.com
```

## 🔧 System Architecture

### Core Components

1. **Numbers Management**: Virtual phone numbers with different access types:
   - Type 0: Open (public access)
   - Type 1: Registered users only
   - Type 2: Private (admin-shared)
   - Type 3: Shared buy (multiple users can purchase)
   - Type 4: Private buy (dedicated access per user)

2. **Payment System**: 
   - Wallet-based transactions
   - Multiple payment gateway integrations
   - Transaction history and logging

3. **Subscription Plans**:
   - Flexible pricing and validity periods
   - Auto-renewal support
   - Plan-to-number mapping

4. **SMS Reception**:
   - RESTful API endpoint: `/api/receive/{auth_key}`
   - Supports custom keyword mapping
   - Message storage and retrieval

### Key Routes

- `/` - Home page
- `/number/{number}` - View specific number and messages
- `/order` - Purchase plans (authenticated)
- `/wallet` - Manage wallet balance (authenticated)
- `/admin` - Admin dashboard
- `/api/receive/{auth_key}` - SMS reception endpoint
- `/api/cron/{password}` - Cron job endpoint

## 🔐 Configuration

### Payment Gateways

Configure payment methods in Admin Panel → Settings → Payment Methods:

1. **Stripe**: Enter your Stripe Secret Key
2. **PayPal**: Enter Client ID and Secret (format: `CLIENT_ID:SECRET`)
3. **RazorPay**: Enter Key ID and Secret (format: `KEY_ID:SECRET`)
4. **Paystack**: Enter Public Key and Secret (format: `PUBLIC_KEY:SECRET`)
5. **CoinPayments**: Enter Public Key and Private Key (format: `PUBLIC_KEY:PRIVATE_KEY`)
6. **Paynow**: Enter Integration ID and Key (format: `INTEGRATION_ID:INTEGRATION_KEY`)

### SMS Reception Setup

1. Get your auth key from Admin Panel → Settings → Configuration
2. Configure your SMS gateway to send POST requests to:
   ```
   https://yourdomain.com/api/receive/{auth_key}
   ```
3. Map keywords in Settings → Keywords:
   - `to`: Phone number receiving the message
   - `from`: Sender phone number
   - `msg`: Message content
   - `uuid`: Unique message identifier

### Environment Variables

Key environment variables in `.env`:

```env
APP_NAME="SMSHub"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smshub
DB_USERNAME=root
DB_PASSWORD=

# Mail (for notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"
```

## 📱 Usage

### For End Users

1. **Browse Numbers**: View available virtual phone numbers
2. **Purchase Access**: Buy plans to access premium numbers
3. **View Messages**: Access SMS messages sent to your numbers
4. **Manage Wallet**: Add funds and view transaction history

### For Administrators

1. **Manage Numbers**: Add, edit, and configure virtual numbers
2. **Create Plans**: Set up subscription plans with pricing
3. **Map Plans to Numbers**: Assign plans to specific numbers
4. **View Orders**: Monitor all user orders and transactions
5. **User Management**: Manage user accounts and permissions
6. **System Settings**: Configure payment methods, themes, and more

## 🛠️ Development

### Running Tests

```bash
php artisan test
```

### Clearing Cache

```bash
# Clear application cache
php artisan cache:clear

# Clear config cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Clear view cache
php artisan view:clear
```

### Database Migrations

```bash
# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Reset database
php artisan migrate:fresh --seed
```

## 📦 Technologies Used

- **Laravel 9** - PHP Framework
- **Laravel Jetstream** - Authentication & User Management
- **Livewire** - Interactive UI Components
- **Laravel Sanctum** - API Authentication
- **MySQL** - Database
- **Tailwind CSS** - Styling
- **Alpine.js** - JavaScript Framework

## 📄 License

This project is proprietary software developed by Dexterity Wurayayi. All rights reserved.

## 👤 Author

**Dexterity Wurayayi**

## 🤝 Support

For support and inquiries, please contact the developer.

## 📂 Repository

**GitHub**: [https://github.com/DexterWura/SMSHUB-](https://github.com/DexterWura/SMSHUB-)

Clone the repository:
```bash
git clone https://github.com/DexterWura/SMSHUB-.git
```

## 🔄 Version

Current Version: 2.5

---

**Note**: Make sure to keep your `.env` file secure and never commit it to version control. The system includes an installer that handles initial setup, but manual configuration is also possible through the `.env` file and admin panel.

