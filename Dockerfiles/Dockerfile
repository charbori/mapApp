FROM php:8.1-fpm

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=Asia/Seoul

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update            
RUN apt-get install -y curl

ARG NODE_VERSION=18

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

RUN composer global require laravel/installer
RUN ["/bin/bash", "-c", "echo PATH=$PATH:~/.composer/vendor/bin/ >> ~/.bashrc"]
RUN ["/bin/bash", "-c", "source ~/.bashrc"]

COPY package.json /var/www/html/package.json
COPY vite.config.js /var/www/html/vite.config.js
COPY resources /var/www/html/resources
COPY public /var/www/html/public
COPY Dockerfiles/start-container /usr/local/bin/start-container
COPY Dockerfiles/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN npm install
RUN npm run build

EXPOSE 8080 8888 9000

ENTRYPOINT ["start-container"]