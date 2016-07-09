<?php

// Define our variables
$errors = array();
$success = false;
$name = null;
$transit = null;
$transit_options = array('walk' => 'Walk', 'bike' => 'Bike', 'public transit' => 'Public Transit', 'drive' => 'Drive');
$distance = null;
$commute_distance = array(0 => 'Less than 1 Mile', 1 => '1 to 10 Miles', 2 => 'More than 10 Miles');

/* Uncomment if needed
// Use if needed to view superglobals
echo '<pre>';
print_r($_POST);
echo '</pre>';
exit;
Uncomment if needed */

// Check if the form was submitted and process the submission
if (isset($_GET['name']) && isset($_GET['transit']) && isset($_GET['distance'])) {
	
	// Validate form input
	if (!strlen($_GET['name']))
		$errors['name'] = 'Please enter your name';
	else
		$name = $_GET['name'];
	if (!array_key_exists($_GET['transit'], $transit_options))
		$errors['transit'] = 'Please select a valid transit option';
	else
		$transit = $_GET['transit'];
		
	if (!array_key_exists($_GET['distance'], $commute_distance))
		$errors['distance'] = 'Please select a valid commute distance';
	else
		$distance = $_GET['distance'];
	
	// Update success variable and clear form if no errors
	if (!$errors) {
		$success = true;	
		if ($transit ==='drive'){
			header('Location: http://www.tesla.com');
			exit;
		}
	}	
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>::How Do You Commute?::</title>
        
        <meta charset="utf-8" />
	    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	    <meta name="viewport" content="width=device-width, initial-scale=1" />
		
		<!-- font-family: 'Roboto', sans-serif; -->
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300,500' rel='stylesheet' type='text/css'>
		
		<style type="text/css">
			body {margin:0; padding:0; background-color:#eeeeee;font-family:'Roboto',Helvetica,Arial,sans-serif;}
			div.container {width:400px;margin:0 auto; margin-top:10rem;}
			h1 {color:#212121;text-transform:uppercase; font-size:1.5rem;}
			h2 {color:#212121;}
			legend {font-weight:700; font-size:1.3rem;}
			fieldset {padding:2.4rem 0 2.2rem 1rem;}
			p {color:#212121; margin-top:2rem; font-size:1.1rem;}
			p.ouch {font-weight:700; font-size:1.1rem;}
			input {padding:0.3rem 0.3rem;}
			input, select {margin-bottom:1rem;}
		</style>
		
	</head>
	<body>

		
		<div class="container">
			<h1>How do you get to work?</h1>
			<form action="php_commuter_form.php" method="GET">
				<fieldset>
					<label for="name">Name</label>
					<input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name, ENT_QUOTES); ?>" />
					<?php
					if (isset($errors['name']))										 
						echo '<p class="error">'.$errors['name'].'</p>';
					?>
					<br />
					<label for="transit">Transit Option</label>
					<select name="transit" id="transit">
						<?php
							foreach ($transit_options as $key => $value)
								echo '<option value="'.$key.'"'.($key == $transit ? ' selected="selected"' : '').'>'.htmlspecialchars($value, ENT_QUOTES).'</option>';
						?>
					</select>
					<?php
					if (isset($errors['transit']))
						echo '<p class="error">'.$errors['transit'].'</p>';
					?>
					<br />
					<label for="distance">Commute Distance</label>
					<select name="distance" id="distance">
						<?php 
							foreach ($commute_distance as $key => $value)
								echo '<option value="'.$key.'"'.($key == $distance ? ' selected="selected"' : '').'>'.htmlspecialchars($value, ENT_QUOTES).'</option>';
						?>
					</select>
					<br />
					<button type="submit">Submit</button>
				</fieldset>
			</form>
			
			<?php

		if ($success) {
			
			switch ($transit) {
				case 'walk':
					echo '<p>Great! Awesome exercise.</p>';
					break;
				case 'bike':
					echo '<p>Cool! Don\'t get hit by a car.</p>';
					break;
				case 'public transit':
					echo '<p>Sweet! Strangers and people-watching.</p>';
					break;
				case 'drive':
					echo '<p>You are the cause of man made climate change, thanks a lot.</p>';
					break;					
			}
			
			if ($distance == 2) 
				echo '<p class="ouch">Sounds like a long trip though. I\'m so sorry '.htmlspecialchars($name, ENT_QUOTES).'!</h2>';
		    else 
				echo '<h2>Thanks for submitting '.htmlspecialchars($name, ENT_QUOTES).'!</h2>';
			
			echo '<hr />';
			list($name, $transit, $distance) = array(null, null, null); // Clear form values
		}

		?>
			
		</div>
	</body>
</html>