#!/bin/bash
/usr/bin/mysql -h $2 -u root '-pW0won5$5' <<EOFMYSQL
create database $1 DEFAULT CHARACTER SET latin1 COLLATE latin1_general_ci;
EOFMYSQL
