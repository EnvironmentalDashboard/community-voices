#!/bin/bash

. db.config
# column stats https://serverfault.com/a/912677/456938
mysqldump --no-data --column-statistics=0 -h 159.89.232.129 -u $user -p$pass community_voices > migrate/community-voices.sql