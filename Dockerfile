FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    zip \
    unzip \
    git

# Install PHP extensions
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install dependencies
RUN composer install --no-interaction --no-progress --no-suggest

# Start PHP Built-in Server
CMD ["php", "-S", "0.0.0.0:80", "-t", "public"] 