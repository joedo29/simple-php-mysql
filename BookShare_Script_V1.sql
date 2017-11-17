DROP TABLE IF EXISTS transaction_table;
DROP TABLE IF EXISTS item;
DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS admin;
DROP TABLE IF EXISTS users;
drop procedure if exists sp_AddBook;
drop procedure if exists sp_buyBook;
drop procedure if exists sp_searchBook;
drop procedure if exists sp_showSellers;
drop procedure if exists sp_Reports;

-- Create 'users' table 
CREATE TABLE users (
    user_id INT AUTO_INCREMENT NOT NULL,
    user_Name VARCHAR(225),
    user_Password VARCHAR(50),
    email VARCHAR(80),
    city VARCHAR(30),
    state CHAR(5),
    phone VARCHAR(15),
    zip INT,
    signup_date DATETIME,
    isAdmin INT,-- limited to userid 1 being admin do we still need this 
    PRIMARY KEY (user_id)
);

-- Insert data into 'users' table 
load data local infile'~/Downloads/data/user.txt'INTO table users;

/*
This sp is run when a user requests to sign up into the database
Collect all user inforamtion
This procedure will return 1 if user was sucessfully added into database. This would happen under the condtions that user email doesnt already exist 
and all the required information is entered by the user. 
If the user email is already part of the database, the procedure will return 0 on which, the UI will inform the user that this email has already registered nd is part of DB 
*/

drop procedure if exists sp_signup;

-- allow dups for user name? yes ? aviod complicity 
delimiter //
create procedure sp_signup (in var_userName VARCHAR(225),in  var_Email VARCHAR(80),in var_userPassword varchar(50),
in  var_City VARCHAR(30),in  var_State CHAR(5),in  var_phone VARCHAR(15),in  var_zip INT, in var_signup datetime, in var_isAdmin int, out verification boolean)

begin

SET verification = (select count(*) from users u where u.email=var_Email);-- count if email exists 

if verification = 0 then -- email exists

insert into users (user_Name,email,user_Password,city, state, phone, zip, signup_date, IsAdmin)
values (var_userName,var_Email,var_userPassword,var_City , var_State , var_phone , var_zip,now(), var_isAdmin);

set verification=1; -- user was sucessfully added, return 1 to UI to print message, user added successfully 

end if;

end //

delimiter ;

-- these are sample queries that will run
call sp_signup('Janebye','Livelyjane@yaho.com', 'pwd1','redmomd','wa','384367',98059, now(),0, @verification);



/*
This sp is executed when the user clicks the sign in button on the UI. User is a returning user
We will check that their password and email matches that what we have on record and allow them access
This SP will return 1 if the user input for user email and password matches the information the the database 
Otherwise, if information doesnt match user input, return 0. 
UI will you this return information to print out a message accordingly  
*/

drop procedure if exists sp_signIn;

delimiter //
create procedure sp_signIn (in var_Email VARCHAR(80),in var_userPassword varchar(50), out result boolean)

begin

set result = (select count(*)
from users u
where email=var_email AND user_Password=var_userPassword);

end //

delimiter ;

call sp_signIn('kimlee@yahoo.com','pwd1',@result);

-- select @result;

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
    available_copies INT,
    book_condition VARCHAR(100),
    PRIMARY KEY (item_id),
    FOREIGN KEY (book_id)
        REFERENCES books (book_id),
    FOREIGN KEY (user_id)
        REFERENCES users (user_id)
);

-- Insert data into 'item' table
load data local infile '~/Downloads/data/item.txt' INTO table item;


-- enter a new book for sale
drop procedure if exists sp_AddBook;

/*
This procedure adds a book into our database. The parameter's are recieved from our user(a seller) via the UI.
The procedure handles 2 possible scenarios:
1. If the book being added, doesnt already exist in our database, we ask the user to input all the details about the book( title, author,..)
2. If the book being added, already exists in our database, we auto fill the book's information(title, author..) for the user
This check of whether or not the book information is already present, is done via the isbn_13 of the book as it will always be unique
This store procedure will be executed when the user hits submit button after adding all the information he wants to share about the item he is selling
*/
delimiter //

Create procedure sp_AddBook(in varISBN13 varchar(13), in varISBN10 varchar(10), in varTitle varchar(225),
in varAuthor varchar(225),in varPublisher varchar(225),in varYear_published int, in varBook_subject varchar(100),in user_id int, in price decimal(4,2),
in available_copies int, in book_condition varchar(100))

