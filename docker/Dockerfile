
FROM wordpress:6.9.0-php8.4-fpm

WORKDIR /usr/src/wordpress


# Remove native themes and plugins
RUN rm -rf /usr/src/wordpress/wp-content

# Configure Nginx
RUN apt-get update && apt-get upgrade -y && apt-get install -y nginx && apt-get clean
COPY --chown=www-data:www-data ./config/nginx.conf /etc/nginx/nginx.conf

# Configure wp-cli and wp-cron
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
RUN chmod +x wp-cli.phar && mv wp-cli.phar /usr/local/bin/wp

# Configure WP
COPY --chown=www-data:www-data ./config/wp-config-container.php /usr/src/wordpress/wp-config.php
COPY --chown=www-data:www-data ./config/wordfence-waf.php /usr/src/wordpress/wordfence-waf.php
COPY --chown=www-data:www-data ./config/robots.txt /usr/src/wordpress/robots.txt
COPY --chown=www-data:www-data ./config/llms.txt /usr/src/wordpress/llms.txt

# Copy custom themes and plugins
COPY --chown=www-data:www-data ./wp-content /usr/src/wordpress/wp-content

# Expose http port
EXPOSE 80

# Start cron, PHP-FPM and Nginx
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
