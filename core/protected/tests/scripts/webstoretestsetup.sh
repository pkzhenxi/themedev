#!/bin/bash
# This script assumes that on a linux box mysql-client bins are installed
#   and on an OSx box, mysql\bin is in the path

# convince the webstore that the license has been accepted
mysql -h deathstar.local -u LSTestAdmin -ppassword1 wstest < dbupdate.sql

#copy the db to the test location
./cpdb.sh