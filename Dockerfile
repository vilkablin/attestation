# Устанавливаем базовый образ PHP 8.4 с Apache
FROM php:8.4-apache

# Устанавливаем необходимые зависимости
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    libicu-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Устанавливаем зависимости
RUN docker-php-ext-install bcmath intl

# Указываем рабочую директорию для проекта
WORKDIR /var/www/html

# Настраиваем DocumentRoot для папки public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Копируем файлы проекта
COPY . /var/www/html

# Настраиваем права доступа
RUN chown -R www-data:www-data /var/www/html && chmod -R 777 /var/www/html

# Включаем модуль Apache для переписывания URL
RUN a2enmod rewrite

RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

# Указываем порт для Apache
EXPOSE 80