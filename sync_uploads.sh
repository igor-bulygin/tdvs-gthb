#!/bin/bash
rsync -razhe ssh --progress dev2@dev.todevise.com:/var/www/dev2.todevise.com/web/web/uploads/ ./web/uploads/
