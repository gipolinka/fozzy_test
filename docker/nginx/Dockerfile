FROM nginx

COPY nginx.conf /etc/nginx/
COPY conf.d/default.conf /etc/nginx/conf.d/

RUN echo "upstream php-upstream { server php-fpm:9000; }" > /etc/nginx/conf.d/upstream.conf

CMD ["nginx"]

EXPOSE 80
EXPOSE 443
