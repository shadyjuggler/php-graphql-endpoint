DROP IF EXISTS graphql_db;
CREATE DATABASE graphql_db;
USE graphql_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL
);

INSERT INTO users (name, email)
VALUES ('john doe', 'john@email.com'),
VALUES ('steve jobs', 'jobs@email.com');
