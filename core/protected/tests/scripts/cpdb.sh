#!/bin/bash
# This script assumes that on a linux box mysql-client bins are installed
#   and on an OSx box, mysql\bin is in the path

# first drop the previous copper-unittest
mysqladmin -h deathstar.local -u LSTestAdmin -ppassword1 drop copper-unittest -f

mysqladmin -h deathstar.local -u LSTestAdmin -ppassword1 create copper-unittest

# now copy the installed wstest into the new db.
mysqldump -h deathstar.local -u LSTestAdmin -ppassword1 wstest | mysql -h deathstar.local -u LSTestAdmin -ppassword1 copper-unittest

exit 0