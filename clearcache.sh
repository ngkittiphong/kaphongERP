#!/bin/bash  
# Navigate to the Laravel project directory 
cd /mnt/c/laragon/www/kaphongERP  

# Clear application cache 
php artisan cache:clear  

# Clear route cache 
php artisan route:clear  

# Clear configuration cache 
php artisan config:clear  

# Clear compiled views cache 
php artisan view:clear
