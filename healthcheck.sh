#!/bin/bash

curl --fail http://localhost/community-voices || exit 1
curl --fail http://localhost/community-voices/public/digital-signage.php || exit 1
