<?php
 require_once("../resources/config.php");
include(FRONT.DS."header.php");

?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">
              <h4 class="text-center bg-success"><?php display_message(); ?></h4>


            <div class="col-md-9">

                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <h1>Search for a book</h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-md-offset-3">
                            <form action="booksearch.php" class="search-form">
                               
                                <div class="form-group has-feedback">
                                    <label for="search" class="sr-only">Search</label>
                                    <input type="text" class="form-control" name="search" id="search" placeholder="search">
                                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                </div>

                <div class="row">

                    <?php display_books(); ?>

         
                </div>

            </div>

        </div>

    </div>
    <!-- /.container -->

    <div class="container">

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Your Website 2017</p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <script>
    document.onkeydown=function(evt){
        var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
        if(keyCode == 13)
        {
            //your function call here
            document.test.submit();
        }
    }
</script>

</body>

</html>
