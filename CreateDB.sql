CREATE DATABASE IF NOT EXISTS Maria;

USE Maria;

CREATE TABLE IF NOT EXISTS Users (
   id INT PRIMARY KEY AUTO_INCREMENT,
   username VARCHAR(50),
   pass VARCHAR(50)
);

INSERT INTO Users (username, pass) VALUES ('qwer', 'qwer');