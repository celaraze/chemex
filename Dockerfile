FROM celaraze/laravel-docker:latest

RUN git clone --recursive https://gitee.com/celaraze/chemex.git /var/www/html/laravel
COPY .env.docker /var/www/html/laravel/.env
WORKDIR /var/www/html/laravel
RUN composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
RUN composer install -vvv

RUN chown -R www-data:www-data /var/www/html/laravel && \
    chmod -R 755 /var/www/html/laravel && \
    chmod -R 777 /var/www/html/laravel/storage

COPY docker-entrypoint.sh /docker-entrypoint.sh
RUN chmod +x /docker-entrypoint.sh

entrypoint ["/docker-entrypoint.sh"]
