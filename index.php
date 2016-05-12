<?php  session_start(); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 

    <title>Instagram Scraper</title>

    <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

	<link rel="stylesheet" href="css/app.css"> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse  ">
      <div class="container">
        <div class="navbar-header"> 
          <a class="navbar-brand" href="#">Instagram Scraper</a>
        </div> 
      </div>
    </nav>
 


    <div class="container">
      <div class="row" id="file-upload">
      	<div class="col-sm-12">  
	        <?php   
			
			// Set the token from form post if posted 
			if(isset($_POST)){
				if(isset($_POST['token'])){
					$_SESSION['access_token'] = $_POST['token'];
				}
			}
 
			include('config.php');
			include('functions.php');
			if($_SESSION['access_token'] != ''){
				include('scraper.php');
			}else{
				// if no token, ask for it
				?>

				<form id="formid" action="index.php" method="post" enctype="multipart/form-data" style="display:none;"> 
				<h2>Please enter an Instagram Token.</h2> 
				  <fieldset class="form-group"> 
				    <input type="text"  id="token" name="token" value="" >  
				  </fieldset>
					<input type="submit" name="submit" value="Set Token" class="btn btn-primary" /><br /><br />
					<p><a href="http://instagram.pixelunion.net/" target="_blank"  >Click here if you don't have a token</a> </p>
				</form>

			<?php
			}
	         ?>
	    </div> 
      </div>
      <div class="row" >
      	<div class="col-sm-12">  
	        <p class="bg-warning alert processing">Processing file. Please wait....</p>
	    </div> 
      </div>





    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script><script src="js/app.js"></script> 

  </body>
</html>