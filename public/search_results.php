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
    </style>
</head>
<body style ="navbutton_background_color: powderblue;">
<!--<img src="logo.png" style="width:128px;height:128px;">-->
<center><h1> Search Results </h1></center>
<?php
$app = require __DIR__.'/../bootstrap/app.php';

$search = $_POST['searchBook'];

$connection = new \App\DB\Connector\Connection('mariadb', 'joe', 'root', 'root');
$model = new \App\Model\BookModel($connection);
$query = $model->select(['book_id', 'isbn_13', 'isbn_10', 'title', 'author', 'publisher', 'year_published', 'book_subject']);
$query->where('title', '==', $search);
$results = $query->getResults();

?>
<table id="t01">
    <tr>
        <th>book_id</th>
        <th>isbn_13</th>
        <th>isbn_10</th>
        <th>Title</th>
        <th>Author</th>
        <th>Publisher</th>
        <th>Year_Published</th>
        <th>Book Subject</th>
    </tr>
    <?php
    foreach($results as $item){
        ?>

        <tr>
            <td><?php echo $item['book_id'] ?></td>
            <td><?php echo $item['isbn_13'] ?></td>
            <td><?php echo $item['isbn_10'] ?></td>
            <td><?php echo $item['title'] ?></td>
            <td><?php echo $item['author'] ?></td>
            <td><?php echo $item['publisher'] ?></td>
            <td><?php echo $item['year_published'] ?></td>
            <td><?php echo $item['book_subject'] ?></td>

        </tr>
        <?php
    } ?>
</table>

</body>
</html>