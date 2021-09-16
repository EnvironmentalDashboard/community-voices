FROM ubuntu:18.04
ENV DEBIAN_FRONTEND=noninteractive \
    TZ=America/New_York \
    COMPOSER_ALLOW_SUPERUSER=1 \
    APACHE_RUN_USER=www-data \
    APACHE_RUN_GROUP=www-data \
    APACHE_LOG_DIR=/var/log/apache2 \
    APACHE_LOCK_DIR=/var/lock/apache2 \
    APACHE_PID_FILE=/var/run/apache2.pid
WORKDIR /var/www/html
ADD ./composer.* /var/www/html/
ADD ./build/* /var/www/html/build/
ADD ./apache/* /var/www/html/apache/
ADD ./crontab/* /var/www/html/crontab/
RUN ./build/init.sh
ADD . /var/www/html
EXPOSE 80
CMD /etc/init.d/cron start; /usr/sbin/apache2ctl -D FOREGROUND
