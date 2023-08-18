CREATE USER 'zenn_slave'@'%' IDENTIFIED WITH mysql_native_password BY 'zenn_slave';
GRANT ALL PRIVILEGES ON *.* TO 'zenn_slave'@'%';
