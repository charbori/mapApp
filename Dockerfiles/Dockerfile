FROM php:8.1-fpm

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=Asia/Seoul

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update            
RUN apt-get install -y curl

ARG NODE_VERSION=18

RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer 
RUN curl -sS https://getcomposer.org/installer | php  
RUN mv composer.phar /usr/bin/composer

RUN apt-get install -y openssh-server supervisor
RUN apt-get install -y zlib1g-dev && apt-get install -y libzip-dev \
    && curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash -
RUN apt-get update
RUN apt-get install -y nodejs 
RUN npm install -g npm

RUN echo '' | pecl install -o -f redis \
    && docker-php-ext-enable redis.so \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && pecl clear-cache;
    
RUN docker-php-ext-install zip

COPY composer.json /var/www/html/composer.json
RUN composer global require laravel/installer
RUN ["/bin/bash", "-c", "echo PATH=$PATH:~/.composer/vendor/bin/ >> ~/.bashrc"]
RUN ["/bin/bash", "-c", "source ~/.bashrc"]

COPY package.json /var/www/html/package.json
COPY vite.config.js /var/www/html/vite.config.js
COPY resources /var/www/html/resources
COPY app /var/www/html/app
COPY bootstrap /var/www/html/bootstrap
COPY config /var/www/html/config
COPY database /var/www/html/database
COPY artisan /var/www/html/artisan
COPY routes /var/www/html/routes
COPY storage /var/www/html/storage
COPY tests /var/www/html/tests
COPY public /var/www/html/public
COPY Dockerfiles/start-container /usr/local/bin/start-container
COPY Dockerfiles/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN composer update

RUN npm install
RUN npm run build

EXPOSE 8080 8888 9000

ENTRYPOINT ["start-container"]
