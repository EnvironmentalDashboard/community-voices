#!/bin/bash

# Read our password from our secret file.
if [ -f "db.config" ] || [ -f "/var/secret/db.config" ]
then
	# By placing the loading of the global file first,
	# we are making the local file take precedence over
	# the global file.
	if [ -f "/var/secret/db.config" ]
	then
		. /var/secret/db.config
	fi

	if [ -f "db.config" ]
	then
		. db.config
	fi
else
	echo "You must have a database configuration!"
	echo "Exiting without running."
	exit
fi

# Prepare a FQDN into a domain name.
# On Linux, dnsdomainname can be used,
# but using cut allows for backwards-compatibility
# with Mac OS.
production_domain=environmentaldashboard.org
domain=`cut -f 2- -d . <<< $HOSTNAME`

if [ "$domain" = "$production_domain" ] || [ "$HOSTNAME" = "$production_domain" ]
then
	# live server:
	# Get the computer name.
	computer=`cut -f 1 -d . <<< $HOSTNAME`

	# Set the port to run on according to this name.
	if [ "$computer" = "nuc" ]
	then
		port=5297
	else
		port=3001
	fi

	docker run -dit -p $port:80 --restart always \
	-v /var/www/uploads/CV_Media/images/:/var/www/uploads/CV_Media/images/ \
	-v $(pwd):/var/www/html/ \
	-v /etc/opendkim/keys/environmentaldashboard.org/mail.private:/opendkim/mail.private \
	-e "MYSQL_HOST=159.89.232.129" -e "MYSQL_DB=community_voices" -e "MYSQL_USER=$user" -e "MYSQL_PASS=$pass" \
	-e SERVER=`hostname` \
	--name PROD_CV community-voices
else
	# local machine:
	docker run -dit -p 3001:80 --restart always \
	-v $(pwd)/CV_Media/images/:/var/www/uploads/CV_Media/images/ \
	-v $(pwd):/var/www/html/ \
	-e "MYSQL_HOST=159.89.232.129" -e "MYSQL_DB=community_voices" -e "MYSQL_USER=$user" -e "MYSQL_PASS=$pass" \
	-e SERVER=`hostname` \
	--name LOCAL_CV community-voices
fi
