FROM ubuntu:latest
ARG sasToken
WORKDIR /var/www/html
RUN apt-get update && \
    apt-get install -y ant && \
    apt-get clean;
# Essentials
RUN echo "UTC" > /etc/timezone
RUN apt-get install -y zip unzip curl sqlite git

# Installing bash
RUN apt-get install -y bash
RUN sed -i 's/bin\/ash/bin\/bash/g' /etc/passwd

# Installing PHP
RUN apt-get install -y php7.4 php7.4-fpm php7.4-common php7.4-mysql php7.4-bcmath openssl php7.4-json php7.4-mbstring php7.4-ctype php7.4-xml php7.4-xmlrpc php7.4-curl php7.4-gd php7.4-imagick php7.4-cli php7.4-dev php7.4-imap php7.4-opcache php7.4-soap php7.4-zip php7.4-intl

# Installing and Configuring OpenSSH Server
RUN apt-get install -y --no-install-recommends openssh-server
RUN echo "root:Docker!" | chpasswd
COPY sshd_config /etc/ssh/

# Installing composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN rm -rf composer-setup

# Configure Nginx
RUN apt install -y nginx && rm /etc/nginx/sites-available/default && rm /etc/nginx/sites-enabled/default
RUN echo "daemon off;" >> /etc/nginx/nginx.conf
COPY main.conf /etc/nginx/conf.d/
RUN ln -sf /dev/stdout /var/log/nginx/access.log
RUN ln -sf /dev/stderr /var/log/nginx/error.log
#RUN rm /etc/nginx/sites-enabled/Default
#RUN rm /etc/nginx/sites-available/Default

# clone API Welcome Repo
RUN git clone https://github.com/DeveloperMujtaba/welcome-pms.git
# download .env file from storage account
RUN curl https://chicstays.blob.core.windows.net/chicstays/.env.txt?${sasToken} -o .env && ls -la
# make directory in html folder
RUN mkdir /var/www/html/chicstays-backend
# copy files to linux filesystem
COPY . chicstays-backend/
# Permissions configurations for API
  #chown the root directory:
RUN chown -R www-data:www-data /var/www/html/chicstays-backend
  #Set file permission to 644:
RUN find /var/www/html/chicstays-backend -type f -exec chmod 644 {} \;
  #Set directory permission to 755:
RUN find /var/www/html/chicstays-backend -type d -exec chmod 755 {} \;
  #Give rights for web server to read and write storage and cache
RUN chgrp -R www-data /var/www/html/chicstays-backend/storage /var/www/html/chicstays-backend/bootstrap/cache
RUN  chmod -R ug+rwx /var/www/html/chicstays-backend/storage /var/www/html/chicstays-backend/bootstrap/cache

# Permissions for Welcome-PMS
  #chown the root directory:
RUN chown -R www-data:www-data /var/www/html/welcome-pms
  #Set file permission to 644:
RUN find /var/www/html/welcome-pms -type f -exec chmod 644 {} \;
  #Set directory permission to 755:
RUN find /var/www/html/welcome-pms -type d -exec chmod 755 {} \;

WORKDIR /var/www/html/chicstays-backend
# Building process
RUN composer install

#copy init script
COPY init.sh /home/pms/init.sh
RUN chmod 755 /home/pms/init.sh
#expose ssh n nginx ports
EXPOSE 2222 80
#run entry script
ENTRYPOINT ["/home/pms/init.sh"]
