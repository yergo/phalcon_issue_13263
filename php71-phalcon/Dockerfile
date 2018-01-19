FROM thatsamguy/xenial-php71

RUN \
    apt-get update && \
    apt-get install -y php-phalcon && \
    apt-get clean -yqq && \
    apt-get autoremove -y && \
    rm -rf /var/lib/apt/lists/* && \
    a2enmod rewrite

EXPOSE 80

COPY index.php /var/www/html

CMD /usr/sbin/apache2ctl -D FOREGROUND
