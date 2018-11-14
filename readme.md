## add cron entry to crontab
- "* * * * * cd /var/www/html/content-port && php artisan schedule:run >> /dev/null 2>&1"