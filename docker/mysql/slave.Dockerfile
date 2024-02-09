FROM mysql/mysql-server:8.0.32

RUN touch /var/log/mysql-general.log
RUN chown mysql:mysql /var/log/mysql-general.log
RUN touch /var/log/mysql-error.log
RUN chown mysql:mysql /var/log/mysql-error.log
RUN touch /var/log/mysql-slow.log
RUN chown mysql:mysql /var/log/mysql-slow.log

COPY ./slave/ /etc/mysql/
COPY init_slave.sql /docker-entrypoint-initdb.d/initialize.sql
