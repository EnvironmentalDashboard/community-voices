#! /bin/sh
#
# Note: this script requires equal versions of unison on both machines.
# On top of that, `ednyc1` must be resolved to an actual host
# (this is usually done in /etc/hosts).
# To set up equal versions of unison, make sure that unison is installed
# via Homebrew or by source using this guide:
# https://github.com/bcpierce00/unison/issues/200

unison CV_Media/images/ ssh://root@ednyc1//var/www/uploads/CV_Media/images -batch -nodeletion ssh://root@ednyc1//var/www/uploads/CV_Media/images
