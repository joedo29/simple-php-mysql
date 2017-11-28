DROP TABLE IF EXISTS transaction_table;
DROP TABLE IF EXISTS item;
DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS users;

-- Create 'users' table 
CREATE TABLE users (
    user_id INT AUTO_INCREMENT NOT NULL,
    user_Name VARCHAR(225),
    user_Password VARCHAR(50),
    email VARCHAR(80),
    city VARCHAR(30),
    state CHAR(5),
    phone VARCHAR(15),
    zip VARCHAR(6),
    signup_date DATETIME,
    isAdmin INT,-- limited to userid 1 being admin 
    PRIMARY KEY (user_id)
);

-- Insert data into 'users' table 
load data local infile'~/Downloads/data/user.txt'INTO table users;

-- Create 'books' table
CREATE TABLE books (
    book_id INT AUTO_INCREMENT NOT NULL,
    isbn_13 VARCHAR(13) UNIQUE,
    isbn_10 VARCHAR(10) UNIQUE,
    title VARCHAR(225) NOT NULL,
    author VARCHAR(225),
    publisher VARCHAR(225),
    year_published INT,
    book_subject VARCHAR(100),
    PRIMARY KEY (book_id)
);

-- Insert data into 'books' table
load data local infile '~/Downloads/data/book.txt' INTO table books;

-- Create 'item' table
CREATE TABLE item (
    item_id INT AUTO_INCREMENT NOT NULL,
    book_id INT NOT NULL,
    user_id INT NOT NULL,
    price DECIMAL(4 , 2 ) NOT NULL,
    available_copies INT,-- always going to be 1 by default when new item entered, once sold changed to 0 
    book_condition VARCHAR(100),
    sold_UserId int,-- who the 'item' was sold to 
    sold_date datetime, -- when the seller 'marked' the transaction as completed under his 'mybooks' button 
    PRIMARY KEY (item_id),
    FOREIGN KEY (book_id)
        REFERENCES books (book_id),
    FOREIGN KEY (user_id)
        REFERENCES users (user_id),
	FOREIGN KEY (sold_userId)
         REFERENCES users (user_id)
);

load data local infile '~/Downloads/data/item.txt' INTO table item;

CREATE TABLE transaction_table (
    t_id INT AUTO_INCREMENT NOT NULL,
    item_id INT NOT NULL,
    buyer_Userid INT NOT NULL,
    t_date DATETIME,
    isAccepted boolean,
    PRIMARY KEY (t_id),
    FOREIGN KEY (item_id)
        REFERENCES item (item_id),
    FOREIGN KEY (buyer_Userid)
        REFERENCES users (user_id)
);