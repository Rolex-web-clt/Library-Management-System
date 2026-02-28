CREATE DATABASE lms;
USE lms;


-- ADMINS TABLE

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    mobile VARCHAR(15) NOT NULL
);

INSERT INTO admins (name, email, password, mobile)
VALUES ('Admin', 'admin@gmail.com', 'admin...', '9845123220');


-- USERS (STUDENTS)

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    mobile VARCHAR(15) NOT NULL UNIQUE,
    address VARCHAR(255) NOT NULL
);

INSERT INTO users (name, email, password, mobile, address)
VALUES(
'Sandeep verma', 'sandeep@gmail.com','sandeep123','9811901407','kharendrapur','kapilvastu'),

(
'Annil vermaa',
'vermaanil0777@gmail.com',
'anil..',
'9877764545'
'bhairahawa, busparkaaaaaaaa'),


('aa',
'nnn@gmail.com',
'nnnnnnnn',
'6213213543',
'kokoookkokoko');


('Mausain Ram',
'rambo@gmaill.com',
'rambo0987',
'9855555555',
'Tuntunpur');


-- AUTHORS

CREATE TABLE authors (
    author_id INT AUTO_INCREMENT PRIMARY KEY,
    author_name VARCHAR(150) NOT NULL
);

INSERT INTO authors (author_name)
VALUES(
'Arjun singh thomas',
'Thomas cell',
'Laxmi prasad David'
);

-- CATEGORIES

CREATE TABLE categories (
    cat_id INT AUTO_INCREMENT PRIMARY KEY,
    cat_name VARCHAR(100) NOT NULL
);

INSERT INTO categories (cat_name)
VALUES
('Computer Science'),
('Novel'),
('Motivational'),
('Story');


-- BOOKS

CREATE TABLE books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    book_name VARCHAR(200) NOT NULL,
    author_id INT NOT NULL,
    cat_id INT NOT NULL,
    book_number INT NOT NULL UNIQUE,
    price DECIMAL(8,2) NOT NULL,

    FOREIGN KEY (author_id) REFERENCES authors(author_id),
    FOREIGN KEY (cat_id) REFERENCES categories(cat_id)
);

INSERT INTO books (book_name, author_id, cat_id, book_number, price)
VALUES
('Software Engineering', 1, 1, 4518, 270),
('Data Structures', 1, 1, 6541, 300);


-- ISSUED BOOKS

CREATE TABLE issued_books (
    issue_id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    user_id INT NOT NULL,
    issue_date DATE NOT NULL,
    return_status ENUM('Issued','Returned') DEFAULT 'Issued',

    FOREIGN KEY (book_id) REFERENCES books(book_id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO issued_books (book_id, user_id, issue_date)
VALUES
(2, 1, '2020-04-22');



