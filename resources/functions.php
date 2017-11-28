<?php  


function set_message($message){

   if($message){
       $_SESSION['message'] = $message;
   }

}

function display_message(){
    if(isset($_SESSION['message'])){
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}

function checklogin(){
  if(! isset($_SESSION['user_email'])){
      set_message("Please Sign in");
      redirect("SignIn.php");
  }
}


function redirect($location){
  header("Location: $location");
}

function query($string){
   global $connection;

    $query = mysqli_query($connection,$string) or die(" An error occured :)".mysqli_error($connection));

    return $query;
}

function fetch_array($result){
    return mysqli_fetch_array($result);
}

function confirm($result){
    if(!$result){
        die("Something wrong happened with your query :( !");
    }
}

function signup(){
    if(isset($_POST['submit'])){
   var_dump ($_POST);

global $connection;



$username = $_POST['user_Name'];
$password = $_POST['user_password'];
$email = $_POST['email'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip =$_POST['zip'];
$phone = $_POST['phone'];

$checkuser = query("SELECT * FROM BookShare_test.users WHERE email = '{$email}'");
$rows = mysqli_num_rows($checkuser); 
if($rows != 0){
    set_message("User is already registered! Please Sign in.");
      redirect("SignIn.php");
}
else{
   $string = "insert into BookShare_test.users (user_Name,email,user_Password,city, state, phone, zip, signup_date, IsAdmin)
values ('{$username}','{$email}','{$password}','{$city}' , '{$state}' , '{$zip}' , '{$phone}',now(), 0)";

$result = query($string);

confirm($result);

if($result){
    set_message("You have been successfully registered. Please go ahead and sign in.");
    redirect("index.php");
}
else{
    set_message("There was an error in the registration. Please try again!");
    redirect("SignIn.php");
}
}



    }
}


function signin(){
    if(isset($_POST['submit'])){
        $email = $_POST['email'];
        $password = $_POST['password'];
        $query = query("select email , user_id from BookShare_test.users where email='{$email}' AND user_Password='{$password}'");
        $row = fetch_array($query);
        $user_email = $row['email'];
        
        if(!$user_email){
           // set_message("The count is ".$count);
            set_message("Email/Password incorrect. Please try again");
            redirect("signin.php");
        }else
        {
            $_SESSION['user_email'] = $email;
            $_SESSION['user_id'] = $row['user_id'];
            redirect("index.php");
        }
    }
}

function addbook(){
    checklogin();
 if(isset($_POST['submit'])){
   //var_dump ($_POST);

global $connection;

$isbn13 = $_POST['isbn13'];
$isbn10 = $_POST['isbn10'];
$title = $_POST['title'];
$author = $_POST['author'];
$publisher = $_POST['publisher'];
$year_published = $_POST['year_published'];
$book_subject = $_POST['book_subject'];
$price = $_POST['price'];
$book_condition = $_POST['book_condition'];

 $querystep = "select book_id from BookShare_test.books where books.isbn_13='{$isbn13}'";

 $book_id = fetch_array(query($querystep))['book_id'];  

if(!$book_id){
   $querystep1 = "insert into BookShare_test.books (isbn_13,isbn_10,title,author,publisher,year_published,book_subject)
values('{$isbn13}', '{$isbn10}','{$title}','{$author}','{$publisher}','{$year_published}','{$book_subject}');
" ;
  $insert_book = query($querystep1);
 if(!$insert_book){
      set_message("Your book was not added! :( ");
        redirect("addbook.php");
 }

  
}
   
    $querystep2 = "select book_id from BookShare_test.books where books.isbn_13='{$isbn13}'";
    $book_id = fetch_array(query($querystep2))['book_id'];  
    $user_id = $_SESSION['user_id'];
    $querystep3 = "insert into BookShare_test.item(book_id,user_id,price,available_copies,book_condition)
    values ('{$book_id}','{$user_id}','{$price}','1','{$book_condition}')";
    $insert_item = query($querystep3);

    if($insert_item){
        set_message("You have successfully added a book!");
        redirect("index.php");
    }
    else{
         set_message("Something wrong happened with item insertion! :( ");
   }
}
}

function display_books(){
    $query = query("SELECT * FROM BookShare_test.books");
    confirm($query);
    while($row = fetch_array($query)){
       $product = <<<DELIMITER
                        <div class="col-sm-4 col-lg-4 col-md-4">
                        <div class="thumbnail">
                            <div class="caption">
                                <h5><a href="#">{$row['title']}</a></h5>
                                <h6><a href="#">{$row['author']}</a></h6>
                                <p>Click on "View Sellers" to see available sellers for this book.</p>
                            </div>
                             <a class="btn btn-primary" href="viewsellers.php?id='{$row['book_id']}'">View sellers</a>
                        </div>
                    </div>
DELIMITER;

echo $product;
}
}


function searchbook(){

    if(isset($_GET['search'])){
       // global $connection;
        $searchString = trim($_GET['search']);
        $queryString = "select book_id, title , author,isbn_13 ";
        $queryString .= "from BookShare_test.books where isbn_13='{$searchString}' OR isbn_10='{$searchString}' ";
        $queryString .= "OR title like CONCAT('%', '{$searchString}', '%') or author like ";
        $queryString .= "CONCAT('%', '{$searchString}', '%') or publisher like ";
        $queryString .= "CONCAT('%', '{$searchString}', '%') or year_published='{$searchString}' ";
        $queryString .= "or book_subject like CONCAT('%', '{$searchString}', '%')";
        $query = query($queryString);
        confirm($query);
        $rows = mysqli_num_rows($query); 
        if($rows ==0){
            set_message("Sorry we couldn't find your book!");
        }
        else{
     while($row = fetch_array($query)){
        $product = <<<DELIMITER
                        <div class="col-sm-4 col-lg-4 col-md-4">
                        <div class="thumbnail">
                            <div class="caption">
                                <h4><a href="#">{$row['title']}</a></h4>
                                <h6><a href="#">{$row['author']}</a></h6>
                                <p>This is a short description. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                            </div>
                             <a class="btn btn-primary" href="viewsellers.php?id='{$row['book_id']}'">View sellers</a>
                        </div>
                    </div>
DELIMITER;

echo $product;

        }
   
    }
}
}

function viewsellers(){
     checklogin();
    //echo $_GET['id'];
    $bookid = $_GET['id'];
    //$userid= $_SESSION['user_id'];

    $queryString = "select item_id, title, user_Name ,price , email , phone , available_copies ,city ,
     state , zip from BookShare_test.books b inner join BookShare_test.users u inner join BookShare_test.item i 
     ON u.user_id=i.user_id and i.book_id=b.book_id
where b.book_id={$bookid} and i.available_copies>0";
    $query = query($queryString);
    $rows = mysqli_num_rows($query); 
   
   //  fetch_array($query);
      while($row = fetch_array($query)){
            $product = <<<DELIMITER
    <tr>
    <td><input type='checkbox' class='checkthis' /></td>
    <td>{$row['title']}</td>
    <td>{$row['user_Name']}</td>
    <td>{$row['price']}</td>
    <td>{$row['email']}</td>
    <td>{$row['phone']}</td>
    <td>{$row['available_copies']}</td>
    <td>{$row['city']}</td>
    <td>{$row['state']}</td>
    <td>{$row['zip']}</td>
    <td></td>
    <td><p data-placement="top" data-toggle="tooltip" title="Buy"> <a href="viewRequest.php?itemid={$row['item_id']}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-ok"></span> buy</a></p></td>
    </tr>
   
DELIMITER;
 echo $product;

      }
     
}

 function purchaseRequest($itemid){
      checklogin();
     $item_id = $itemid;
     $user_id = $_SESSION['user_id'];
     if($user_id){
     $queryString = "insert into BookShare_test.transaction_table (item_id,buyer_Userid,t_date,isAccepted)
values('{$item_id}','{$user_id}',now(),0)";
  $query = query($queryString);
   confirm($query);
   set_message("You have successfully sent a request");
   redirect("viewRequest.php");
     }
     else{
        set_message("Please Sign in before making a purshase");
        redirect("SignIn.php");

     }
     

 }

 function  displayrequests(){
      checklogin();
  $user_id = $_SESSION['user_id'];
 $queryString = "select distinct b.title as Title,b.isbn_13 as 'ISBN 13', i.price as Price, t.t_date as 'Date of Transaction', t.isAccepted as 'status',u.user_name as 'Seller Name'
from BookShare_test.books b INNER JOIN BookShare_test.item i on b.book_id=i.book_id 
inner join BookShare_test.transaction_table t on i.item_id=t.item_id 
inner join BookShare_test.users u on u.user_id = i.user_id
where  t.buyer_Userid='{$user_id}'"; 
$query = query($queryString);
 while($row = fetch_array($query)){
      if($row['status'] == 0){
        $status="Pending";
     }
     else if($row['status'] == 1){
         $status="Approved";
     }
     else{
         $status="Denied";
     }
            $product = <<<DELIMITER
    <tr>
    <td><input type='checkbox' class='checkthis' /></td>
    <td>{$row['Title']}</td>
    <td>{$row['ISBN 13']}</td>
    <td>{$row['Price']}</td>
    <td>{$row['Date of Transaction']}</td>
    <td>{$row['Seller Name']}</td>
    <td>{$status}</td>
    </tr>
   
DELIMITER;
 echo $product;
 }
 }


function viewbooks(){
     //echo $_GET['id'];
     checklogin();
    $userid= $_SESSION['user_id'];
    $queryString = "select  distinct b.isbn_13,i.item_id, i.book_id,b.isbn_10,b.title,b.author,b.publisher,b.year_published,b.book_subject,i.available_copies,i.price,i.book_condition
from BookShare_test.books b INNER JOIN BookShare_test.item i on b.book_id=i.book_id 
where  i.user_id='{$userid}' AND i.available_copies>0";
    $query = query($queryString);
    $rows = mysqli_num_rows($query); 
   
   //  fetch_array($query);
      while($row = fetch_array($query)){
            $product = <<<DELIMITER
    <tr>
    <td><input type='checkbox' class='checkthis' /></td>
    <td>{$row['isbn_13']}</td>
    <td>{$row['isbn_10']}</td>
    <td>{$row['title']}</td>
    <td>{$row['author']}</td>
    <td>{$row['publisher']}</td>
    <td>{$row['year_published']}</td>
    <td>{$row['book_subject']}</td>
    <td>{$row['available_copies']}</td>
    <td>{$row['price']}</td>
    <td>{$row['book_condition']}</td>
    <td><a href="updatebook.php?id={$row['book_id']}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-pencil"></span> Edit</a></td>
    <td><a href="delete.php?item_id={$row['item_id']}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-trash"></span> Delete</a></td>
    </tr>
   
DELIMITER;
 echo $product;

      }
}


function getupdatebook($id){
 checklogin();
  $queryString = "select * from BookShare_test.books where book_id ='{$id}'";
   $query = query($queryString);
   $rows = mysqli_num_rows($query); 
   return fetch_array($query);
}

function updatebook(){
if(isset($_POST['submit'])){
   //var_dump ($_POST);

global $connection;
$bookid = $_POST['book_id'];

$isbn13 = $_POST['isbn13'];
$isbn10 = $_POST['isbn10'];
$booktitle = mysqli_real_escape_string($connection,$_POST['title']);
$author = $_POST['author'];
$publisher = $_POST['publisher'];
$year_published = $_POST['year_published'];
$book_subject = $_POST['book_subject'];
$price = $_POST['price'];
$book_condition = $_POST['book_condition'];
 $querystep = "update BookShare_test.books set isbn_13='{$isbn13}',isbn_10='{$isbn10}',title='{$booktitle}', author='{$author}', publisher='{$publisher}', year_published='{$year_published}'
, book_subject='{$book_subject}' where book_id='{$bookid}'";
echo $booktitle;
  $insert_book = query($querystep);
  confirm($insert_book);
 if(!$insert_book){
      set_message("Your book was not added! :( ");
      redirect("addbook.php");
 }
    
    $user_id = $_SESSION['user_id'];
    $querystep2 = "update BookShare_test.item set price='{$price}',book_condition='$book_condition' where book_id='{$bookid}'";
    $insert_item = query($querystep2);
    if($insert_item){
        set_message("You have successfully updated a book!");
       redirect("index.php");
    }
    else{
         set_message("Something wrong happened with item insertion! :( ");
   }
}
}

function deletebook($itemid){
     checklogin();
    $queryString = "update BookShare_test.item i set i.available_copies=0 where i.item_id='{$itemid}'";
     $query = query($queryString);
    confirm($query);
    if($query){
        set_message("You successfully deleted an item");
        
    }
    else{
        set_message("Item was not deleted, please try again");
    }
    redirect("books.php");
}


function viewsrequests(){
     checklogin();
    $userid= $_SESSION['user_id'];
    $queryString = "select b.title as Title,i.item_id,b.isbn_13 as 'ISBN_13', i.price as Price, t.buyer_UserId, buyer_request.user_name as 'Requested_by'
from BookShare_test.books b INNER JOIN BookShare_test.item i on b.book_id=i.book_id 
left join  BookShare_test.users u on u.user_id = i.user_id
left join BookShare_test.transaction_table t on t.item_id=i.item_id
left join BookShare_test.users buyer_request on buyer_request.user_id = t.buyer_userId 
where i.user_id='{$userid}' and i.available_copies =1 and t.isAccepted=0";
 $query = query($queryString);
 while($row = fetch_array($query)){
            $product = <<<DELIMITER
    <tr>
    <td><input type='checkbox' class='checkthis' /></td>
    <td>{$row['Title']}</td>
    <td>{$row['ISBN_13']}</td>
    <td>{$row['Price']}</td>
    <td>{$row['Requested_by']}</td>
    <td><a href="approveSale.php?buyerId={$row['buyer_UserId']}&&itemId={$row['item_id']}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-check"></span> Approve</a></td>
   
    </tr>
   
DELIMITER;
 echo $product;

      }
}

function viewssales(){
     checklogin();
    $userid= $_SESSION['user_id'];
    $queryString = "select b.title as Title,i.item_id,b.isbn_13 as 'ISBN_13', i.price as Price, t.buyer_UserId, buyer_request.user_name as 'Requested_by'
from BookShare_test.books b INNER JOIN BookShare_test.item i on b.book_id=i.book_id 
left join  BookShare_test.users u on u.user_id = i.user_id
left join BookShare_test.transaction_table t on t.item_id=i.item_id
left join BookShare_test.users buyer_request on buyer_request.user_id = t.buyer_userId 
where i.user_id='{$userid}' and t.isAccepted=1";
 $query = query($queryString);
 while($row = fetch_array($query)){
            $product = <<<DELIMITER
    <tr>

    <td>{$row['Title']}</td>
    <td>{$row['ISBN_13']}</td>
    <td>{$row['Price']}</td>
    <td>{$row['Requested_by']}</td>
    <td>Sold</td>
   
    </tr>
   
DELIMITER;
 echo $product;

      }
}

function approvesale($buyerid,$itemid){
     checklogin();
 $queryString1="update BookShare_test.item i set i.available_copies=0,sold_UserId ='{$buyerid}', sold_date=now()
where i.item_id = '{$itemid}'";
$query1 = query($queryString1);
if($query1){
    $queryString2="update BookShare_test.item i set i.sold_UserId='{$buyerid}'
    where i.item_id='{$itemid}'";
    $query2 = query( $queryString2);

    if($query2){
        $queryString3="update BookShare_test.transaction_table t set isAccepted=1
        where  item_id='{$itemid}' and buyer_Userid={$buyerid}";
        $query3 = query( $queryString3);

        if($query3){
            $queryString4 = "update BookShare_test.transaction_table t set isAccepted=2
            where  item_id='{$itemid}' and buyer_Userid<>{$buyerid}";
            $query4 = query( $queryString4);
            confirm($query4);
            set_message("You successfully sold an item! :D ");
        }
        else{
            set_message("Could not update other decline offer to other buyers. Try again");
           
        }
    }
    else{
        set_message("Could not set sold to user_id");
    }
}
else{
   set_message("Could not update available number of books. Try again");
}
 redirect("sales.php");
}


function showusers(){

    $stringQuery = "SELECT * FROM BookShare_test.users";

    $query = query($stringQuery);
    while($row = fetch_array($query)){
            $product = <<<DELIMITER
    <tr>
 
    <td>{$row['user_Name']}</td>
    <td>{$row['signup_date']}</td>   
    </tr>
   
DELIMITER;
 echo $product;

      }
}

function showPartialusers($startdate, $enddate){
    $stringQuery = "SELECT user_name AS 'Name', signup_date AS 'Date_of_Sign up'
FROM BookShare_test.users WHERE signup_date BETWEEN '{$startdate}' AND '{$enddate}' ORDER BY signup_date DESC";

    $query = query($stringQuery);
    while($row = fetch_array($query)){
            $product = <<<DELIMITER
    <tr>
  
    <td>{$row['Name']}</td>
    <td>{$row['Date_of_Sign up']}</td>   
    </tr>
   
DELIMITER;
 echo $product;

      }


}


function showbooks(){

    $stringQuery = "select * from BookShare_test.item i 
    where i.sold_UserId is NOT null";

    $query = query($stringQuery);
    return $rows = mysqli_num_rows($query); 
}

function showPartialbooks($startdate, $enddate){
    $stringQuery = "select * from BookShare_test.item i 
    where i.sold_UserId is NOT null and i.sold_date between '{$startdate}' and '{$enddate}' ";

    $query = query($stringQuery);
    return $rows = mysqli_num_rows($query); 
}

function allbooks(){
 $queryString = "select title as 'Title', user_Name as 'Seller',price as 'Price', email as 'Seller_Email', phone as 'Seller_Phone', available_copies as 'Qty',city as 'City', state as 'State', zip as 'Zip_Code'
from BookShare_test.books b inner join BookShare_test.users u inner join BookShare_test.item i ON u.user_id=i.user_id and i.book_id=b.book_id";

$query = query($queryString);
 while($row = fetch_array($query)){
            $product = <<<DELIMITER
    <tr>

    <td>{$row['Title']}</td>
    <td>{$row['Seller']}</td>
    <td>{$row['Price']}</td>
    <td>{$row['Seller_Email']}</td>
    <td>{$row['Seller_Phone']}</td>
    <td>{$row['Qty']}</td>
    <td>{$row['City']}</td>
    <td>{$row['State']}</td>
      <td>{$row['Zip_Code']}</td>
   
    </tr>
   
DELIMITER;
 echo $product;

      }


}

?>