begin

-- UI will allow user to only enter in isbn feild other feilds will be disabled

SET @varCount = (select count(*) from books b where b.isbn_13=varISBN13);-- count if book is present in table

if @varCount >0 then -- book exists

-- return all data to UI for auto fill of the remaining feilds.
select * from books
where isbn_13=varISBN13;

-- book doesnt exist, no auto fill, other feilds enabled for input
-- if book doesnt already exists within our database, add it to the book's table
else  -- @varCount=0

insert into books (isbn_13,isbn_10,title,author,publisher,year_published,book_subject)
values(varISBN13, varISBN10,varTitle,varAuthor,varPublisher,varYear_published,varBook_subject);

end IF;

-- now book exists in book table (either from above query or from old entries), add book into item table, visible for sale

set @var_bookid= (select book_id from books where books.isbn_13=varISBN13);-- if book exists , add to items table 

-- information passed in by the user(seller) via UI
insert into item(book_id,user_id,price, available_copies,book_condition)
-- values (@var_bookid,1,30.00, 3,'good');
values (@var_bookid,user_id,price, available_copies,book_condition);

end //


delimiter ;

-- Query that runs the sp_AddBook taking information form user and populating database

call sp_AddBook('12345','212125','hello world' ,'mary kate','oxford', 2017,'programming',5,30.00,3,'good');
call sp_AddBook('9999996','5050505056','Intro to Python' ,'Google Publisher','Google', 2017,'programming',8,30.00,3,'never used');
call sp_AddBook('9780321982384','032198238X','Linear Algebra and Its Applications (5th Edition)','David C. Lay','Pearson',2015,'Math',4,32.00,1,'good as new');
call sp_AddBook('9780321982384','032198238X','Linear Algebra and Its Applications (5th Edition)','David C. Lay','Pearson',2015,'Math',3,32.00,1,'good as new');


/*
User's search query , this query is run when the user searches for a specific book via any of the 7 book field
Parameters for this stored procedure will come in from the user via the UI
UI will have one search bar where user(buyer) will enter a keyword(S) for their search
*/
drop procedure if exists sp_searchBook;

delimiter //

Create procedure sp_searchBook(in varRequest varchar(225))

BEGIN

-- select query within search stored procedure to return list of books based on user request

select DISTINCT b.title as Title , b.author as Author, b.isbn_13 as ISBN 
from item i inner join books b ON i.book_id=b.book_id
where b.isbn_13=varRequest OR b.isbn_10=varRequest OR b.title like CONCAT('%', varRequest, '%') or b.author like CONCAT('%', varRequest, '%') or
b.publisher like CONCAT('%', varRequest, '%') or b.year_published=varRequest or b.book_subject like CONCAT('%', varRequest, '%');
-- order by year_published DESC; 

end //

DELIMITER ;

-- Queries to run sp_searchBook based on user's request, sticking to just one string approach 
call sp_searchBook('programming');
call sp_searchBook('9780321982384');-- two different sellers for this book, show 1 book
call sp_searchBook('2013');
call sp_searchBook('ANATOMY AND PHYSIOLOGY');


/*
This store procedure will be executed when the buyer selects 'show more information' button on the UI.
The 'show more information' button will be placed besides each book that will be listed via the sp_searchBook (run when the user searches for the book)
The 'show more information' button will execute sp_showSellers. This will show the user all the available sellers for this book

*/

drop procedure if exists sp_showSellers;
delimiter //
Create procedure sp_showSellers(in varBook_id int)-- parameter should be passed in when the user selects 'Show more info' button of a specific book from the list

begin

-- returns the seller's details for the buyer to contact
select title as Title, user_Name as Seller,price as Price, email as 'Seller Email', phone as 'Seller Phone', available_copies as Qty,city as City, state as State, zip as 'Zip Code'
from books b inner join users u inner join item i ON u.user_id=i.user_id and i.book_id=b.book_id
where b.book_id=varBook_id and i.available_copies <>0; -- only shows books that are available for sale, number of copies >0 

end//

delimiter ;


-- These queries will be run and provide the seller's information
call sp_showSellers(2); -- ONE SELLER FOR THE GIVEN BOOK
call sp_showSellers(18);-- MULTIPLE SELLER FOR THE GIVEN BOOK, ONE 
call sp_showSellers(20);-- NO SELLER FOR THE GIVEN BOOK
call sp_showSellers(4);-- book is part of books table and was an item, but sold out so returns 

