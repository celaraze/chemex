FROM php:8.1.3-apache
COPY sources.list /etc/apt/sources.list
RUN apt-get update && \
    apt-get install -y git zip unzip libldap-2.4-2=2.4.47+dfsg-3+deb10u6 zlib1g=1:1.2.11.dfsg-1 libzip-dev libldap-dev mariadb-client --allow-downgrades && \
    apt-get clean
RUN docker-php-ext-install -j$(nproc) zip ldap bcmath mysqli pdo_mysql sockets
RUN a2enmod rewrite
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
RUN git clone https://gitee.com/celaraze/chemex.git /var/www/chemex/
COPY .env /var/www/chemex/.env

WORKDIR /var/www/chemex/
RUN composer update
RUN chown -R www-data:www-data /var/www/chemex && \
    chmod -R 755 /var/www/chemex && \
    chmod -R 777 /var/www/chemex/storage
RUN rmdir /var/www/html && \
    ln -s /var/www/chemex/public /var/www/html

COPY docker-entrypoint.sh /docker-entrypoint.sh
RUN chmod +x /docker-entrypoint.sh

entrypoint ["/docker-entrypoint.sh"]
