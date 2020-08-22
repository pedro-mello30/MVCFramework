#FROM ubuntu:latest
#LABEL maintainer "Pedro Mello pedro_mello@icloud.com"
#
#RUN apt-get update
#RUN apt-get install -y ruby ruby-dev rubygems curl gnupg
#RUN curl -sL https://deb.nodesource.com/setup_6.x | bash -
#RUN apt-get install -y nodejs
#RUN apt-get install -y npm
#
#
#RUN npm install -g grunt-cli grunt-init
#RUN gem install compass
##RUN grunt-init ./boilerplate
##RUN npm install
#
#
#CMD ["grunt"]


FROM pedromellob1/fwgrunt:latest
LABEL maintainer "Pedro Mello pedro_mello@icloud.com"

RUN npm install -g grunt-init