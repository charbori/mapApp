echo "
    CREATE DATABASE IF NOT EXISTS testing;
    CREATE DATABASE IF NOT EXISTS rentalApp;
    GRANT ALL PRIVILEGES ON rentalApp.* TO '${MYSQL_USER}'@'localhost';
    GRANT ALL PRIVILEGES ON testing.* TO '${MYSQL_USER}'@'localhost' with grant option;
    flush privileges;
" > /docker-entrypoint-initdb.d/mysql-server.sql
