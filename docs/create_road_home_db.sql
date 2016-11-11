CREATE SCHEMA `road_home_db`;

USE `road_home_db`;

CREATE TABLE `road_home_db`.`shift` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `count` INT(5) NOT NULL DEFAULT 1,
  `email` VARCHAR(100) NULL,
  `start_time` DATETIME NOT NULL,
  `end_time` DATETIME NULL,
  `organization` VARCHAR(100) NULL,
  `location` VARCHAR(100) NULL,
  `details` VARCHAR(100) NULL,
  `department` VARCHAR(100) NULL,
  PRIMARY KEY (`id`));

INSERT INTO `road_home_db`.`shift` (`first_name`, `last_name`, `email`, `start_time`, `organization`, `location`, `department`) VALUES ('John', 'Doe', 'abc@abc.com', '2016-11-04 17:00', 'Volunteers R US', 'SLC', 'Some department');
INSERT INTO `road_home_db`.`shift` (`first_name`, `last_name`, `email`, `start_time`, `organization`, `location`, `department`) VALUES ('Jane', 'Doe ', 'xyz', '2016-11-04 12:00', 'Volunteers R US', 'SLC', 'Some other department');
INSERT INTO `road_home_db`.`shift` (`first_name`, `last_name`, `email`, `start_time`, `organization`, `location`, `department`) VALUES ('Oliver ', 'Queen', 'oliver@queenconsolidated.com', '2016-11-04 09:00', 'Queen Volunteers Group', 'Starling City', 'Archery Department');
INSERT INTO `road_home_db`.`shift` (`first_name`, `last_name`, `email`, `start_time`, `organization`, `location`, `department`) VALUES ('Felicity', 'Smoke', 'felicitysmoke@queenconsolidated.com', '2016-11-04 13:00', 'Queen Volunteers Group', 'Starling City', 'IT Department');
INSERT INTO `road_home_db`.`shift` (`first_name`, `last_name`, `email`, `start_time`, `organization`, `location`, `department`) VALUES ('Barry', 'Allen', 'barry@csiscentralcity.gov', '2016-11-04 08:30', 'Star Labs', 'Central City', 'CSIS');
INSERT INTO `road_home_db`.`shift` (`first_name`, `last_name`, `email`, `start_time`, `organization`, `location`, `department`) VALUES ('Bruce', 'Wayne', 'brucewayne@wayneenterprises.com', '2016-11-04 15:00', 'Wayne Enterprises', 'Gotham', 'Vigilinatism');

UPDATE `road_home_db`.`shift` SET `end_time`='2016-11-04 18:00:00' WHERE `id`='1';
UPDATE `road_home_db`.`shift` SET `end_time`='2016-11-04 13:00:00' WHERE `id`='2';
UPDATE `road_home_db`.`shift` SET `end_time`='2016-11-04 13:00:00' WHERE `id`='3';


CREATE TABLE `road_home_db`.`admin` (
 `id` INT NOT NULL AUTO_INCREMENT,
 `username` VARCHAR(50) NOT NULL,
 `password` VARCHAR(255) NOT NULL,
 PRIMARY KEY (`id`));

INSERT INTO `road_home_db`.`admin` (`username`, `password`) VALUES ('admin', 'test');



