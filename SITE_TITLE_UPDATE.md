# Commands to update the site_name to site_title

Run these commands in order:

1. **Run the migration to rename the column:**
```bash
php artisan migrate --path=database/migrations/2025_07_28_rename_site_name_to_site_title.php
```

2. **Clear all caches:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan optimize:clear
```

3. **If you get an error about the column not existing, you may need to manually update the database:**
```sql
ALTER TABLE website_settings CHANGE COLUMN site_name site_title VARCHAR(255);
```

## Changes Made:

1. **Database**: Changed `site_name` column to `site_title` in website_settings table
2. **Model**: Updated WebsiteSetting model to use `site_title` field with backward compatibility
3. **Controller**: Updated validation to use `site_title` instead of `site_name`
4. **View**: Changed label from "Site Name" to "Site Title" with helper text
5. **Admin Layout**: Updated to show only logo (when available) without site name next to it

The site title will now be used only for:
- Browser tab titles
- SEO purposes
- When logo is not available (shows with icon)

This prevents the duplication of logo and site name appearing together.
