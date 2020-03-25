if [ "$#" -gt 0 ]
then
  dump=$1
else
  dump=db/dump.sql
fi

mysql -h 127.0.0.1 -P 3007 -u root community_voices < $dump
