# Use an official PHP runtime with Apache
FROM php:7.4-apache

# Install necessary PHP extensions (mysqli is crucial for MySQL)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy your application code into the container
COPY . .

# Explicitly set Apache environment variables
ENV APACHE_RUN_DIR=/var/run/apache2
ENV APACHE_PID_FILE=/var/run/apache2/apache2.pid
ENV APACHE_RUN_USER=www-data
ENV APACHE_RUN_GROUP=www-data
ENV APACHE_LOG_DIR=/var/log/apache2

# Add the ServerName directive to prevent warnings
RUN <<EOF
    echo "ServerName localhost" >> /etc/apache2/apache2.conf; \
    chown -R www-data:www-data /var/www/html; \
    chmod -R 755 /var/www/html; \
EOF

# Expose port 80 for the web application
EXPOSE 80

CMD ["apache2","-D","FOREGROUND"]
