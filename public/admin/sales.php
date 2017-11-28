<?php
 require_once("../../resources/config.php");

 ?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>BookShare Admin</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
           <!-- Navigation -->
           <nav class="navbar navbar-inverse navbar-fixed-top" style="background-color: #184ead" role="navigation">
           <!-- Brand and toggle get grouped for better mobile display -->
           <div class="navbar-header">
               <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse" >
                   <span class="sr-only">Toggle navigation</span>
                   <span class="icon-bar"></span>
                   <span class="icon-bar"></span>
                   <span class="icon-bar"></span>
               </button>
               <a class="navbar-brand" href="../index.php">BookShare</a>
           </div>
           <!-- Top Menu Items -->
           
           <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
           <div class="collapse navbar-collapse navbar-ex1-collapse" >
             <ul class="nav navbar-nav side-nav" style="background-color: #184ead">
                   <li>
                       <a href="index.php"><i class="fa fa-fw fa-dashboard"> </i> Dashboard</a>
                   </li>
                   <li class="active">
                           <a href="sales.php"><i class="fa fa-money" ></i> Total Sales</a>
                   </li>
           
               
                   <li>
                       <a href="users.php"><i class="fa fa-users"></i> Registrations</a>
                   </li>
               
               </ul>
           </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

             <div class="row">

<h1 class="page-header">
   Total Sales

</h1>
<form class="form-inline" action="" method="post">
                            <div class="form-group">
                                <label for="exampleInputName2">Start date</label>
                                <input type="date" name="startdate" class="form-control" id="exampleInputName2" placeholder="Jane Doe">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail2">End date</label>
                                <input type="date" name="enddate" class="form-control" id="exampleInputEmail2" placeholder="jane.doe@example.com">
                            </div>
                            <button type="submit" class="btn btn-default">Search</button>
                        </form>
<table class="table table-hover">


    <thead>

      <tr>
           <th>Number of Books Sold within a given period</th>
           
      </tr>
    </thead>
    <tbody>
       <?php 
            $numbersold=0;
                                if(isset($_POST['startdate']) && isset($_POST['enddate'])){
                                    $start = $_POST['startdate'];
                                    $end = $_POST['enddate'];
                                    if($start != null && $end != null){
                                        $numbersold = showPartialbooks($start,$end);
                                    }else{
                                      $numbersold =  showbooks();
                                    }
                                    
                                }
                                else{
                                    $numbersold = showbooks();
                                }
                                ?>
       <tr>
            <td><?php echo $numbersold; ?></td>
       </tr>

                                    
                                    


  </tbody>
</table>

             </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="js/plugins/morris/raphael.min.js"></script>
    <script src="js/plugins/morris/morris.min.js"></script>
    <script src="js/plugins/morris/morris-data.js"></script>

</body>

</html>
