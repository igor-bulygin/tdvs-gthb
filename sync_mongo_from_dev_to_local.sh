#!/bin/bash

folder="db_backup"
remote_host="dev.todevise.com"
remote_port="1021"
remote_user="todeviseapp"
remote_database_host="ddbb.todevise.com"
remote_database_port="27017"
remote_database_name="todevise"

echo "Openning ssh tunnel"
ssh -M -S todevise-ctrl-socket -fnNT -p$remote_port -L 27777:$remote_database_host:$remote_database_port $remote_user@$remote_host


now=$(date +"%Y%m%d%H%M")
folder="$folder/$now/"

collections=`echo "show collections" | mongo localhost:27777/$remote_database_name --quiet`

for collection in $collections;
do
	if [ "$collection" == "todeviselog" ]; then
    	echo "Ignoring collection $collection"
		continue
	fi
    printf "\n"
    printf "\n"

    echo "Exporting $collection"
    mongoexport -h localhost:27777 -d $remote_database_name -c $collection --out $folder/$collection.json

    printf "\n"

    echo "Deleting contents of $collection"
    mongo localhost:27017/todevise --eval "db.getCollection('$collection').remove({})"

	printf "\n"

    echo "Importing $collection"
    mongoimport -h localhost:27017 -d todevise -c $collection --file $folder/$collection.json

    printf "\n"
    printf "\n"
done

echo "Closing ssh tunnel"
ssh -S todevise-ctrl-socket -O exit todeviseapp@dev.todevise.com