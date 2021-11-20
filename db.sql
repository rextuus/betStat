CREATE DATABASE bet;
USE bet;
CREATE USER 'bet_user'@'localhost' IDENTIFIED BY 'password';
CREATE USER 'bet_user'@'%' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON * . * TO 'bet_user'@'localhost';
GRANT ALL PRIVILEGES ON * . * TO 'bet_user'@'%';
FLUSH PRIVILEGES;
