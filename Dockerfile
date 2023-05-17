FROM celaraze/php-web:latest

RUN git clone --recursive https://gitee.com/celaraze/chemex.git /var/www/chemex/
COPY .env.docker /var/www/chemex/.env
WORKDIR /var/www/chemex/
RUN composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
RUN composer update -vvv

RUN chown -R www-data:www-data /var/www/chemex && \
    chmod -R 755 /var/www/chemex && \
    chmod -R 777 /var/www/chemex/storage
RUN rmdir /var/www/html && \
    ln -s /var/www/chemex/public /var/www/html

COPY docker-entrypoint.sh /docker-entrypoint.sh
RUN chmod +x /docker-entrypoint.sh

entrypoint ["/docker-entrypoint.sh"]
