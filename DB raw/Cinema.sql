DROP TABLE IF EXISTS reservations;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS products;


CREATE TABLE products (
    productid   INT NOT NULL,
    name        VARCHAR(100),
    description VARCHAR(255), 
    price       DECIMAL(10, 2),
    type        VARCHAR(50),
    PRIMARY KEY (productid)
);

CREATE TABLE reservations (
    reservationid   INT NOT NULL,
    userid          INT,
    productid       INT,
    reservationdate DATE,
    status          VARCHAR(20),
    PRIMARY KEY (reservationid),
    FOREIGN KEY (productid) REFERENCES products (productid),
    FOREIGN KEY (userid) REFERENCES users (userid)
);

CREATE TABLE users (
    userid    INT NOT NULL,
    firstname VARCHAR(50),
    lastname  VARCHAR(50),
    country   VARCHAR(50),
    city      VARCHAR(50),
    address   VARCHAR(100),
    email     VARCHAR(100),
    username  VARCHAR(50),
    password  VARCHAR(100),
    role      VARCHAR(20),
    PRIMARY KEY (userid),
    UNIQUE (username)
);




CREATE TABLE registration_requests (
    requestid INT NOT NULL AUTO_INCREMENT,
    firstname VARCHAR(50),
    lastname VARCHAR(50),
    country VARCHAR(50),
    city VARCHAR(50),
    address VARCHAR(100),
    email VARCHAR(100),
    username VARCHAR(50),
    password VARCHAR(100),
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    PRIMARY KEY (requestid)
);

