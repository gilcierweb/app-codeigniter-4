FROM php:8.3-fpm-alpine as AlpinePHP

LABEL maintainer="gilcierweb@gmail.com"

RUN echo "UTC" > /etc/timezone
RUN apk update && apk add tzdata \
     && cp -r -f /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime

RUN set -ex && apk update && apk upgrade 

RUN apk add bash
RUN sed -i 's/bin\/ash/bin\/bash/g' /etc/passwd

RUN wget https://getcomposer.org/composer-stable.phar -O /usr/local/bin/composer && chmod +x /usr/local/bin/composer
RUN apk add --no-cache composer
RUN apk add --update --no-cache libgd libpng-dev libjpeg-turbo-dev freetype-dev

RUN apk add jpeg-dev libpng-dev \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN apk add jpeg-dev libpng-dev \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN apk add --no-cache zip libzip-dev
RUN apk add icu-dev php-tokenizer
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-enable mysqli pdo_mysql
RUN docker-php-ext-configure intl && docker-php-ext-install intl
RUN docker-php-ext-install zip

RUN apk add php-session \
    php-tokenizer \
    php-xml \
    php-ctype \
    php-curl \
    php-dom \
    php-fileinfo \
    php-mbstring \
    php-openssl \
    php-pdo \
    php-pdo_mysql \
    php-session \
    php-tokenizer \
    php-xml \
    php-ctype \
    php-xmlwriter \
    php-simplexml

WORKDIR /app
COPY . /app

RUN composer install --no-dev --optimize-autoloader

# Install MySQL client tools (for interacting with the database)
RUN apk add --no-cache mysql-client

# Create a script to initialize the database and run the server
#COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
#RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 8080

# Start the CodeIgniter development server
CMD ["php", "spark", "serve", "--host", "0.0.0.0"]

# Run the entrypoint script
#ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]