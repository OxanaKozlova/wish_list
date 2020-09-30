FROM nginx:stable-alpine

RUN rm /etc/nginx/conf.d/default.conf

ADD ./config/conf.d/* /etc/nginx/conf.d/
ADD ./config/nginx.conf /etc/nginx
