FROM ubuntu:22.04

LABEL maintainer="Taylor Otwell"

ARG WWWGROUP
ARG NODE_VERSION=16
ARG POSTGRES_VERSION=14

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=Asia/Seoul

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN sed -i 's/archive.ubuntu.com/mirror.kakao.com/g' /etc/apt/sources.list
RUN sed -i 's/security.ubuntu.com/mirror.kakao.com/g' /etc/apt/sources.list
RUN sed -i 's/kr.security.ubuntu.com/mirror.kakao.com/g' /etc/apt/sources.list

RUN apt-get update \
    && apt-get install -y gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3 libcap2-bin libpng-dev python2 vim \
    && mkdir -p ~/.gnupg \
    && chmod 600 ~/.gnupg \
    && echo "disable-ipv6" >> ~/.gnupg/dirmngr.conf \
    && echo "keyserver hkp://keyserver.ubuntu.com:80" >> ~/.gnupg/dirmngr.conf \
    && gpg --recv-key 0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c \
    && gpg --export 0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c > /usr/share/keyrings/ppa_ondrej_php.gpg \
    && echo "deb [signed-by=/usr/share/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu jammy main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && apt-get update \
    && apt-get install -y php8.1-cli php8.1-dev \
       php8.1-opcache php8.1-pdo php8.1-calendar php8.1-ctype php8.1-curl php8.1-dom php8.1-exif php8.1-ffi php8.1-fileinfo php8.1-ftp php8.1-gd php8.1-gettext php8.1-iconv php8.1-imagick php8.1-mysqli php8.1-phar php8.1-posix php8.1-shmop php8.1-simplexml php8.1-sockets php8.1-sqlite3 php8.1-sysvmsg php8.1-sysvsem php8.1-sysvshm php8.1-tokenizer php8.1-xmlreader php8.1-xmlwriter php8.1-xsl \
       php8.1-pgsql php8.1-sqlite3 php8.1-gd \
       php8.1-imap php8.1-mysql php8.1-mbstring \
       php8.1-xml php8.1-zip php8.1-bcmath php8.1-soap \
       php8.1-intl php8.1-readline \
       php8.1-ldap \
       php8.1-msgpack php8.1-igbinary php8.1-redis php8.1-swoole \
       php8.1-memcached php8.1-pcov php8.1-xdebug \
    && php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor | tee /usr/share/keyrings/yarn.gpg >/dev/null \
    && echo "deb [signed-by=/usr/share/keyrings/yarn.gpg] https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
    && curl -sS https://www.postgresql.org/media/keys/ACCC4CF8.asc | gpg --dearmor | tee /usr/share/keyrings/pgdg.gpg >/dev/null \
    && echo "deb [signed-by=/usr/share/keyrings/pgdg.gpg] http://apt.postgresql.org/pub/repos/apt jammy-pgdg main" > /etc/apt/sources.list.d/pgdg.list \
    && apt-get update \
    && apt-get install -y yarn \
    && apt-get install -y mysql-client \
    && apt-get install -y postgresql-client-$POSTGRES_VERSION \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN ln -s /usr/local/bin/composer.phar /usr/local/bin/composer
RUN setcap "cap_net_bind_service=+ep" /usr/bin/php8.1

RUN groupadd -g 1000 jaehyeok
RUN useradd -g 1000 -u 1000 jaehyeok

COPY . /var/www/html

RUN groupadd -g 999 sail
RUN useradd -r -u 999 -g sail sail

RUN composer install

RUN chown -R 999:sail /var/www/html/storage
RUN chown -R sail:www-data /var/www/html/bootstrap/cache
RUN chown -R 999:www-data /var/www/html/storage/app/public
RUN chown -R sail:www-data /var/log/supervisor
RUN chown -R sail:www-data .env
RUN chown -R sail:www-data .env.testing
RUN chown -R sail:www-data database
RUN chmod -R 775 /var/www/html/storage
RUN chmod -R 775 /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/log/supervisor
RUN chmod -R 775 .env
RUN chmod -R 775 .env.testing
RUN chmod -R 775 database
RUN usermod -aG sail www-data
RUN usermod -aG www-data sail
RUN usermod -aG jaehyeok sail
RUN usermod -aG sail jaehyeok

RUN rm -f public/storage
RUN ln -s /var/www/html/storage/app/public public/storage
RUN chown -h 1000:www-data /var/www/html/public/storage

COPY docker/start-container /usr/local/bin/start-container
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php.ini /etc/php/8.1/cli/conf.d/99-sail.ini

RUN chmod +x /usr/local/bin/start-container

RUN php artisan key:generate
RUN sed 's/DB_DATABASE=sports_record/DB_DATABASE=testing/g' .env > .env.testing2
RUN sed 's/APP_ENV=local/APP_ENV=testing/g' .env.testing2 > .env.testing

RUN php artisan migrate
RUN php artisan migrate --env=testing

USER sail

EXPOSE 8888 8000

ENTRYPOINT ["start-container"]
