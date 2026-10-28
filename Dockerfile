FROM php:8.1-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid
ARG DEBIAN_FRONTEND=noninteractive
ENV COMPOSER_ALLOW_SUPERUSER = 1
# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    zip \
    unzip \
    build-essential  \
    xorg  \
    libssl-dev  \
    libxrender-dev  \
    wget  \
    gdebi \
    supervisor \
    cron \
    nano \
    webp \
    certbot
    # Clear cache

RUN apt-get clean && rm -rf /var/lib/apt/lists/*


# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd opcache
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp


# Make supervisor log directory
RUN mkdir -p /var/log/supervisor
RUN service cron start

# Copy local supervisord.conf to the conf.d directory
COPY docker-compose/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Start supervisord
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]


# Adding crontab to the appropiate location
ADD /docker-compose/cronjob/crontab /etc/cron.d/crontab

# Giving executable permission to crontab file
RUN chmod 0644 /etc/cron.d/crontab

# Running crontab
RUN crontab /etc/cron.d/crontab

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user


RUN chmod 777 /dev
RUN chmod 777 /run
# Set working directory
WORKDIR /var/www

USER root

COPY ./entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

EXPOSE 9000
