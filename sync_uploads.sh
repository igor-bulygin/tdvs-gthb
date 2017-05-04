#!/bin/bash

folder="db_backup"
remote_host="dev.todevise.com"
remote_port="1021"
remote_user="todeviseapp"
remote_folder="/var/www/todevise/web/current/web/uploads/"

rsync -razhe "ssh -p $remote_port" --progress $remote_user@$remote_host:$remote_folder ./web/uploads/
