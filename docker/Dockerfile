FROM celaraze/laravel-docker:latest

RUN git clone --recursive https://github.com/celaraze/chemex.git /var/www/html/laravel
COPY .env.docker /var/www/html/laravel/.env
WORKDIR /var/www/html/laravel
RUN composer install -vvv

RUN chmod -R 755 /var/www/html/laravel && \
    chmod -R 777 /var/www/html/laravel/storage
