#!/bin/bash
rsync -razhe ssh --progress root@dev.todevise.com:/var/www/dev.todevise.com/web/web/uploads/ ./web/uploads/
