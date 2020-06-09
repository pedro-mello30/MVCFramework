FROM ubuntu:18.04
MAINTAINER "Pedro Mello"

ARG DEBIAN_FRONTEND=noninteractive

RUN mkdir app
COPY . /app
RUN bash /app/configure.sh

CMD /usr/sbin/apache2ctl -D FOREGROUND
