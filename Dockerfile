FROM php:7.3-apache
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Install cron
RUN apt-get update && apt-get install -y cron

# Add files
ADD entrypoint.sh /entrypoint.sh

RUN chmod +x /entrypoint.sh

ENTRYPOINT ["sh", "/entrypoint.sh"]
