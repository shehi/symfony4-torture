FROM php:7-fpm
LABEL maintainer="Shahriyar Imanov <shehi@imanov.me>"

WORKDIR /var/www

ENV DEBIAN_FRONTEND noninteractive
ENV TERM xterm

RUN apt-get update -y \
    && apt-get install -y apt-utils \
    && apt-get upgrade -y \
    && mkdir -p /usr/share/man/man1 \
    && apt-get install -y \
        git \
        mariadb-client \
        sudo \
        unzip \
        wget

# Create php.ini file
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN sed -i -e "s/upload_max_filesize =.*/upload_max_filesize = 100M/g" "$PHP_INI_DIR/php.ini"
RUN sed -i -e "s/post_max_size =.*/post_max_size = 100M/g" "$PHP_INI_DIR/php.ini"

RUN apt-get install -y libzip-dev && docker-php-ext-install zip
RUN apt-get install -y libicu-dev && docker-php-ext-install intl

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql

# Install PECL php-xdebug
RUN pecl install -of xdebug-3.1.5 \
    && rm -rf /tmp/pear \
    && echo "zend_extension=xdebug.so" | tee /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.idekey=PHPSTORM" | tee -a /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" | tee -a /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_port=54321" | tee -a /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.mode=off" | tee -a /usr/local/etc/php/conf.d/xdebug.ini

# Port for XDebug
EXPOSE 54321

# Timezone
RUN echo "date.timezone = UTC" | tee /usr/local/etc/php/conf.d/date.ini

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Clean-up
RUN apt-get clean \
    && apt-get remove -y gnupg \
    && apt-get autoremove -y \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Add a non-root user to prevent files being created with root permissions on host machine.
ARG PUID=1000
ARG PGID=1000
RUN groupadd -g $PGID loudly && useradd -u $PUID -g loudly -m loudly
RUN usermod -aG sudo loudly
RUN sed -i -e "s/%sudo.*/%sudo ALL=(ALL:ALL) NOPASSWD: ALL/g" /etc/sudoers

COPY ./profile /etc/profile
COPY ./profile /etc/bashrc

USER loudly

# Install Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash
