#!/bin/bash
rsync -razhe ssh --progress --exclude '.git' --exclude 'runtime' --exclude 'vendor' --exclude 'web/assets' . root@dev.todevise.com:/var/www/dev.todevise.com/web/
ssh root@dev.todevise.com << EOF
su - todevise
docker exec web_nginx_1 bash -c 'cd /var/www/html/ && composer update'
EOF
