<?php 
 require_once("../resources/config.php");
include (FRONT.DS."header.php");

?>


<div class="container">
    <h1 class="well"><span class="glyphicon glyphicon-plus"></span>Add a book</h1>
	<div class="col-lg-12 well">
	<div class="row">
	             <h4 class="text-center bg-warning"><?php display_message(); ?></h4>
				<form action="" method="post" >
				    <?php addbook(); ?>

					<div class="col-sm-12">
						<div class="row">
							<div class="col-sm-6 form-group">
								<label>ISBN 13</label>
								<input type="text" name="isbn13" placeholder="Enter ISBN 13.." class="form-control" required>
							</div>
							<div class="col-sm-6 form-group">
								<label>ISBN 10</label>
								<input type="text" name="isbn10" placeholder="Enter ISBN 10 .." class="form-control" required>
							</div>
						</div>	
                        <div class="row">
                            <div class="col-sm-6 form-group">
								<label>Title</label>
								<input type="text" name="title" placeholder="Enter Title.." class="form-control" required>
							</div>

                        </div>				
						<!-- <div class="form-group">
							<label>Address</label>
							<textarea placeholder="Enter Address Here.." rows="3" class="form-control"></textarea>
						</div>	 -->
						<div class="row">
							<div class="col-sm-4 form-group">
								<label>Author</label>
								<input type="text" name="author" placeholder="Enter Author.." class="form-control" required>
							</div>	
							<div class="col-sm-4 form-group">
								<label>Publisher</label>
								<input type="text" name="publisher" placeholder="Enter Publisher.." class="form-control" required>
							</div>	
							<div class="col-sm-4 form-group">
								<label>Year published</label>
								<input type="text" name="year_published" placeholder="Enter Year Published.." class="form-control" required>
							</div>		
						</div>
                        <div class="form-group">
						<label>Book subject</label>
						<input type="text" name="book_subject" placeholder="Enter Subject of the book.." class="form-control" required>
					</div>	

                        <hr/>

                        <div class="row">
							<div class="col-sm-4 form-group">
								<label>Price</label>
								<input type="text" name="price" placeholder="Enter Price.." class="form-control" required>
							</div>	
							<div class="col-sm-4 form-group">
								<label>Available copies</label>
								<input type="text" name="available_copies" placeholder="." disabled=true class="form-control" value="1" required>
							</div>	
							<div class="col-sm-4 form-group">
								<label>Book condition</label>
								<input type="text" name="book_condition" placeholder="Enter Book Condition.." class="form-control" required>
							</div>		
						</div>
            					
					<button type="submit" name="submit" class="btn btn-primary">Submit</button>					
					</div>
				</form> 
				</div>
	</div>
	</div>