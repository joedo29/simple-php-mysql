<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body style="background-color:powderblue;">
<form action="/" method="post">
    <table border="0">
        <center><h1> Sailors - Reserves - Boats </h1></center>
        <h3> (Filter by Sailor rating) </h3>
        <tr>
            <td>Sailor Rating</td>
            <td align="center"><input type="text" name="rating" size="30" placeholder="Enter your rating filer"/></td>
            <td> (Enter value 0 - 10 or leave blank)</td>
        </tr>
        <tr>
            <td colspan="2" align="center"><input type="submit" value="Submit"/></td>
        </tr>
    </table>

    <?php

    $rating = $_POST['rating'];
    $age = $_POST['age'];
    $app = require __DIR__.'/../bootstrap/app.php';

    $connection = new \App\DB\Connector\Connection('mariadb', 'joe', 'root', 'root');
    $model = new \App\Model\SailorModel($connection);
    $query = $model->select(['sid', 'sname', 'rating']);
    $query->where('rating', '>', $rating);
    $results = $query->getResults();


    ?>
    <table>
        <tr>
            <th>sid</th>
            <th>sname</th>
            <th>rating</th>
        </tr>
        <?php foreach ($results as $item) { ?>
            <tr>
            <td><?php echo $item['sid'] ?></td>
            <td><?php echo $item['sname'] ?></td>
            <td><?php echo $item['rating'] ?></td>
        </tr>
        <?php } ?>
    </table>
</form>
<hr>

<!--// second form-->
    <form action="/" method="post">
        <table border="0">

            <h3> (Filter by Sailor age) </h3>

            <tr>
                <td>Sailor Age</td>
                <td align="center"><input type="text" name="age" size="30" placeholder="Enter your age filer"/></td>
                <td> (Enter value 0 - 63 or leave blank)</td>
            </tr>

            <tr>
                <td colspan="2" align="center"><input type="submit" value="Submit"/></td>
            </tr>
        </table>


        <?php

        $rating = $_POST['rating'];
        $age = $_POST['age'];
        $app = require __DIR__.'/../bootstrap/app.php';

        $connection = new \App\DB\Connector\Connection('mariadb', 'joe', 'root', 'root');

        $newModel = new \App\Model\SailorModel($connection);
        $query = $model->select(['sname', 'sid', 'age']);
        $query->where('age', '>', $age);
        $results = $query->getResults();


        ?>
        <table>
                    <tr>
                        <th>sid   </th>
                        <th>sname   </th>
                        <th>age   </th>
                    </tr>
            <?php foreach ($results as $item) { ?>
                <tr>
                    <td><?php echo $item['sid'] ?></td>
                    <td><?php echo $item['sname'] ?></td>
                    <td><?php echo $item['age'] ?></td>
                </tr>
            <?php } ?>
        </table>

</form>

<!--Third form-->
<!--<hr>-->
<!--<form action="/" method="post">-->
<!--    <table border="0">-->
<!---->
<!--        <h3> (Filter by Boat color) </h3>-->
<!---->
<!--        <tr>-->
<!--            <td>Boat Color</td>-->
<!--            <td align="center"><input type="text" name="age" size="30" placeholder="Enter your boat color"/></td>-->
<!--            <td> (Enter 'blue', 'red', 'green' or leave blank)</td>-->
<!--        </tr>-->
<!---->
<!--        <tr>-->
<!--            <td colspan="2" align="center"><input type="submit" value="Submit"/></td>-->
<!--        </tr>-->
<!--    </table>-->
<!---->
<!---->
<!--    --><?php
//
//    $color = $_POST['color'];
//    $app = require __DIR__.'/../bootstrap/app.php';
//
//    $connection = new \App\DB\Connector\Connection('mariadb', 'joe', 'root', 'root');
//
//    $newModel = new \App\Model\BoatModel($connection);
//    $query = $model->select(['bid', 'bname', 'color']);
//    $query->where('color', '==', $color);
//    $results = $query->getResults();
//
//
//    ?>
<!--    <table>-->
<!--        <tr>-->
<!--            <th>bid   </th>-->
<!--            <th>bname   </th>-->
<!--            <th>color   </th>-->
<!--        </tr>-->
<!--        --><?php //foreach ($results as $item) { ?>
<!--            <tr>-->
<!--                <td>--><?php //echo $item['bid'] ?><!--</td>-->
<!--                <td>--><?php //echo $item['bname'] ?><!--</td>-->
<!--                <td>--><?php //echo $item['color'] ?><!--</td>-->
<!--            </tr>-->
<!--        --><?php //} ?>
<!--    </table>-->
<!---->
<!--</form>-->
</body>
</html>