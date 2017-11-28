
<?php

 require_once("../resources/config.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>BookShare Homepage - Bellevue College</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/shop-homepage.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

<style>
    #logo{
        height: 290%;
        margin-bottom: -10px;
        margin-top: -19px;
        margin-left: -50px;
    }

  
        /* .navbar-toggle{
            margin-top: 25px;
        } */
    

    </style>

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" style="background-color: #184ead" role="navigation">

   
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <a class="navbar-brand" href="index.php"><img id="logo"  alt= "Website logo" src="logo2.png"></a>

                <a class="navbar-brand" href="index.php"> BookShare </a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php if(isset($_SESSION['user_email'])): ?>
                         <?php 
                            if((int)$_SESSION['user_id']==1):
                         ?>
                        <li>
                            <a href="admin"><span class="glyphicon glyphicon-briefcase"></span>Admin</a>
                            
                        </li>
                        <?php endif; ?>
                        <li>
                            <a href="addbook.php"> <span class="glyphicon glyphicon-plus"></span>Add Book</a>
                        </li>
                        <li>

                            <a href="books.php"><span class="glyphicon glyphicon-eye-open"></span> View Books</a>
                        </li>
                        <li>
                            <a href="viewRequest.php"> <span class="glyphicon glyphicon-book"></span>My purchases</a>
                        </li>
                        <li>
                            <a href="sales.php"> <span class="glyphicon glyphicon-usd"></span>Sales</a>

                        </li>
                    <?php endif; ?>
                
                </ul>

                <ul class="nav navbar-nav navbar-right">
                <?php if(isset($_SESSION['user_email'])): ?>
                    <li><a href="#"><span class="glyphicon glyphicon-user"></span><?php echo $_SESSION['user_email']; ?></a></li>
                    <li><a href="signout.php"><span class="glyphicon glyphicon-log-in"></span>Log out</a></li>
                <?php else:  ?>
                     <li><a href="signup.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                    <li><a href="signin.php"><span class="glyphicon glyphicon-log-in"></span> Sign in</a></li>
                <?php endif;  ?>
     
    </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>