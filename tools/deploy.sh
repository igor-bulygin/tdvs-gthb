#!/bin/bash
rsync -razhe ssh --progress --exclude '.git' --exclude 'web/uploads' --exclude 'runtime' --exclude 'vendor' --exclude 'web/assets' --exclude 'thumbor_cache' --exclude 'thumbor_resized' --exclude 'db_backup'  . root@dev.todevise.com:/var/www/dev.todevise.com/web/
ssh root@dev.todevise.com << EOF
su - todevise
docker exec web_nginx_1 bash -c 'cd /var/www/html/ && composer install -v --prefer-dist && ./yii mongodb-migrate --interactive=0'
EOF
