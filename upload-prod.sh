# If a different dump should be loaded, it can be provided
# as the first command-line argument.
if [ "$#" -gt 0 ]
then
  dump=$1
else
  dump=db/dump.sql
fi

docker exec cv-mysql mysql community_voices < $dump
