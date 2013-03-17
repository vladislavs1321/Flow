CREATE DATABASE IF NOT EXISTS `flow`;
USE `flow`;

CREATE TABLE IF NOT EXISTS `user` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(256),
    `password` int(50),
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `flow_data` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `data_url` varchar(256) default NULL,
    `user_id` int(11) default NULL,
    `description` varchar(2048) default NULL,
    INDEX (user_id),
    CONSTRAINT FOREIGN KEY (user_id) REFERENCES user(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
