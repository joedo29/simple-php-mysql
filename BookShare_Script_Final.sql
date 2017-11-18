DROP TABLE IF EXISTS transaction_table;
DROP TABLE IF EXISTS item;
DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS admin;
DROP TABLE IF EXISTS users;
drop procedure if exists sp_AddBook;
drop procedure if exists sp_buyBook;
drop procedure if exists sp_searchBook;
drop procedure if exists sp_showSellers;
drop procedure if exists sp_myBooks;
drop procedure if exists sp_myPurchases;
drop procedure if exists sp_mySoldBooks;
drop procedure if exists sp_markSold; 
drop procedure if exists sp_Reports;
drop procedure if exists sp_signUp;
drop procedure if exists sp_signIn;

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
    isAdmin INT,-- limited to userid 1 being admin 
    PRIMARY KEY (user_id)
);

-- Insert data into 'users' table 
load data local infile'~/Downloads/data/user.txt'INTO table users;

/*
-This sp is run when a user requests to register into the database
-Collects all user inforamtion
-This procedure will return 1 if user was sucessfully added into database. 
	This would happen under the condtions that user email doesnt already exist and all the required information is entered by the user. 
	If the user email is already part of the database, the procedure will return 0 on which, the UI will inform the user that this email 
    has already registered and should proceed to sign in
*/

drop procedure if exists sp_signUp;


delimiter //
create procedure sp_signUp (in var_userName VARCHAR(225),in  var_Email VARCHAR(80),in var_userPassword varchar(50),
in  var_City VARCHAR(30),in  var_State CHAR(5),in  var_phone VARCHAR(15),in  var_zip INT, in var_signup datetime, in var_isAdmin int, out verification boolean)

begin

SET verification = (select count(*) from users u where u.email=var_Email);-- count if email exists 

if verification = 0 then -- email doesnt  exists

insert into users (user_Name,email,user_Password,city, state, phone, zip, signup_date, IsAdmin)
values (var_userName,var_Email,var_userPassword,var_City , var_State , var_phone , var_zip,now(), var_isAdmin);

set verification=1; -- user was sucessfully added, return 1 to UI to print message, user added successfully 

end if;
-- if verification hasnt been set to 1, i.e. verification =0, then user already exists. This result will be evaluated in PHP code and will print a message accordlingly
end //

delimiter ;

-- these are sample queries that will run, by default isAdmin always 0. Our dummy data will have userID 1 set to 1 for isAdmin
call sp_signUp('Jane','Livelyjane@yahoo.com', 'pwd1','Redmond','WA','384367',98059, now(),0, @verification);
call sp_signUp('Sherlock','sherlockholmes@gamil.com', 'sherlock','Redmond','WA','384367',98059, now(),0, @verification);

/*
-This sp is executed when the user clicks the sign in button on the UI. User is a "returning user"
-We will check that their password and email matches that what we have on record and allow them access
-This SP will return 1 if the user input for user email and password matches the information the the database 
	Otherwise, if information doesnt match user input, return 0. 
-UI will you this return information to print out a message accordingly  
*/

drop procedure if exists sp_signIn;

delimiter //
create procedure sp_signIn (in var_Email VARCHAR(80),in var_userPassword varchar(50), out success boolean)

begin

set success = (select count(*)
from users u
where email=var_email AND user_Password=var_userPassword);

end //

delimiter ;

call sp_signIn('kimlee@yahoo.com','pwd1',@success);-- will fail cause doesnt exists in DB, return 0 
call sp_signIn('sherlockholmes@gamil.com','sherlock',@success);-- will match records on db, return 1 


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

load data local infile '~/Downloads/data/item.txt' INTO table item;-- Insert data into 'item' table, no books are sold yet 

-- enter a new book for sale
drop procedure if exists sp_addBook;
/*
-This procedure adds a book into our database. The parameter's are recieved from our user(a seller) via the UI.-
-The procedure handles 2 possible scenarios:
1. If the book being added, doesnt already exist in our database, we ask the user to input all the details about the book( title, author,..)
2. If the book being added, already exists in our database, we auto fill the book's information(title, author..) for the user
-This check of whether or not the book information is already present, is done via the isbn_13 of the book as it will always be unique
-This store procedure will be executed when the user hits submit button after adding all the information he wants to share about the item he is selling

We will not ask the user for number of available copies. We will hard-code it as 1 by default 
*/
delimiter //