CREATE TABLE transaction_table (
    t_id INT AUTO_INCREMENT NOT NULL,
    item_id INT NOT NULL,
    buyer_Userid INT NOT NULL,
    t_date DATETIME,
    sold_UserId INT,
    PRIMARY KEY (t_id),
    FOREIGN KEY (item_id)
        REFERENCES item (item_id),
    FOREIGN KEY (buyer_Userid)
        REFERENCES users (user_id)
);

-- load data into transaction_table
load data local infile '~/Downloads/data/transaction.txt' into table transaction_table;


/* NOTE THE FOLLOWING WILL NOT RUN. WE ARE CURRENTLY TRYING TO WORK AROUND AN ISSUE WE ENCOUNTERED WHEN SUING VIEWS WITHING SOTORED PROCEDURES
HOWEVER, INSTEAD OF PASSING THE PARAMETER IF THESE VALUES WERE HARD CODED THE QUERIES WILL RUN PERFECTLY AS REQUIED
THIS NOTE APPLIES TO: SP_BUYBOOK, SP_MYBOOKS, SP_MYSOLDBOOKS AND SP_MYPURCHASES 
/*


/*
This store procedure will run when user hits the buy Book button that will show up with the list of available sellers for a specific book
This will add the buyer_id, the item_id (related to the item he is reuqesting for) and the dateTime of when he sent in the request into the 
transaction table
*/

drop procedure if exists sp_buyBook; 

delimiter // 
Create procedure sp_buyBook(in varItem_id int, in varBuyer_id int)

begin

insert into transaction_table (item_id,buyer_Userid,t_date)
values(varItem_id,varBuyer_id,now());

end//

delimiter ; 

-- these are the queries that will run when the user (buyer) clicks the buy book button on the UI 

