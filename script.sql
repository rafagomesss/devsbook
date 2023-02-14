CREATE DATABASE IF NOT EXISTS devsbook;
USE devsbook;

-- DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users
(
	id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(200) NOT NULL,
    name VARCHAR(150) NOT NULL,
    birthdate DATE NOT NULL,
    city VARCHAR(150),
	work VARCHAR(150),
	avatar VARCHAR(100) DEFAULT 'default.jpg' NOT NULL,
	cover VARCHAR(100)  DEFAULT 'cover.jpg' NOT NULL,
	token VARCHAR(200),
    PRIMARY KEY (id)
);

-- DROP TABLE IF EXISTS userrelations;
CREATE TABLE IF NOT EXISTS userrelations
(
	id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    user_from INT UNSIGNED,
    user_to INT UNSIGNED,
    PRIMARY KEY(id)
);

-- DROP TABLE IF EXISTS posts;
CREATE TABLE IF NOT EXISTS posts
(
	id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    type VARCHAR(20),
    body TEXT,
    id_user INT UNSIGNED,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id)
);

-- DROP TABLE IF EXISTS postcomments;
CREATE TABLE IF NOT EXISTS postcomments
(
	id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    id_post INT UNSIGNED,
    id_user INT UNSIGNED,
    body TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id)
);

-- DROP TABLE IF EXISTS postlikes;
CREATE TABLE IF NOT EXISTS postlikes
(
	id INT UNSIGNED AUTO_INCREMENT NOT NULL,
	id_post  INT UNSIGNED,
	id_user  INT UNSIGNED,
	created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id)
);

-- SHOW TABLES;

SELECT * FROM posts;

SELECT * FROM usersrelations;

INSERT INTO
	usersrelations(user_from, user_to)
VALUES
(1, 2),
(1, 3),
(1, 254),
(1, 384),
(1, 923),
(1, 6541);

SELECT * FROM users;
select * from posts;
SELECT * FROM postlikes;



# UPDATE users SET work = 'System Analyst', city = 'Maringá - PR, Brazil' WHERE id = 1 LIMIT 1;
# UPDATE users SET work = 'Administradora', city = 'Maringá - PR, Brazil' WHERE id = 2 LIMIT 1;