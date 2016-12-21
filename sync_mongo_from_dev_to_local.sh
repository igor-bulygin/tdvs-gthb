#!/bin/bash

database="dev.todevise.com:27017/todevise"
folder="db_backup"

now=$(date +"%Y%m%d%H%M")
folder="$folder/$now/"

collections=`echo "show collections" | mongo $database --quiet`

for collection in $collections;
do
    echo "Exporting $collection"
    mongoexport -h dev.todevise.com -p 27017 -d todevise -c $collection --out $folder/$collection.json

    echo "Importing $collection"
    mongoimport -h localhost -p 27017 -d todevise -c $collection --drop --file $folder/$collection.json
    printf "\n"
done
