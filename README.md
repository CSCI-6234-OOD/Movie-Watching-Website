# Movie-Watching-Website
# By Team 16
## How to run


### 1. Create a file named "Project", and put the codes in to this file as follows,
```
Project/ood/conf/
Project/ood/www/
```
### 2. Install docker

### 3. Run the following commands in the command line tool：
```
docker pull nginx

docker pull php

docker run --name  ood-php-fpm -v ~/Project/ood/www:/www -d php:5.6-fpm

docker exec -it ood-php-fpm bash

docker-php-ext-install mysqli

exit

docker restart ood-php-fpm

docker run --name ood-nginx -p 8083:80 -d \
    -v ~/Project/ood/www:/usr/share/nginx/html:ro \
    -v ~/Project/ood/conf/conf.d:/etc/nginx/conf.d:ro \
    --link ood-php-fpm:php \
    nginx

docker run -itd --name ood-mysql -p 3306:3306 -e MYSQL_ROOT_PASSWORD=123456 mysql:5.6.47

docker pull phpmyadmin/phpmyadmin

docker run --name myadmin -d --link ood-mysql:db -p 8011:80 phpmyadmin/phpmyadmin
```
### 4. Get the IP address of "ood-mysql" from the following command:
```
docker inspect ood-mysql|grep "IPAddress"
```
Then replace the 'hostname' in the file "ood/www/application/config/database.php"

### 5. Change the variable in "ood/www/index.php":
```
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');
```
Change the 'development' to 'production' as follows,
```
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'production');
```
### 6. Create a Mysql database in mysql management page named “ood” and run the “ood.sql” file 



## Now, we can use this website.
### Index Page: http://localhost:8083/?
### mysql management: http://localhost:8011/
