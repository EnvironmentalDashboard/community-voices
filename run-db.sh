#! /bin/bash

docker run -p 3007:3306 -e "MYSQL_ROOT_HOST=172.17.0.1" -e "MYSQL_ALLOW_EMPTY_PASSWORD=true" --name cv-mysql -d mysql/mysql-server:latest

# Then, connect with `mysql -h 127.0.0.1 -P 3007 -u root`
