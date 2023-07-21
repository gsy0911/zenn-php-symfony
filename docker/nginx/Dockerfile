FROM nginx:latest

RUN apt-get update && apt-get install -y procps

COPY default.conf /etc/nginx/conf.d/default.conf

CMD ["nginx", "-g", "daemon off;"]
