# Use the official Ubuntu base image
FROM ubuntu:latest

# Update package lists and install Apache2
RUN <<EOF
    apt-get update; \
    apt-get install -y apache2; \
    rm -rf /var/lib/apt/lists/*; \
    echo "<h1>Container Hostname: $(hostname)</h1>" > /var/www/html/index.html; \
    echo "ServerName localhost" >> /etc/apache2/apache2.conf
EOF
# Expose port 80 for HTTP traffic
EXPOSE 80

# Run Apache2 in the foreground
CMD ["/usr/sbin/apache2", "-D", "FOREGROUND"]