Create procedure sp_addBook(in varISBN13 varchar(13), in varISBN10 varchar(10), in varTitle varchar(225),
in varAuthor varchar(225),in varPublisher varchar(225),in varYear_published int, in varBook_subject varchar(100),in user_id int, in price decimal(4,2),in book_condition varchar(100))

begin

-- UI will allow user to only enter in isbn feild other feilds will be disabled

SET @exist = (select count(*) from books b where b.isbn_13=varISBN13);-- count if book is present in table

if @exist > 0 then -- book exists

-- return all data to UI for auto fill of the remaining feilds.
select * from books
where isbn_13=varISBN13;

-- book doesnt exist, no auto fill, other feilds enabled for input
-- if book doesnt already exists within our database, add it to the book's table
else  -- @exist=0

insert into books (isbn_13,isbn_10,title,author,publisher,year_published,book_subject)
values(varISBN13, varISBN10,varTitle,varAuthor,varPublisher,varYear_published,varBook_subject);

end IF;

-- now book exists in book table (either from above query or from old entries), add book into item table, visible for sale

set @var_bookid= (select book_id from books where books.isbn_13=varISBN13);-- if book exists , add to items table, select the specific book_id  

-- information passed in by the user(seller) via UI
insert into item(book_id,user_id,price,available_copies,book_condition)

values (@var_bookid,user_id,price,1,book_condition);-- note: #copies is hard coded as 1 

end //


delimiter ;

-- Query that runs the sp_AddBook taking information form user and populating database

call sp_addBook('12345','212125','hello world' ,'mary kate','oxford', 2017,'programming',5,30.00,'good');
call sp_addBook('9999996','5050505056','Intro to Python' ,'Google Publisher','Google', 2017,'programming',8,30.00,'never used');
call sp_addBook('9780321982384','032198238X','Linear Algebra and Its Applications (5th Edition)','David C. Lay','Pearson',2015,'Math',4,32.00,'good as new');
call sp_addBook('9780321982384','032198238X','Linear Algebra and Its Applications (5th Edition)','David C. Lay','Pearson',2015,'Math',3,32.00,'good as new');


/*
-User's search query , this query is run when the user searches for a specific book via any of the 7 book fields
-Parameters for this stored procedure will come in from the user via the UI
-UI will have one search bar where user(buyer) will enter a keyword(S) for their search
-The search is a single string search that DOESNT get broken down. We will search for the string exavtly how its enetred and not break it down 
*/
drop procedure if exists sp_searchBook;

delimiter //

Create procedure sp_searchBook(in varRequest varchar(225))

BEGIN

-- select query within search stored procedure to return list of books based on user request

select b.title as Title , b.author as Author, b.isbn_13 as ISBN 
from books b
where b.isbn_13=varRequest OR b.isbn_10=varRequest OR b.title like CONCAT('%', varRequest, '%') or b.author like CONCAT('%', varRequest, '%') or
b.publisher like CONCAT('%', varRequest, '%') or b.year_published=varRequest or b.book_subject like CONCAT('%', varRequest, '%');

end //

DELIMITER ;

-- Queries to run sp_searchBook based on user's request, sticking to just one string approach 
call sp_searchBook('programming');
call sp_searchBook('9780321982384');-- two different sellers for this book, show 1 book
call sp_searchBook('2013');
call sp_searchBook('ANATOMY AND PHYSIOLOGY');


/*
- This store procedure will be executed when the buyer selects 'show more information' button on the UI.
- The 'show more information' button will be placed besides each book that will be listed via the sp_searchBook (run when the user searches for the book)
- The 'show more information' button will execute sp_showSellers. This will show the seller's email, price, phone# , location for this book
- besides every seller detail will be a button "BUY" a buyer will execute this button when they are ok to buy the book from the seller 
*/

drop procedure if exists sp_showSellers;
delimiter //
Create procedure sp_showSellers(in varBook_id int)-- parameter should be passed in from UI when the user selects 'Show more info' button of a specific book from the list

begin

