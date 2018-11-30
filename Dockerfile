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
ADD ./build/init.sh /init.sh
ADD ./build/composer-install.sh /composer-install.sh
WORKDIR /var/www/html
RUN ["bash", "/init.sh"]
EXPOSE 80
CMD /usr/sbin/apache2ctl -D FOREGROUND

# to run:
# docker build -t community-voices .
# docker run -dit -p 3002:80 --restart always -v /var/www/uploads/CV_Media/images/:/var/www/uploads/CV_Media/images/ -v $(pwd):/var/www/html/ -e "MYSQL_HOST=159.89.232.129" -e "MYSQL_DB=community_voices" -e "MYSQL_USER=public_cv" -e "MYSQL_PASS=1234" -e SERVER=`hostname` --name PROD_CV community-voices
# or, on local machine:
# docker run -dit -p 3002:80 --restart always -v /Users/tim/repos/community-voices/CV_Media/images/:/var/www/uploads/CV_Media/images/ -v $(pwd):/var/www/html/ -e "MYSQL_HOST=159.89.232.129" -e "MYSQL_DB=community_voices" -e "MYSQL_USER=public_cv" -e "MYSQL_PASS=1234" -e SERVER=`hostname` --name PROD_CV community-voices
