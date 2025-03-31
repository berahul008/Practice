# Use the official Ubuntu base image
FROM ubuntu:latest

# Copy the script into the container
COPY script.sh /usr/local/bin/script.sh

# Update package lists and install Apache2
RUN <<EOF
  apt-get update; \
  apt-get install -y apache2; \
  rm -rf /var/lib/apt/lists/*; \
  chmod +x /usr/local/bin/script.sh; \
  echo "ServerName localhost" >> /etc/apache2/apache2.conf; \
EOF

# Expose port 80 for HTTP traffic
EXPOSE 80

# Run Apache2 in the foreground
CMD ["/bin/bash", "-c", "/usr/local/bin/script.sh && /usr/sbin/apache2ctl -D FOREGROUND"]
