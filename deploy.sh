#!/bin/bash
ssh dev2@dev.todevise.com 'cd web ; sudo chown dev2:dev2 -R ./'
rsync -razhe ssh --progress --exclude '.git' --exclude 'runtime' --exclude 'vendor' --exclude 'web/assets' . dev2@dev.todevise.com:/var/www/dev2.todevise.com/web/
ssh dev2@dev.todevise.com 'PATH=$PATH:`pwd`/bin/ ; cd web ; composer update ; sudo chown www-data:www-data -R ./ '
