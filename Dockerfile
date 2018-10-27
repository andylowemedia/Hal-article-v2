FROM 540688370389.dkr.ecr.eu-west-1.amazonaws.com/low-emedia/php:latest

COPY /appcode /var/www/html

VOLUME /var/www/html

CMD ["/usr/local/bin/run.sh"];
