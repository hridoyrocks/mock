# Fix Authentication Log Table Error

Run this command to create the missing authentication_log table:

```bash
php artisan migrate --path=database/migrations/2025_07_28_create_authentication_log_table.php
```

Or if you want to run all pending migrations:

```bash
php artisan migrate
```

This will create the `authentication_log` table that is required by the Laravel Authentication Log package.

The error occurred because the package expects this table to exist, but it wasn't created yet.

After running the migration, the user management page should work properly without any errors.