-- returns the seller's details for the buyer to contact
select title as Title, user_Name as Seller,price as Price, email as 'Seller Email', phone as 'Seller Phone', available_copies as Qty,city as City, state as State, zip as 'Zip Code'
from books b inner join users u inner join item i ON u.user_id=i.user_id and i.book_id=b.book_id
where b.book_id=varBook_id and i.available_copies <>0; -- only shows books that are 'available' for sale, number of copies >0 

end//

delimiter ;


-- These queries will be run and provide the seller's information
call sp_showSellers(2); -- ONE SELLER FOR THE GIVEN BOOK
call sp_showSellers(18);-- MULTIPLE SELLER FOR THE GIVEN BOOK, ONE 
call sp_showSellers(20);-- NO SELLER FOR THE GIVEN BOOK

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

-- load data into transaction_table
load data local infile '~/Downloads/data/transaction.txt' into table transaction_table;



/*
This store procedure will run when user hits the 'buy Book' button that will show up with the list of available sellers for a specific book
This will add a 'transaction' to the transaction table. It will take in the book_id of the book and the user id of the buyer who is sending in the resquest for the book
This will show the seller that a request has been made for their book. It will give them information about the buyer and the time the request was made
*/

drop procedure if exists sp_buyBook; 

delimiter // 
Create procedure sp_buyBook(in varItem_id int, in varBuyer_id int)

begin

insert into transaction_table (item_id,buyer_Userid,t_date,isAccepted)
values(varItem_id,varBuyer_id,now(),0);-- hard code, default isAccepted is 0 

end//

delimiter ; 

-- these are the queries that will run when the user (buyer) clicks the buy book button on the UI 

