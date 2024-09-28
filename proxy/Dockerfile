FROM httpd:alpine

##################################
# ARG to EV
ARG APACHE_RUN_UID
ARG APACHE_RUN_GID
ARG APACHE_RUN_USER
ARG APACHE_RUN_GROUP

ENV APACHE_RUN_UID=$APACHE_RUN_UID
ENV APACHE_RUN_GID=$APACHE_RUN_GID
ENV APACHE_RUN_USER=$APACHE_RUN_USER
ENV APACHE_RUN_GROUP=$APACHE_RUN_GROUP

##################################
# essentials
RUN apk upgrade && apk update && apk add --no-cache vim shadow fping

##################################
# Match UID and GID
RUN groupadd -g ${APACHE_RUN_GID} ${APACHE_RUN_GROUP}
RUN useradd  -g ${APACHE_RUN_GROUP} -u ${APACHE_RUN_UID} -s /bin/false -M ${APACHE_RUN_USER}

##################################
# setup
# RUN apk add apache2-proxy

RUN ln -s /var/www/ssl /etc/letsencrypt
RUN apk add --no-cache certbot-apache
