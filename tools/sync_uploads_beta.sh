#!/bin/bash

remote_host="beta.todevise.com"
remote_port="22"
remote_user="todeviseapp"
remote_folder="/var/www/todevise/web/current/web/uploads/"

rsync -razhe "ssh -p $remote_port" --progress $remote_user@$remote_host:$remote_folder ./web/uploads/
