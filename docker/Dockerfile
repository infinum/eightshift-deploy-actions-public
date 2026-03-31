FROM wordpress:6.9.4-php8.4-fpm

WORKDIR /usr/src/wordpress

# Remove native themes and plugins
RUN rm -rf /usr/src/wordpress/wp-content

# Configure Nginx
RUN apt-get update && apt-get upgrade -y && apt-get install -y --no-install-recommends nginx cron \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
COPY --chown=www-data:www-data ./config/nginx.conf /etc/nginx/nginx.conf

# Configure wp-cli and wp-cron
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar.sha512 \
    && sha512sum -c wp-cli.phar.sha512 \
    && chmod +x wp-cli.phar && mv wp-cli.phar /usr/local/bin/wp \
    && rm wp-cli.phar.sha512
COPY ./config/wp-cron /etc/cron.d/wp-cron
RUN crontab /etc/cron.d/wp-cron

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
CMD ["sh", "-c", "service cron start && php-fpm -D && nginx -g 'daemon off;'"]
