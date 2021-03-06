#!/bin/bash

# Long term, this should probably get moved into the Dockerfile itself.
# Yes, it makes the Dockerfile more messy, but cache can then be taken advantage
# of for the long command of installing all relevant packages with apt-get.

# install apt packages, set timezone (see: https://serverfault.com/a/683651/456938), install composer
apt-get update && \
	apt-get -qq -y install apt-utils tzdata apache2 php libapache2-mod-php php-cli php-mbstring php-xml php-mysql php-xdebug php-gd curl php-curl git unzip wget postfix cron mysql-client
INI_LOC=`php -i | grep 'Loaded Configuration File => ' | sed 's/Loaded Configuration File => //g' | sed 's/cli/apache2/g'` && \
	sed -ie 's/upload_max_filesize = 2M/upload_max_filesize = 64M/g' "$INI_LOC" && \
	sed -ie 's/post_max_size = 8M/post_max_size = 512M/g' "$INI_LOC" && \
	sed -ie 's/; max_input_vars = 1000/max_input_vars = 7000/g' "$INI_LOC"
ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
./build/composer-install.sh && \
	php composer.phar install && \
	php composer.phar update && \
	php composer.phar install
a2enmod rewrite headers && mv /var/www/html/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
service postfix start

# Make sure log/access.log exists
mkdir -p /var/www/html/log
touch /var/www/html/log/access.log
chmod a+w /var/www/html/log/access.log

# Make sure CV_Media/images exists
mkdir -p /var/www/uploads/CV_Media/images
chmod a+w /var/www/uploads/CV_Media/images

# Load cron file
crontab /var/www/html/crontab/crontab
