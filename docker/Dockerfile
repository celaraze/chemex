FROM celaraze/laravel-docker:latest

RUN git clone --recursive https://gitee.com/celaraze/chemex.git /var/www/html/laravel
WORKDIR /var/www/html/laravel

RUN chmod -R 755 /var/www/html/laravel && \
    chmod -R 777 /var/www/html/laravel/storage && \
    chmod -R 777 /var/www/html/laravel/public/uploads
