FROM mysql/mysql-server:8.0
ENV LC_ALL=C.UTF-8
ADD ./docker/mysql /docker-entrypoint-initdb.d
ADD ./docker/mysql.cnf /etc/my.cnf
EXPOSE 3306

ENTRYPOINT ["/entrypoint.sh"]

EXPOSE 3306 33060
CMD ["mysqld"]
