<?php 
 require_once("../resources/config.php");
include (FRONT.DS."header.php");

?>


<div class="container">
    <h3 class="well">Sign Up</h3>
	<div class="col-lg-12 well">
	<div class="row">
	             <h4 class="text-center bg-warning"><?php display_message(); ?></h4>
				<form action="" method="post">
				    <?php signup(); ?>

					<div class="col-sm-12">
						<div class="row">
							<div class="col-sm-6 form-group">
								<label>Username</label>
								<input type="text" name="user_Name" placeholder="Enter Username Here.." class="form-control" required>
							</div>
							<div class="col-sm-6 form-group">
								<label>Email</label>
								<input type="email" name="email" placeholder="Enter Last Name Here.." class="form-control" required>
							</div>
						</div>	
                        <div class="row">
                            <div class="col-sm-6 form-group">
								<label>Password</label>
								<input type="password" name="user_password" placeholder="Enter Password Here.." class="form-control" required>
							</div>

                        </div>				
						<!-- <div class="form-group">
							<label>Address</label>
							<textarea placeholder="Enter Address Here.." rows="3" class="form-control"></textarea>
						</div>	 -->
						<div class="row">
							<div class="col-sm-4 form-group">
								<label>City</label>
								<input type="text" name="city" placeholder="Enter City Name Here.." class="form-control">
							</div>	
							<div class="col-sm-4 form-group">
								<label>State</label>
								<input type="text" name="state" placeholder="Enter State Name Here.." class="form-control">
							</div>	
							<div class="col-sm-4 form-group">
								<label>Zip</label>
								<input type="text" name="zip" placeholder="Enter Zip Code Here.." class="form-control">
							</div>		
						</div>
									
					<div class="form-group">
						<label>Phone Number</label>
						<input type="text" name="phone" placeholder="Enter Phone Number Here.." class="form-control">
					</div>			
					
					<button type="submit" name="submit" class="btn btn-primary">Submit</button>					
					</div>
				</form> 
				</div>
	</div>
	</div>