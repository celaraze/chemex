#!/bin/bash
set -e

# 设置环境变量
source /var/www/chemex/.env

# 等待数据库服务启动
while ! mysqladmin ping -h"$DB_HOST" -u"$DB_USERNAME" -p"$DB_PASSWORD" -P"${DB_PORT:-3306}" --silent; do
    echo "数据库服务还未响应，继续等待"
    sleep 3
done

# 初始化应用程序
# [ -z "${APP_KEY}" ]
if [ "$INSTALL" = "true" ]; then
    php artisan chemex:install
fi
php artisan chemex:update
# 启动应用程序
apache2-foreground
