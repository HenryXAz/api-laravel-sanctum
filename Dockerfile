FROM richarvey/nginx-php-fpm:3.1.6

COPY . .

CMD ["/start.sh"]
