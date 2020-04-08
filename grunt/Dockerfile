FROM ubuntu:latest
LABEL maintainer "Pedro Mello <pedro_mello@icloud.com>"


RUN apt-get update \
    && apt-get install -y \
    ruby \
    ruby-dev \
    rubygems \
    curl \
    gnupg \
    && curl -sL https://deb.nodesource.com/setup_6.x | bash - \
    && apt-get install -y nodejs \
    && apt-get install -y npm \
    && npm install -g grunt-cli \
    && gem install compass

