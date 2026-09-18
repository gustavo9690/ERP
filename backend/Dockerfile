FROM php:8.2-apache

# Instalar extensiones de PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-enable mysqli pdo_mysql

# ✅ Activar módulos que faltaban (rewrite + headers)
RUN a2enmod rewrite headers

# Configurar Apache para permitir RewriteEngine
RUN echo "<Directory /var/www/html>" >> /etc/apache2/apache2.conf && \
    echo "    AllowOverride All" >> /etc/apache2/apache2.conf && \
    echo "    Require all granted" >> /etc/apache2/apache2.conf && \
    echo "</Directory>" >> /etc/apache2/apache2.conf