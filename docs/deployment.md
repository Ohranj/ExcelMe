# Install Nginx
```
sudo apt update
sudo apt install nginx

```


# AWS
 - Configure required security groups


# Initial Nginx conf
```
server {
	listen 80 default_server;
	listen [::]:80 default_server;

	root /var/www/ExcelMe/public;

	# Add index.php to the list if you are using PHP
	index index.php;

	server_name _;

	location / {
		# First attempt to serve request as file, then
		# as directory, then fall back to displaying a 404.
		try_files $uri $uri/ /index.php?$query_string;
	}

	location ~ \.php$ {
		fastcgi_pass unix:/run/php/php8.2-fpm.sock;
		fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
	}

	location ~ /\.(?!well-known).* {
        deny all;
    }
}

```


# Install PHP
```
sudo add-apt-repository ppa:ondrej/php
sudo add-apt-repository ppa:ondrej/nginx-mainline
sudo apt-get install php[8.2]-fpm -y
```

```
sudo apt install php8.2-fpm php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-sqlite3 php-cli unzip git
```


# Install composer
```
curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
HASH=`curl -sS https://composer.github.io/installer.sig`
php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer

```


# Install Node / npm
```
curl -sL https://deb.nodesource.com/setup_16.x -o /tmp/nodesource_setup.sh
sudo bash /tmp/nodesource_setup.sh
sudo apt-get install -y nodejs

```


# Lets Encrypt