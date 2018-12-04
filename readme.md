### add cron entry to crontab
- "* * * * * cd /var/www/html/content-port && php artisan schedule:run >> /dev/null 2>&1"
### create directory and run command
1. storage/app/public`/attachments`
2. `php artisan storage:link`