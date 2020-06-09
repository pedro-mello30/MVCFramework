#!/bin/bash

INSTALL_BASE() {
     echo -e "\nInstalling libraries\n"
     apt-get update
     apt-get upgrade

     apt-get -y install apt-transport-https
     apt-get -y install ca-certificates
     apt-get -y install git
     apt-get -y install curl
     apt-get -y install apt-utils
     apt-get install -y tzdata
}

INSTALL_APACHE() {
     echo -e "\nInstalling APACHE\n"
     apt install -y apache2
}

INSTALL_PHP() {
    echo -e "\nInstalling PHP\n"

    apt-get -y install \
              apache2 \
              php7.0 \
              php7.0-cli \
              libapache2-mod-php7.0 \
              php7.0-gd \
              php7.0-curl \
              php7.0-json \
              php7.0-mbstring \
              php7.0-mysql \
              php7.0-xml \
              php7.0-xsl \
              php7.0-zip

    a2enmod php7.0
    a2enmod rewrite
    systemctl restart apache2
}

INSTALL_PYTHON() {
     echo -e "\nInstalling PYTHON\n"
     apt -y install python3 python3-pip
}

INSTALL_RUBY() {
     echo -e "\nInstalling RUBY\n"
     apt install -y ruby-full
}

INSTALL_NODEJS() {
     echo -e "\nInstalling NODEJS\n"
     curl -sL https://deb.nodesource.com/setup_13.x | -E bash -
     apt-get install -y nodejs
}

INSTALL_GRUNTJS() {
     echo -e "\nInstalling GRUNTJS\n"
     apt-get install -y npm
     npm install -g grunt
}

INSTALL_BASE
INSTALL_APACHE
INSTALL_PHP
INSTALL_PYTHON
INSTALL_RUBY
INSTALL_NODEJS
INSTALL_GRUNTJS