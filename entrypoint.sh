#!/bin/sh

# Setup a cron schedule
echo "0 8 * * * /var/www/html/js/scripts/Groups/autoCheckCall.php >> /var/log/cron.log 2>&1
# This extra line makes it a valid cron" > scheduler.txt

crontab scheduler.txt
cron -f
