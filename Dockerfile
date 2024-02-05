FROM php:8.2-fpm

#Declare variables
ARG user=alex
ARG work_dir=/var/www


#Root project folder
WORKDIR $work_dir


#Server packages
RUN apt-get update && apt-get install -y \ 
    libpng-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    git \
    curl \
    zip \
    unzip \
    supervisor \
    sqlite3 \
    sudo

RUN apt-get clean && rm -rf /var/lib/apt/lists/*


#PHP specific packages
RUN docker-php-ext-install \
    pdo_mysql \
    exif \
    bcmath \
    gd \
    mbstring

# RUN pecl install redis

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer


#Install node
RUN curl -sL https://deb.nodesource.com/setup_16.x -o /tmp/nodesource_setup.sh
RUN bash /tmp/nodesource_setup.sh
RUN apt install nodejs npm -y


#Copy local folders to the root project folder
COPY . $work_dir


#Install npm packages
#RUN npm install
#RUN npm install alpinejs


#Create a new user and add relevant groups
RUN useradd -ms /bin/bash $user
RUN usermod -a -G www-data,root $user

RUN chown -R $user:$user $work_dir


#Shell in to container with user
USER $user



##Build and run image
# docker build -t [tag, i.e laravel-8.2]:[version, i.e 0.1] .
# docker run -it --rm [tag]:[version]

##Shell into container
# docker ps
# docker exec -it [container id] bash