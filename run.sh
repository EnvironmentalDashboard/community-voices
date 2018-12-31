#!/bin/bash

if [ "$#" -gt 0 ]
then
	IFS="" # so newlines don't get lost with DKIM=`cat /etc/opendkim/keys/environmentaldashboard.org/mail.private`
	# live server:
	docker run -dit -p 3002:80 --restart always \
	-v /var/www/uploads/CV_Media/images/:/var/www/uploads/CV_Media/images/ \
	-v $(pwd):/var/www/html/ \
	-e "MYSQL_HOST=159.89.232.129" -e "MYSQL_DB=community_voices" -e "MYSQL_USER=public_cv" -e "MYSQL_PASS=1234" \
	-e SERVER=`hostname` -e DKIM=`cat /etc/opendkim/keys/environmentaldashboard.org/mail.private` \
	--name PROD_CV community-voices
else
	# local machine:
	docker run -dit -p 3002:80 --restart always \
	-v $(pwd)/CV_Media/images/:/var/www/uploads/CV_Media/images/ \
	-v $(pwd):/var/www/html/ \
	-e "MYSQL_HOST=159.89.232.129" -e "MYSQL_DB=community_voices" -e "MYSQL_USER=public_cv" -e "MYSQL_PASS=1234" \
	-e SERVER=`hostname` \
	--name LOCAL_CV community-voices
fi
