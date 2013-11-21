#!/bin/bash

mysqladmin -h deathstar.local -u LSTestAdmin -ppassword1 drop wstest -f

mysqladmin -h deathstar.local -u LSTestAdmin -ppassword1 create wstest

exit 0