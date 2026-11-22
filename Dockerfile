# PHP 8.3 + Apache, listening on port 10000 (Render's default web service port)
FROM php:8.3-apache

# --- Apache: port 10000, vhost, hardening ---------------------------------
RUN a2enmod headers \
 && sed -i 's/^Listen 80$/Listen 10000/' /etc/apache2/ports.conf \
 && sed -i 's/^ServerTokens .*/ServerTokens Prod/; s/^ServerSignature .*/ServerSignature Off/' /etc/apache2/conf-available/security.conf \
 && echo 'ServerName localhost' > /etc/apache2/conf-available/servername.conf \
 && a2enconf servername

COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf

# --- PHP: settings, session hardening, writable session directory ---------
COPY docker/php.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/session-init.php /usr/local/etc/php/session-init.php
RUN mkdir -p /var/lib/php/sessions \
 && chown www-data:www-data /var/lib/php/sessions \
 && chmod 700 /var/lib/php/sessions

# --- Application ----------------------------------------------------------
WORKDIR /var/www/html
COPY --chown=www-data:www-data . /var/www/html/
# Deployment files are not part of the website
RUN rm -rf /var/www/html/docker /var/www/html/Dockerfile /var/www/html/render.yaml

EXPOSE 10000
# php:8.3-apache already runs apache2-foreground as its default command
