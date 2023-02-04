#!/bin/bash

echo "/var/app/current/storage/logs/*.log" > /opt/elasticbeanstalk/tasks/taillogs.d/laravel-logs.conf
echo "/root/.npm/_logs/*.log" > /opt/elasticbeanstalk/tasks/taillogs.d/npm-logs.conf
echo "/var/app/current/storage/logs/*.log" > /opt/elasticbeanstalk/tasks/bundlelogs.d/laravel-logs.conf
echo "/root/.npm/_logs/*.log" > /opt/elasticbeanstalk/tasks/bundlelogs.d/npm-logs.conf

chown root:root /opt/elasticbeanstalk/tasks/taillogs.d/laravel-logs.conf
chown root:root /opt/elasticbeanstalk/tasks/taillogs.d/npm-logs.conf
chown root:root /opt/elasticbeanstalk/tasks/bundlelogs.d/laravel-logs.conf
chown root:root /opt/elasticbeanstalk/tasks/bundlelogs.d/npm-logs.conf

chmod 755 /opt/elasticbeanstalk/tasks/taillogs.d/laravel-logs.conf
chmod 755 /opt/elasticbeanstalk/tasks/taillogs.d/npm-logs.conf
chmod 755 /opt/elasticbeanstalk/tasks/bundlelogs.d/laravel-logs.conf
chmod 755 /opt/elasticbeanstalk/tasks/bundlelogs.d/npm-logs.conf
