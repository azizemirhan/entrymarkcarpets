# WordPress için PHP 8.2 + Apache
FROM php:8.2-apache

# Apache mod_rewrite (kalıcı linkler için)
RUN a2enmod rewrite

# WordPress için gerekli PHP eklentileri
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libicu-dev \
    zip \
    unzip \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    mysqli \
    pdo \
    pdo_mysql \
    zip \
    intl \
    mbstring \
    exif \
    opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Güvenlik: DocumentRoot
ENV APACHE_DOCUMENT_ROOT /var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# AllowOverride All (WordPress .htaccess için)
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Proje dosyalarını kopyala
COPY . /var/www/html/

# wp-config.php yoksa entrypoint oluşturacak
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Yazılabilir dizinler
RUN chown -R www-data:www-data /var/www/html/wp-content

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
