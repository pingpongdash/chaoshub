FROM php:8.2-fpm-alpine

##################################
# ARG to EV
ARG APP_RUN_UID
ARG APP_RUN_GID
ARG APP_RUN_USER
ARG APP_RUN_GROUP
ENV APP_RUN_UID=$APP_RUN_UID
ENV APP_RUN_GID=$APP_RUN_GID
ENV APP_RUN_USER=$APP_RUN_USER
ENV APP_RUN_GROUP=$APP_RUN_GROUP

##################################
# essentials
RUN apk upgrade && apk update && apk add --no-cache vim shadow fping

##################################
# Match UID and GID
# RUN usermod  -u ${APP_RUN_UID} www-data
# RUN groupmod -g ${APP_RUN_GID} www-data
RUN groupadd -g ${APP_RUN_GID} ${APP_RUN_GROUP}
RUN useradd  -g ${APP_RUN_GROUP} -u ${APP_RUN_UID} -s /bin/false -M ${APP_RUN_USER}


##################################
# configurations

##################################
# setup
RUN apk add unzip libxml2     \
    php-fileinfo php-curl php-gd php-mbstring php-zip php-session php-openssl \
    php-dom php-xml php-simplexml php-ctype php-tokenizer \
    php-sqlite3 php-pdo_sqlite sqlite-libs sqlite php-pdo \
    php-pecl-yaml　
