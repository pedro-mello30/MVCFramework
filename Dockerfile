FROM ubuntu:latest
LABEL maintainer "Pedro Mello pedro_mello@icloud.com"

RUN apt-get update
RUN apt-get install -y ruby ruby-dev rubygems curl gnupg
RUN curl -sL https://deb.nodesource.com/setup_6.x | bash -
RUN apt-get install -y nodejs
RUN apt-get install -y npm
RUN npm install -g grunt-cli
RUN gem install compass

CMD ["grunt"]