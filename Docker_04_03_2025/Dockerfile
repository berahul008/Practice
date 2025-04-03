FROM httpd

EXPOSE 80

COPY --chmod=777 script.sh /usr/local/bin/script.sh

RUN echo "ServerName localhost" >> /usr/local/apache2/conf/httpd.conf

CMD ["/bin/bash", "-c", "/usr/local/bin/script.sh && httpd -D FOREGROUND"]
