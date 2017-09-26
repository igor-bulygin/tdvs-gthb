#!/bin/bash

folder="db_backup_beta"
remote_source_host="beta.todevise.com"
remote_source_port="22"
remote_source_user="todeviseapp"
remote_source_db_host="ddbb.todevise.com"
remote_source_db_port="27017"
remote_source_db_name="todevise"
remote_source_ssh_port="27777"

remote_target_host="dev.todevise.com"
remote_target_port="1021"
remote_target_user="todeviseapp"
remote_target_db_host="ddbb.todevise.com"
remote_target_db_port="27017"
remote_target_db_name="todevise"
remote_target_ssh_port="27788"

echo "Openning ssh tunnel with $remote_source_user@$remote_source_host"
ssh -M -S todevisesource-ctrl-socket -fnNT -p$remote_source_port -L $remote_source_ssh_port:$remote_source_db_host:$remote_source_db_port $remote_source_user@$remote_source_host

echo "Openning ssh tunnel with $remote_target_user@$remote_target_host"
ssh -M -S todevisetarget-ctrl-socket -fnNT -p$remote_target_port -L $remote_target_ssh_port:$remote_target_db_host:$remote_target_db_port $remote_target_user@$remote_target_host


now=$(date +"%Y%m%d%H%M")
folder="$folder/$now/"

collections=`echo "show collections" | mongo localhost:$remote_source_ssh_port/$remote_source_db_name --quiet`
#collections=`echo "show collections" | mongo localhost:$remote_source_ssh_port/$remote_source_db_name --quiet | grep 'person\|product'`

for collection in $collections;
do
#	if [ "$collection" == "todeviselog" ]; then
#    	echo "Ignoring collection $collection"
#		continue
#	fi
    printf "\n"
    printf "\n"

    echo "Exporting $collection"
    mongoexport -h localhost:$remote_source_ssh_port -d $remote_source_db_name -c $collection --out $folder/$collection.json

    printf "\n"

    echo "Deleting contents of $collection"
    mongo localhost:$remote_target_ssh_port/$remote_target_db_name --eval "db.getCollection('$collection').remove({})"

	printf "\n"

    echo "Importing $collection"
    mongoimport -h localhost:$remote_target_ssh_port -d $remote_target_db_name -c $collection --batchSize 10 --file $folder/$collection.json

    printf "\n"
    printf "\n"
done

echo "Closing ssh tunnel $remote_source_user@$remote_source_host"
ssh -S todevisesource-ctrl-socket -O exit $remote_source_user@$remote_source_host

echo "Closing ssh tunnel $remote_target_user@$remote_target_host"
ssh -S todevisetarget-ctrl-socket -O exit $remote_target_user@$remote_target_host