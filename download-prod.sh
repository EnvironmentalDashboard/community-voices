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
fi

docker exec cv-mysql mysqldump --no-tablespaces -h 159.89.232.129 -P $port -u $user --password=$pass --databases community_voices > db/dump.sql
#docker exec cv-mysql mysqldump -h 206.189.255.84 -P $cle_port -u $cle_user --password=$cle_pass --databases community_voices > db/cle_dump.sql
