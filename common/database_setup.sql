-- Create database
CREATE DATABASE EloDB

-- Create tables

-- Changing or updating any ID field is NOT SUPPORTED

CREATE TABLE leagues (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR NOT NULL,
starting_rating INT NOT NULL,
k_value INT NOT NULL,
hidden TINYINT(1) UNSIGNED NOT NULL
)

CREATE TABLE players (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(30) NOT NULL,
league INT UNSIGNED,
rating INT,
wins INT,
losses INT,
played INT,
FOREIGN KEY (league) 
    REFERENCES leagues(id)
    ON DELETE CASCADE
)

CREATE TABLE matches (
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
player INT UNSIGNED,
opponent INT UNSIGNED,
outcome TINYINT(1) UNSIGNED,
newrating INT,
delta INT,
match_time TIMESTAMP,
FOREIGN KEY (player)
	REFERENCES players(id)
	ON DELETE CASCADE,
FOREIGN KEY (opponent)
	REFERENCES players(id)
	ON DELETE SET NULL
	
)
