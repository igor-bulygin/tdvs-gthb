#!/bin/bash

mongoexport -h dev.todevise.com -p 27017 -d todevise -c category --out backup/category.json &&
mongoexport -h dev.todevise.com -p 27017 -d todevise -c country --out backup/country.json &&
mongoexport -h dev.todevise.com -p 27017 -d todevise -c faq --out backup/faq.json &&
mongoexport -h dev.todevise.com -p 27017 -d todevise -c migration --out backup/migration.json &&
mongoexport -h dev.todevise.com -p 27017 -d todevise -c person --out backup/person.json &&
mongoexport -h dev.todevise.com -p 27017 -d todevise -c product --out backup/product.json &&
mongoexport -h dev.todevise.com -p 27017 -d todevise -c size_chart --out backup/size_chart.json &&
mongoexport -h dev.todevise.com -p 27017 -d todevise -c statictext --out backup/statictext.json &&
mongoexport -h dev.todevise.com -p 27017 -d todevise -c tag --out backup/tag.json &&
mongoexport -h dev.todevise.com -p 27017 -d todevise -c term --out backup/term.json &&
mongoexport -h dev.todevise.com -p 27017 -d todevise -c tern --out backup/tern.json &&
mongoexport -h dev.todevise.com -p 27017 -d todevise -c test --out backup/test.json &&
mongoexport -h dev.todevise.com -p 27017 -d todevise -c tmp_sessions --out backup/tmp_sessions.json &&

mongoimport -h localhost -p 27017 -d todevise -c category --drop --file backup/category.json &&
mongoimport -h localhost -p 27017 -d todevise -c country --drop --file backup/country.json &&
mongoimport -h localhost -p 27017 -d todevise -c faq --drop --file backup/faq.json &&
mongoimport -h localhost -p 27017 -d todevise -c migration --drop --file backup/migration.json &&
mongoimport -h localhost -p 27017 -d todevise -c person --drop --file backup/person.json &&
mongoimport -h localhost -p 27017 -d todevise -c product --drop --file backup/product.json &&
mongoimport -h localhost -p 27017 -d todevise -c size_chart --drop --file backup/size_chart.json &&
mongoimport -h localhost -p 27017 -d todevise -c statictext --drop --file backup/statictext.json &&
mongoimport -h localhost -p 27017 -d todevise -c tag --drop --file backup/tag.json &&
mongoimport -h localhost -p 27017 -d todevise -c term --drop --file backup/term.json &&
mongoimport -h localhost -p 27017 -d todevise -c tern --drop --file backup/tern.json &&
mongoimport -h localhost -p 27017 -d todevise -c test --drop --file backup/test.json &&
mongoimport -h localhost -p 27017 -d todevise -c tmp_sessions --drop --file backup/tmp_sessions.json
