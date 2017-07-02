#!/bin/bash


#echo "fastcgi_param STORE_DSN $STORE_DSN;" >> /etc/nginx/fastcgi_params

# Start the server
supervisord --nodaemon