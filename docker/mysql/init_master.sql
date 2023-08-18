CREATE USER 'zenn_master'@'%' IDENTIFIED WITH mysql_native_password BY 'zenn_master';
GRANT ALL PRIVILEGES ON *.* TO 'zenn_master'@'%';
GRANT REPLICATION SLAVE ON *.* TO 'zenn_master'@'%';
FLUSH PRIVILEGES;
