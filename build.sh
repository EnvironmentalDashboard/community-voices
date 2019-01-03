#!/bin/bash

export IFS="" && docker build --build-arg DKIM=`cat /etc/opendkim/keys/environmentaldashboard.org/mail.private` -t community-voices .
