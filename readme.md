# DOCKER : LARAVEL + MYSQL + NGINX PROXY + LETSENCRYPT



## Configuracion ROOT Mysql

- use mysql;
- update user set authentication_string=PASSWORD('W0won5$5')  where User='root';
- FLUSH PRIVILEGES;

#### Permisos a usuario
- GRANT ALL ON DATABASE_NAME.* TO 'laraveluser'@'%' IDENTIFIED BY 'your_laravel_db_password';


## Verificar BD

- docker-compose exec laravel-app-project php artisan key:generate
- docker-compose exec laravel-app-project php artisan config:cache
- docker-compose exec laravel-app-project php artisan migrate
- docker-compose exec laravel-app-project php artisan tinker
- \DB::table('migrations')->get();
