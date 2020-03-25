#! /bin/bash

mysql -h 127.0.0.1 -P 3007 -u root -e 'DROP DATABASE community_voices;'
mysql -h 127.0.0.1 -P 3007 -u root < migrate/schema.sql
