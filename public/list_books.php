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
    </style>
</head>
<body style ="navbutton_background_color: powderblue;">
<div style="text-align: center;"><h1> List of Books </h1></div>

    <?php
    //$ressults

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
                <td><a href="/?action=delete&book_id=<?php echo $item['book_id'] ?>">Delete</a></td>

            </tr>
            <?php
        } ?>
    </table>
<hr>
    <form method="get" action="/">
        <table border="0" id="t10">
            <h3> SEARCH FOR A BOOK </h3>
            <tr>
                <td>ISBN_13</td>
                <td align="center"><input type="text" name="isbn_13" size="30" placeholder="Search by isbn_13"/></td>

            <tr>
            <tr>
                <td>AUTHOR</td>
                <td align="center"><input type="text" name="author" size="30" placeholder="Search by Author"/></td>

            <tr>
            <tr>
                <td>BOOK TITLE</td>
                <td align="center"><input type="text" name="title" size="30" placeholder="Search by Title"/></td>

            <tr>
                <td colspan="2" align="center"><input type="submit" value="Search"/></td>
            </tr>
        </table>
        <input type="hidden" name="action" value="search"/>
    </form>

</body>
</html>