#!/bin/bash

touch /etc/cron.d/laravel_schedule_run

echo "* * * * * webapp /usr/bin/php /var/app/current/artisan schedule:run >> /dev/null 2>&1
# empty line" | tee /etc/cron.d/laravel_schedule_run
