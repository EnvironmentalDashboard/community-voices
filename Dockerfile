FROM ubuntu:latest
ENV DEBIAN_FRONTEND=noninteractive \
    TZ=America/New_York \
    COMPOSER_ALLOW_SUPERUSER=1 \
    APACHE_RUN_USER=www-data \
    APACHE_RUN_GROUP=www-data \
    APACHE_LOG_DIR=/var/log/apache2 \
    APACHE_LOCK_DIR=/var/lock/apache2 \
    APACHE_PID_FILE=/var/run/apache2.pid
ADD . /var/www/html
WORKDIR /var/www/html
# install apt packages, set timezone (see: https://serverfault.com/a/683651/456938), install composer
RUN apt-get update && \
  apt-get -qq -y install apt-utils tzdata apache2 php libapache2-mod-php php-cli php-mbstring php-xml php-mysql php-xdebug php-gd curl git unzip wget && \
  INI_LOC=`php -i | grep 'Loaded Configuration File => ' | sed 's/Loaded Configuration File => //g' | sed 's/cli/apache2/g'` && \
  sed -ie 's/upload_max_filesize = 2M/upload_max_filesize = 64M/g' "$INI_LOC" && \
  sed -ie 's/post_max_size = 8M/post_max_size = 512M/g' "$INI_LOC" && \
  ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone && \
  curl -sS https://gist.githubusercontent.com/Sammidysam/708b4f1a26cfd5d313dc627c1b3c2ff6/raw/27685d3a740cfc53e3730e60833e7a22796f22b3/composer-install.sh -o composer-install.sh && \
  bash composer-install.sh && \
  php composer.phar update && php composer.phar install && \
  a2enmod rewrite headers && mv /var/www/html/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
EXPOSE 80
CMD /usr/sbin/apache2ctl -D FOREGROUND

# to run:
# docker build -t community-voices .
# docker run -dit -p 3002:80 --restart always -v /var/www/uploads/CV_Media/images/:/var/www/uploads/CV_Media/images/ -v $(pwd):/var/www/html/ -e "MYSQL_HOST=159.89.232.129" -e "MYSQL_DB=community_voices" -e "MYSQL_USER=public_cv" -e "MYSQL_PASS=1234" -e SERVER=`hostname` --name PROD_CV community-voices
# or, on local machine:
# docker run -dit -p 3002:80 --restart always -v /Users/tim/repos/community-voices/CV_Media/images/:/var/www/uploads/CV_Media/images/ -v $(pwd):/var/www/html/ -e "MYSQL_HOST=159.89.232.129" -e "MYSQL_DB=community_voices" -e "MYSQL_USER=public_cv" -e "MYSQL_PASS=1234" -e SERVER=`hostname` --name PROD_CV community-voices
