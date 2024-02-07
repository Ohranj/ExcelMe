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
```
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d xlmeapp.com -d www.xlmeapp.com
```

### Renew Cert if not auto-renew
```
sudo certbot renew --dry-run
sudo certbot renew
```


## Updated Nginx
```
server {
	root /var/www/ExcelMe/public;

	# Add index.php to the list if you are using PHP
	index index.php;

	server_name xlmeapp.com www.xlmeapp.com;

	location / {
		# First attempt to serve request as file, then
		# as directory, then fall back to displaying a 404.
		try_files $uri $uri/ /index.php?$query_string;
	}

	# pass PHP scripts to FastCGI server
	#
	location ~ \.php$ {
		fastcgi_pass unix:/run/php/php8.2-fpm.sock;
		fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
         	include fastcgi_params;
	}

	location ~ /\.(?!well-known).* {
       	deny all;
    }

	listen [::]:443 ssl ipv6only=on; # managed by Certbot
	listen 443 ssl; # managed by Certbot
	ssl_certificate /etc/letsencrypt/live/xlmeapp.com/fullchain.pem; # managed by Certbot
	ssl_certificate_key /etc/letsencrypt/live/xlmeapp.com/privkey.pem; # managed by Certbot
	include /etc/letsencrypt/options-ssl-nginx.conf; # managed by Certbot
	ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem; # managed by Certbot
}

server {
    if ($host = www.xlmeapp.com) {
        return 301 https://$host$request_uri;
    } # managed by Certbot


    if ($host = xlmeapp.com) {
        return 301 https://$host$request_uri;
    } # managed by Certbot


	listen 80 default_server;
	listen [::]:80 default_server;

	server_name xlmeapp.com www.xlmeapp.com;
    return 404; # managed by Certbot

}
```