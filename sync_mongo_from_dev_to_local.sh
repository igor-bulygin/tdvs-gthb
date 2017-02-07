#!/bin/bash

folder="db_backup"

now=$(date +"%Y%m%d%H%M")
folder="$folder/$now/"

collections=`echo "show collections" | mongo localhost:27777/todevise --quiet`

for collection in $collections;
do
    printf "\n"
    printf "\n"

    echo "Exporting $collection"
    mongoexport -h localhost:27777 -d todevise -c $collection --out $folder/$collection.json

    printf "\n"

    echo "Importing $collection"
    mongoimport -h localhost:27017 -d todevise -c $collection --drop --file $folder/$collection.json

    printf "\n"
    printf "\n"
done