-- JOE, ADD 2-3 REQUESTS RELATED TO ONE BOOK, REQUEST FOR 2-3 DIFFRENT ITEMS WHERE SOME ARE FROM THE SAME SELLER (IR SELLER SELLING MULTIPL BOOKS
-- AND SOME ARE FROM JUST A SELLER WHO IS SELLING ONE BOOK 

-- two buyers send in request for this item 8
call sp_buyBook(8,6);
call sp_buyBook(8,8);

drop procedure if exists sp_myBooks; 

/*
-This store procedure allows the user(seller) to see what they have up for sale..ALL BOOKS EVEN THOSE W/O A REQUEST FOR PURCHASE  
seller can see the books they have had 0 or more 'requests' for. 
-The UI will contain a check besides each transaction. There will be 2 check boxes, sold and denied. 
	If the item is sold to a client who was part of one transaction,
	the seller will click sold besides their request and like wise for denied. 
*/

delimiter //

CREATE PROCEDURE sp_myBooks(in var_UserId int)

begin 

select b.title as Title,b.isbn_13 as 'ISBN 13', i.price as Price, t.buyer_UserId, buyer.user_name as 'Buyer Name', t.t_date as 'Date of request'
from books b INNER JOIN item i on b.book_id=i.book_id 
left join  users u on u.user_id = i.user_id
left join transaction_table t on t.item_id=i.item_id
left join users buyer on buyer.user_id = t.buyer_userId -- where there exists a buyer who is legal and has  
where i.user_id=var_UserId; -- the seller id's match between the current user who's account is running and the user who is selling the book  

end//

delimiter ; 

-- When a user requests to see his books that he submited for sale, these queries will run
call sp_myBooks(4);-- shows Shaila's books for sale and transaction requests if any 
call sp_myBooks(3);-- shows Ferdinand's books for sale and transaction requests if any 

drop procedure if exists sp_mySoldBooks; 

delimiter //

/*
- This store procedure will allow the seller to retrive information about  'compeleted' sales transactions he has made for his items 
- On multiple reuqest for one book, the seller will decide which buyer's request he wants to approve. He will select the sold or denied check box and mark 
  who he sold the book to
*/
create procedure sp_mySoldBooks (in var_UserId int)

begin 

select b.title as Title,b.isbn_13 as 'ISBN 13', i.price as Price, t.buyer_UserId, sold_to.user_name as 'Sold to'
from books b INNER JOIN item i on b.book_id=i.book_id 
left join  users u on u.user_id = i.user_id
left join transaction_table t on t.item_id=i.item_id
left join users sold_to on sold_to.user_id = t.buyer_userId -- is a valid person who sent in a request for the book 

where i.user_id=var_UserId and i.available_copies =0 and t.isAccepted=1; -- book is sold i.e. 0 copies left, the transaction was 'accepted' for specific buyer 

end//

delimiter ;
-- no books sold, yet. Have made a comment of when to come back and run this  
call sp_mySoldBooks(9);-- sold 1 book
call sp_mySoldBooks(5);-- sold 2 books 

drop procedure if exists sp_myPurchases; 

delimiter //

/*
This store procedure allows a user to see the purchases he has made on BookShare
*/
create procedure sp_myPurchases(in var_UserId int)

begin 
 
select distinct b.title as Title,b.isbn_13 as 'ISBN 13', i.price as Price, i.sold_date as 'Date of Transaction', u.user_name as 'Seller Name'
from books b INNER JOIN item i on b.book_id=i.book_id 
inner join transaction_table t on i.item_id=t.item_id 
inner join users u on u.user_id = i.user_id
where  i.sold_userId=var_UserId; 

end//

-- no books bought, yet. Will made a comment of when to come back and run this 
call sp_myPurchases(9); -- no books bought
call sp_myPurchases(3);-- bought 2 books 
call sp_myPurchases(7);-- bought 1 book 


drop procedure if exists sp_markSold 
/*
This procedure marks the item as sold but updating it's number of copies to 0, stores the id of the user to whom the book was sold and the date when sale was approved
This procedure also updates the isApproved colomn in the transaction table where the buyerId of the transaction matches the id of buyer who is being sold the book 
Once the seller checks off 'Sold' next to a transaction in his "my books" this procedure will be executed 
*/
delimiter //
create procedure sp_markSold (in var_itemId int, in var_BuyerId int)
begin 

update item i set i.available_copies=i.available_copies-1,sold_UserId =var_BuyerId, sold_date=now()
where i.item_id = var_itemId;

update item i set i.sold_UserId=var_BuyerId -- set sold_id in items table to the id of the buyer who bought the book 
where i.item_id=var_itemId;

update transaction_table t set isAccepted=1 -- update the isAccepted based on
where  item_id=var_itemId and buyer_Userid=var_BuyerId;-- must be for this specific item and specific buyer 

end //

delimiter ;

-- these queries will run when the seller updates the number of copies after he has sold a book
-- books that have been sold as per our transaction table 
call sp_markSold(1,3);-- item 1 sold to user 3 
call sp_markSold(2,2);-- item 2 sold to user 2 
call sp_markSold(3,7);-- item 3 sold to user 7 
call sp_markSold(4,3);-- item 4 sold to user 3 
call sp_markSold(8,6);-- item 8 sold to user 6 

-- you go now run, my soldBooks and my purchasesProcedures

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

-- create report 1, number of books sold within a give dates , will return a number as output that will be printed on UI 

set number_booksSold=(select count(*)
from item i
where i.sold_UserId is NOT null and i.sold_date between var_dateFrom and var_dateTo);-- should return 5 for the current queries we ran 

-- create report 2, to return users who signed up on a certain day or between 2 given dates 

SELECT 
    user_name AS 'Name', signup_date AS 'Date of Sign up'
FROM
    users
WHERE
    signup_date BETWEEN var_dateFrom AND var_dateTo
ORDER BY signup_date DESC;-- most recent to oldest 

-- create report 3, the revenue generated from the sales of books and numbers of books sold based on their subject
SELECT 
    b.book_subject AS 'Book Subject',
    COUNT(b.book_subject) AS 'Number of Books',
    SUM(i.price) AS 'Total Revenue'
FROM
    item i 
        INNER JOIN
    books b ON i.book_id = b.book_id
WHERE
    i.sold_date BETWEEN var_dateFrom AND var_dateTo
GROUP BY (b.book_subject) ASC;

-- create report 3, returns all the books that are available for sale, havnt been sold yet, 3 books are available for sale, rest are sold after running to queries above 

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

call sp_Reports(1,'2017-01-10 00:00:00',now(),@number_booksSold);-- admin id always has to be 1 

select @number_booksSold;-- will be 5 