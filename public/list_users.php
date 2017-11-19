<html>
<head>
    <style>
        table#t01 {
            width:100%;
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;

        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        table#t01 tr:nth-child(even) {
            background-color: #eee;
        }
        table#t01 tr:nth-child(odd) {
            background-color:#fff;
        }
        table#t01 th {
            background-color: black;
            color: white;
        }

        table#t10 {
            width: 25%;
            border: 1px solid lightblue;
            border-collapse: collapse;
            background-color: powderblue;
        }

        th {
            text-align: left;
        }
    </style>
</head>
<center><h1> List of Users </h1></center>


<?php
$app = require __DIR__.'/../bootstrap/app.php';

//    $rating = $_POST['rating'];

$connection = new \App\DB\Connector\Connection('mariadb', 'joe', 'root', 'root');
$model = new \App\Model\UserModel($connection);
$query = $model->select(['user_id', 'user_Name', 'user_Password', 'email', 'city', 'state', 'phone', 'zip', 'signup_date', 'isAdmin']);
//    $query->where('rating', '>', $rating);
$results = $query->getResults();

?>
<table id="t01">
    <tr>
        <th>User_id</th>
        <th>user_name</th>
        <th>Email</th>
        <th>city</th>
        <th>State</th>
        <th>Phone</th>
        <th>Zip</th>
        <th>Signup Date</th>


    </tr>
    <?php
    foreach($results as $item){
        ?>

        <tr>
            <td><?php echo $item['user_id'] ?></td>
            <td><?php echo $item['user_Name'] ?></td>

            <td><?php echo $item['email'] ?></td>
            <td><?php echo $item['city'] ?></td>
            <td><?php echo $item['state'] ?></td>
            <td><?php echo $item['phone'] ?></td>
            <td><?php echo $item['zip'] ?></td>
            <td><?php echo $item['signup_date'] ?></td>

        </tr>
        <?php
    } ?>
</table>
<hr>
<form method="post" action="/">
    <table border="0" id="t10">
        <h3> ADD A BOOK </h3>
        <tr>
            <td>ISBN 13</td>
            <td align="center"><input type="text" name="isbn13" size="30" placeholder="Enter your value here"/></td>
        </tr>

        <tr>
            <td>ISBN 10</td>
            <td align="center"><input type="text" name="isbn10" size="30" placeholder="Enter your value here"/></td>
        </tr>

        <tr>
            <td>Title</td>
            <td align="center"><input type="text" name="title" size="30" placeholder="Enter your value here"/></td>
        </tr>

        <tr>
            <td>Author</td>
            <td align="center"><input type="text" name="author" size="30" placeholder="Enter your value here"/></td>
        </tr>

        <tr>
            <td>Publisher</td>
            <td align="center"><input type="text" name="publisher" size="30" placeholder="Enter your value here"/></td>
        </tr>

        <tr>
            <td>Year Published</td>
            <td align="center"><input type="text" name="year" size="30" placeholder="Enter your value here"/></td>
        </tr>

        <tr>
            <td>Book subject</td>
            <td align="center"><input type="text" name="subject" size="30" placeholder="Enter your value here"/></td>
        </tr>

        <tr>
            <td colspan="2" align="center"><input type="submit" value="Add This Book"/></td>
        </tr>
    </table>
    <input type="hidden" name="action" value="addBook"/>
</form>

</html>