-- JOE, ADD 2-3 REQUESTS RELATED TO ONE BOOK, REQUEST FOR 2-3 DIFFRENT ITEMS WHERE SOME ARE FROM THE SAME SELLER (IR SELLER SELLING MULTIPL BOOKS
-- AND SOME ARE FROM JUST A SELLER WHO IS SELLING ONE BOOK 

-- two buyers send in request for this item 1 
call sp_buyBook(1,6);
call sp_buyBook(1,8);

drop procedure if exists sp_myBooks; 
/*This store procudre creates a view for the user(seller) to see what they have up for sale..ALL BOOKS EVEN THOSE W/O TRANS. REQUEST  
seller can see the books they have sold and the books that they havnt yet soled but they have submited for selling 
*/
delimiter //

CREATE PROCEDURE sp_myBooks(in var_UserId int)

begin 

select b.title as Title,b.isbn_13 as 'ISBN 13', i.price as Price, i.available_copies as 'Number of Copies'
from books b INNER JOIN item i 
on b.book_id=i.book_id 
left join 
users u on u.user_id = i.user_id -- where there exists a buyer who is legal, in the db  
where i.user_id=var_UserId; -- the seller id's match between the current user who's account is running and the user who is selling the book  

end//

delimiter ; 

call sp_myBooks(4);-- shows Shaila's books for sale 
call sp_myBooks(3);-- shows Joe's books for sale 
call sp_myBooks(9);
-- When a user requests to see his books that he submited for sale, these queries will run
-- JOE YOU WILL ADD QUESRIES HERE TO RUN THE SP_myBooks 

drop procedure if exists sp_mySoldBooks; 

delimiter //

/*
This store procedure will cretae a view for the user if he wants to see ONLy the books that he has actually sold 
*/
create procedure sp_mySoldBooks (in var_UserId int)

begin 

select DISTINCT b.title as Title,b.isbn_13 as 'ISBN 13', i.price as Price, i.available_copies as 'Number of Copies',
t.t_date as 'Date of Transaction', t.sold_UserId as 'Sold to', u.user_name as 'Buyer Name'
from books b INNER JOIN item i 
on b.book_id=i.book_id 
left join transaction_table t
on i.item_id=t.item_id left join 
users u on u.user_id = t.sold_Userid
where i.user_id=var_UserId AND t.sold_UserId is not null AND t.buyer_Userid=t.sold_UserId -- there exists a buyer 
order by t.t_date desc;

end//

delimiter ;

call sp_mySoldBooks(9);
call sp_mySoldBooks(5);

drop procedure if exists sp_myPurchases; 

delimiter //

/*
This store procedure will creates a view for the user if he wants to see the books he/she has purchased  
*/
create procedure sp_myPurchases(in var_UserId int)

begin 
 
select b.title as Title,b.isbn_13 as 'ISBN 13', i.price as Price,
t.t_date as 'Date of Transaction', u.user_name as 'Seller Name'
from books b INNER JOIN item i 
on b.book_id=i.book_id 
inner join transaction_table t
on i.item_id=t.item_id inner join 
users u on u.user_id = i.user_id
where  t.sold_userId=var_UserId AND t.buyer_userId=var_UserId  -- SYNTAX ERROR HERE: WE NEED TO PASS IN VAR_USERID, need help 
order by t.t_date desc; 

end//


call sp_myPurchases(7);
call sp_myPurchases(8);
call sp_myPurchases(10);


-- joe you will have to tst this out and see if it executes, other wise we can just leave it for now.. 
-- im not sure if the user name feild will out put the user who sold the book 


drop procedure if exists sp_updateCopies 
/*
This procedure updates the number of copies. This update will be done manually throught the UI. And when done, this sp_updateCopies will run
*/
delimiter //
create procedure sp_updateCopies (in var_itemId int)
begin 
update item i set i.available_copies=i.available_copies-1
where i.item_id = var_itemId;
end //

delimiter ;

-- these queries will run when the seller updates the number of copies after he has sold a book
-- books that have been sold as per our transaction table 
call sp_updateCopies(1);
call sp_updateCopies(2);
call sp_updateCopies(4);
call sp_updateCopies(8);

/*
This sp updates the sold User to the user Id of that buyer to whom the seller decided to sell the book 
The buyer_id will be sent in from the UI when the seller selects an approved button on the trasnaction request that he recieves from 
a specific buyer 
*/

drop procedure if exists sp_updateSold_UserId

delimiter //

create procedure sp_updateSold_UserId(in var_BuyerId int)

begin 
update transaction_table t set t.sold_UserId=var_BuyerId
where t.buyer_Userid is not null; -- there is a buyer for it , request was sent in 
end //

delimiter ; 

-- a real call to run this sp cannot be shown here currently as it will only be run in connection to the ui when the seller chooses to accept a buyer

/*
The following queries satisfy our client's requirements. For our system we will assign only ONE ADMIN. The user is part of our 
user's table and has the user id 1. This user is currently not part of any sales just for simplicity
*/

/*when sign in button will be hit and the user will enter their information,sign in will return a boolean value, if the boolean value is true
 this means the user is an Admin. The user will have a speacial button appear for him that will say "Report". 
 On selecting the report button the follow sp will be executed. 
 */


drop procedure if exists sp_Reports 

delimiter //

create procedure sp_Reports (in var_adminID int, in var_dateFrom datetime, in var_dateTo datetime, out number_booksSold int)

begin 

if var_adminID=1 then 
-- only if adminID is 1 the following will be executed 

-- create report 1, number of books sold within a week , will return a number as output that will be printed on UI 

set number_booksSold=(select count(*)
from transaction_table t
where t.sold_UserId is NOT null and t.t_date between var_dateFrom  and var_dateTo);

-- create report 2, to return users who signed up on a certain day or between 2 given dates 

SELECT 
    user_name AS 'Name', signup_date AS 'Date of Sign up'
FROM
    users
WHERE
    signup_date BETWEEN var_dateFrom AND var_dateTo
ORDER BY signup_date DESC;-- most recent to oldest 

SELECT 
    b.book_subject AS 'Book Subject',
    COUNT(b.book_subject) AS 'Number of Books',
    SUM(i.price) AS 'Total Revenue'
FROM
    transaction_table t
        INNER JOIN
    item i ON t.item_id = i.item_id
        INNER JOIN
    books b ON i.book_id = b.book_id
WHERE
    t.t_date BETWEEN var_dateFrom AND var_dateTo
GROUP BY (b.book_subject) ASC;

-- create report 4, returns all the books that are available for sale, havnt been sold yet 

SELECT 
    b.title AS 'Books available for sale',
    i.available_copies AS 'Number of Copies'
FROM
    item i
        INNER JOIN
    books b ON i.book_id = b.book_id
WHERE
    i.available_copies > 0
ORDER BY i.available_copies;
end if;
end // 

delimiter ; 

call sp_Reports(1,'2017-02-10 00:00:00','2017-05-14 00:00:00',@number_booksSold);-- admin id always has to be 1 

