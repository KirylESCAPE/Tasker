FROM nginx:alpine

ARG UID=1000
ARG GID=1000

RUN apk add --no-cache shadow

RUN groupmod -o -g ${GID} nginx && \
    usermod -o -u ${UID} -g ${GID} nginx

RUN chown -R nginx:nginx /var/cache/nginx \
    /var/log/nginx \
    /etc/nginx/conf.d \
    /usr/share/nginx/html \
    /var/run \
    && chmod -R 755 /var/run

USER nginx

EXPOSE 8080

CMD ["nginx", "-g", "daemon off;"]   
