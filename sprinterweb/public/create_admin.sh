#!/bin/bash
printf -v var "%q" $2
/usr/bin/mysql -h $4 -u root '-pW0won5$5' <<EOFMYSQL
use $1;
INSERT INTO usuario values('1', 'ADMINISTRADOR', 'Administrador del Sistema', '$var', '$3', null, 0, 1,'ADMINISTRADOR', null, 0)
EOFMYSQL
