#!/bin/bash

flag=""
flag=$1

if [ $flag == "-r" ] || [ -z "$flag" ];
then
	cat inservices.sql | docker-compose exec -T db /usr/bin/mysql -u root --password=root inservices
elif [ $flag == "-b" ];
then
	docker-compose exec -T db /usr/bin/mysqldump -u root --password=root inservices > backup.sql
fi

