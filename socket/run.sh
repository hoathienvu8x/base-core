#!/bin/bash

for pid in $(netstat -tulpn | grep 8080 | awk '{print $7}' | grep -Eo "([0-9]*)" | awk '{print $1}');
do
	if ps -l $pid > /dev/null
	then
		kill -9 $pid
	fi
done
echo "Start server port 8080"
php-cgi -q -f /home/chatws/chatws.php &
