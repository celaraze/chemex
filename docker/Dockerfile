FROM celaraze/laravel-docker:latest

RUN git clone --recursive https://github.com/celaraze/chemex.git /var/www/html/laravel
COPY .env.docker /var/www/html/laravel/.env
WORKDIR /var/www/html/laravel
RUN composer install -vvv
RUN php artisan chemex:update

RUN chmod -R 755 /var/www/html/laravel && \
    chmod -R 777 /var/www/html/laravel/storage

COPY start.sh.sh /start.sh
RUN chmod +x /start.sh

entrypoint ["/docker-entrypoint.sh"]
