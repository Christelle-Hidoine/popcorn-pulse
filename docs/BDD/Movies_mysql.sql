CREATE DATABASE IF NOT EXISTS `MOVIES` DEFAULT CHARACTER SET UTF8MB4 COLLATE utf8_general_ci;
USE `MOVIES`;

CREATE TABLE `ACTOR` (
  `code_actor` VARCHAR(42),
  `firstname` VARCHAR(42),
  `lastname` VARCHAR(42),
  PRIMARY KEY (`code_actor`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

CREATE TABLE `BELONGS_TO2` (
  `code_movie` VARCHAR(42),
  `code_genre` VARCHAR(42),
  PRIMARY KEY (`code_movie`, `code_genre`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

CREATE TABLE `GENRE` (
  `code_genre` VARCHAR(42),
  `name` VARCHAR(42),
  PRIMARY KEY (`code_genre`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

CREATE TABLE `MOVIE` (
  `code_movie` VARCHAR(42),
  `title` VARCHAR(42),
  `duration` VARCHAR(42),
  `rating` VARCHAR(42),
  `summary` VARCHAR(42),
  `synopsis` VARCHAR(42),
  `release_date` VARCHAR(42),
  `country` VARCHAR(42),
  `poster` VARCHAR(42),
  `code_type` VARCHAR(42),
  PRIMARY KEY (`code_movie`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

CREATE TABLE `PLAY` (
  `code_movie` VARCHAR(42),
  `code_actor` VARCHAR(42),
  `role` VARCHAR(42),
  `credit_order` VARCHAR(42),
  PRIMARY KEY (`code_movie`, `code_actor`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

CREATE TABLE `SEASON` (
  `code_season` VARCHAR(42),
  `number` VARCHAR(42),
  `nb_episodes` VARCHAR(42),
  `code_movie` VARCHAR(42),
  PRIMARY KEY (`code_season`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

CREATE TABLE `TYPE` (
  `code_type` VARCHAR(42),
  `name` VARCHAR(42),
  PRIMARY KEY (`code_type`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

ALTER TABLE `BELONGS_TO2` ADD FOREIGN KEY (`code_genre`) REFERENCES `GENRE` (`code_genre`);
ALTER TABLE `BELONGS_TO2` ADD FOREIGN KEY (`code_movie`) REFERENCES `MOVIE` (`code_movie`);
ALTER TABLE `MOVIE` ADD FOREIGN KEY (`code_type`) REFERENCES `TYPE` (`code_type`);
ALTER TABLE `PLAY` ADD FOREIGN KEY (`code_actor`) REFERENCES `ACTOR` (`code_actor`);
ALTER TABLE `PLAY` ADD FOREIGN KEY (`code_movie`) REFERENCES `MOVIE` (`code_movie`);
ALTER TABLE `SEASON` ADD FOREIGN KEY (`code_movie`) REFERENCES `MOVIE` (`code_movie`);