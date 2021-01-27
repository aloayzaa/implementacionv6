FROM php:7.3-fpm

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    autoconf \
    pkg-config \
    libssl-dev \
    libzip-dev \
    libgmp-dev \
    locales \
    re2c \
    libmhash-dev \
    libmcrypt-dev \
    file \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl

RUN ln -s /usr/include/x86_64-linux-gnu/gmp.h /usr/local/include/
RUN docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install zip \
    && docker-php-ext-install gmp \
    && docker-php-ext-install gd   
    

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install gd

# Instalar locale y los idiomas que necesitemos
#RUN DEBIAN_FRONTEND=noninteractive apt-get install -y locales locales-all
#RUN sed -i 's/# es_PE.UTF-5 UTF-5/es_PE.UTF-5 UTF-5/' /etc/locale.gen \
#    && locale-gen

# Supervisor
#RUN add-apt-repository universe
RUN apt-get update -y
RUN apt-get install supervisor -y && touch /etc/supervisor/conf.d/laravel-worker.conf

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 devops
RUN useradd -u 1000 -ms /bin/bash -g devops devops

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=devops:devops . /var/www

# Change current user to devops
USER devops

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]