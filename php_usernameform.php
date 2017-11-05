<?php

	// Create handle to users file
	
	$name = null; 
	$success = false;
	$users = array();
	$errors = array();
	$filename = 'users.txt';
	$handle = fopen($filename, 'a+');  // make connectiont o the file, create it if it doesn't exist
	
		if ($handle) {
		while(($line = fgets($handle)) !== false) {   //creating a variable $line equal to fgets and the lops continues as long as not false i.e. there are still lines
				$users[] = trim($line);
			}
			
		}
	
		
	
	//Check if form was posted
	if (isset($_POST['username'])) {
		
		//Validate username
		if (!strlen($_POST['username'])) {
			$errors['username'] = "Please enter a user name.";
		}
		
		//Check if a user exists
		else if (in_array($_POST['username'], $users)) {
			$errors['username'] = "This user name is already taken. Please try another.";
		}
		
		//Check for alphanumeric characters
		else if (!preg_match('/^[a-zA-Z0-9]+$/',$_POST['username'])) {
			$errors['username'] = "Please use only alphanumeric characters.";	
		}
		
		//Save Variables for display
		$name = $_POST['username'];
		
		if(!$errors) {
			
			//Indicate success
			$success = true;
				
			//fwrite($handle, $name."\r\n"); 
			
			if (!$users) { // FIrst user
				fwrite($handle, $name);
			} else {
				fwrite($handle, "\r\n".$name);
			}
			$users[] = $name;
	
			//Clear inputs
			$name=null;
		}
	
	}
		
	
	fclose($handle);
	
	/*
	echo '<pre>';
	print_r($users);
	echo '</pre>';
	exit;
	*/
	
	//Check if form was posted , check if it's valid, check if the user exists, write the user to the text file, add user to array ==> display the users
	
	//Close handle


?>

<html>
	<head>
		<title>::PHP User Names::</title>
		
		<meta charset="utf-8" />
	    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	    <meta name="viewport" content="width=device-width, initial-scale=1" />
	
		<!-- font-family: 'Roboto', sans-serif; -->
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300,500' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">	
		<style type="text/css">
			body {margin:0; padding:0; background-color:#eeeeee;font-family:'Roboto',Helvetica,Arial,sans-serif;}
			div.container {width:350px;margin:0 auto; margin-top:10rem;}
			h1 {color:#212121;text-transform:uppercase; font-size:2rem;text-align:center;margin-bottom:3rem;border-top:1px solid #cccccc;padding-top:15px;}
			h2 {color:red;text-transform:uppercase;font-size:1rem;}
			ul {text-align:center;margin:0; padding:0;list-style-type:none;}
			ul li {margin-bottom:1rem;}
			legend {font-weight:700; font-size:1.3rem; border-bottom-color: #cccccc;}
			fieldset {margin-bottom:2.5rem;}
			p {color:#212121; margin-top:1rem;}
			input {padding:0.3rem 0.3rem;}
			input, select {margin-bottom:1rem;}
			.error {color:red; font-weight:bold;}
		</style>
	</head>
	<body>
		<div class="container">
			<?php 
				if($success) {
					echo "<h2>Success! Username added.</h2>";
				}
			?>
			
				<legend>Enter Username</legend>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<fieldset>
					<label for="username">User Name:</label>
					<input class="form-control" type="text" id="username" name="username" value="<?php echo htmlspecialchars($name, ENT_QUOTES); ?>" />
					<?php 
						if (isset($errors['username']))
							echo "<p class='error'>".$errors['username']."</p>";
					?>
					<button class="btn btn-block btn-primary" type="submit">Submit</button>
					</fieldset>
				</form>
			<hr>
			<h1>Current Users</h1>
			<ul>
				
				<?php 
					if ($users) {
						foreach ($users as $key => $value)
							echo '<li>'.htmlspecialchars($value, ENT_QUOTES).'</li>';
					} else {
						echo '<p>There are no users yet.</p>';
					}
				?>
				
			</ul>
		</div>
	</body>
</html>
