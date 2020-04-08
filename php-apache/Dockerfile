FROM php:7.2-apache-stretch
LABEL maintainer "Pedro Mello <pedro_mello@icloud.com>"

RUN docker-php-ext-install mysqli



#### PDO_MYSQL ####
ARG PHP_APACHE_INSTALL_PDO_MYSQL=false
RUN if [ ${PHP_APACHE_INSTALL_PDO_MYSQL} = true ]; then \
	docker-php-ext-install pdo pdo_mysql \
;fi

RUN apt-get update && \
    apt-get install -y \
        sendmail libpng-dev libjpeg-dev pkg-config patch
RUN apt-get update -y && apt-get upgrade -y libfreetype6-dev

#ADD https://git.archlinux.org/svntogit/packages.git/plain/trunk/freetype.patch?h=packages/php /tmp/freetype.patch
# RUN docker-php-source extract; \
#   cd /usr/src/php; \
#   patch -p1 -i /tmp/freetype.patch; \
#   rm /tmp/freetype.patch

# RUN docker-php-ext-configure gd --with-freetype-dir --with-jpeg-dir=/usr/include/
# RUN docker-php-ext-install gd


#RUN apt-get install php7.0-cli -y

RUN apt-get update -y && apt-get upgrade -y


RUN a2enmod rewrite

