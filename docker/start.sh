#!/bin/bash
set -e

# 设置环境变量
source /var/www/html/laravel/.env

# 初始化应用程序
# [ -z "${APP_KEY}" ]
if [ "$INSTALL" = "true" ]; then
    php artisan chemex:install
fi
php artisan chemex:update
