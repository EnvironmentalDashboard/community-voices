# If a different IP should be used, it can be provided
# as the first command-line argument.
if [ "$#" -gt 0 ]
then
  ip=$1
else
  ip=159.89.232.129
fi

rsync -av root@$ip:/var/www/uploads/CV_Media/ ./CV_Media
