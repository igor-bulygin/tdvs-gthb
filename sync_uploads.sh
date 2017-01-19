#!/bin/bash
rsync -razhe "ssh -p 1021" --progress todeviseapp@dev.todevise.com:/var/www/todevise/web/current/web/uploads/ ./web/uploads/
