# Migration Commands for Remember Me & Trust Device

## 🚀 Run These Commands:

```bash
# Navigate to project directory
cd /Users/enginesec/Desktop/ielts-mock-platform

# Run the specific migration for trusted devices
php artisan migrate --path=database/migrations/2024_12_31_trusted_devices.php

# Or run all pending migrations
php artisan migrate

# If you get an error that table already exists, use:
php artisan migrate:fresh --seed
# WARNING: This will drop all tables and recreate them!

# To check migration status
php artisan migrate:status

# Clear all caches after migration
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optional: If sessions not working properly
php artisan session:table
php artisan migrate
```

## 🔧 Alternative Manual SQL (if migration fails):

```sql
CREATE TABLE `trusted_devices` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `device_token` varchar(255) NOT NULL UNIQUE,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text NOT NULL,
  `trusted_until` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trusted_devices_user_id_device_token_index` (`user_id`, `device_token`),
  KEY `trusted_devices_trusted_until_index` (`trusted_until`),
  CONSTRAINT `trusted_devices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## 📍 File Locations:

- **Migration File:** `/Users/enginesec/Desktop/ielts-mock-platform/database/migrations/2024_12_31_trusted_devices.php`
- **Login Controller:** `/Users/enginesec/Desktop/ielts-mock-platform/app/Http/Controllers/Auth/LoginController.php`
- **Middleware:** `/Users/enginesec/Desktop/ielts-mock-platform/app/Http/Middleware/CheckTrustedDevice.php`
- **Session Config:** `/Users/enginesec/Desktop/ielts-mock-platform/config/session.php`

## ✅ Testing After Migration:

1. Go to login page
2. Enter credentials
3. Check "Remember me" for 30-day login
4. Check "Trust this device for 60 days" for extended session
5. Login and verify in browser cookies:
   - Look for `laravel_session` cookie
   - Look for `remember_web_*` cookie
   - Look for `device_trust_token` cookie

## 🔍 Verify in Database:

```sql
-- Check if table created
SHOW TABLES LIKE 'trusted_devices';

-- Check trusted devices for a user
SELECT * FROM trusted_devices WHERE user_id = 1;

-- Check active trusted devices
SELECT * FROM trusted_devices WHERE trusted_until > NOW();

-- Clean expired devices (optional)
DELETE FROM trusted_devices WHERE trusted_until < NOW();
```