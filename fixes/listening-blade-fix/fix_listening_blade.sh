#!/bin/bash

# Fix for listening.blade.php syntax error

echo "Creating backup of current listening.blade.php..."
cp /Users/enginesec/Desktop/ielts-mock-platform/resources/views/admin/questions/create/listening.blade.php \
   /Users/enginesec/Desktop/ielts-mock-platform/backup/listening.blade.php.corrupted.$(date +%Y%m%d_%H%M%S)

echo "Restoring listening.blade.php from template..."
cp /Users/enginesec/Desktop/ielts-mock-platform/resources/views/admin/questions/create/reading.blade.php \
   /Users/enginesec/Desktop/ielts-mock-platform/resources/views/admin/questions/create/listening.blade.php

echo "Modifying for listening context..."
sed -i '' 's/Reading/Listening/g' /Users/enginesec/Desktop/ielts-mock-platform/resources/views/admin/questions/create/listening.blade.php
sed -i '' 's/reading/listening/g' /Users/enginesec/Desktop/ielts-mock-platform/resources/views/admin/questions/create/listening.blade.php

echo "Clearing caches..."
cd /Users/enginesec/Desktop/ielts-mock-platform
php artisan cache:clear
php artisan config:clear
php artisan view:clear

echo "Fix applied successfully!"
echo "The listening.blade.php file has been restored and fixed."
