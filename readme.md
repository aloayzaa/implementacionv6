## Configuracion ROOT Mysql

use mysql;
update user set authentication_string=PASSWORD('W0won5$5')  where User='root';
FLUSH PRIVILEGES;

GRANT ALL ON DATABASE_NAME.* TO 'laraveluser'@'%' IDENTIFIED BY 'your_laravel_db_password';
