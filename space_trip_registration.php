<?php 
	
	//DEFINE VARIABLES
	
	$errors = array();
	$success = null;
	$name = $origin = $dob = $sex = $destination = null;
	$destination_options = array("mercury" => "Mercury", "venus" => "Venus", "moon" => "Moon", "jupiter" => "Jupiter", "saturn" => "Saturn", "uranus" => "Uranus", "neptune" => "Neptune", "pluto" => "Pluto", "ub313" => "2003 UB313");
	$trip = $purpose = $arrest = $arrest_reason = $terrorist = $fears = $insurance = $terms = null;
	$fears_options = array("aliens" => "Aliens", "zero_gravity" => "Zero Gravity", "solar_flares" => "Solar Flares", "vast_space" => "Vast Infinite Space", "black_holes" => "Black Holes", "worm_holes" => "Wormholes", "airlock" => "Getting accidentally blown out of an airlock by a malicious self-aware computer", "interstellar" => "Getting lost in space-time like in Interstellar", "none" => "I have no fears");
	$trip_options = array("one" => "One Way", "round" => "Round Trip");
	$arrest_options = array("yes" => "Yes", "no" => "No");
	$sex_options = array("male" => "M", "female" => "F");
	$terrorist_options = array("yes" => "Yes", "no" => "No");
	$insurance_options = array("no" => "No", "regular" => "Regular(add $10,000)", "deluxe" => "Deluxe(add $20,000)");
	
	
	// Check if form was submitted
	
	// XYZ1
	error_log(print_r($_POST,true));
	
	if (isset($_POST['name']) && isset($_POST['origin']) && isset($_POST['dob']) && isset($_POST['destination']) && isset($_POST['purpose']) && isset($_POST['arrest_reason'])) { 
		
		//VALIDATE INPUTS
			
		$name = $_POST['name'];
		if (!strlen($_POST['name'])) {
			$errors['name'] = "Please enter your name.";
		}
			
		$origin = $_POST['origin'];
		if (!strlen($_POST['origin'])) {
			$errors['origin'] = "Please enter your country of origin."; 
		}
		
		$dob = $_POST['dob'];	
		if (!strlen($_POST['dob']) && !is_numeric($_POST['dob'])) {
			$errors['dob'] = "Please enter a valid birth date.";
		}
		
		$sex = $_POST['sex'];	
		if (!isset($_POST['sex'])) {
			$errors['sex'] = "Please provide your sex.";
		}	
		
		$destination = $_POST['destination'];
		if (!array_key_exists($_POST['destination'], $destination_options)) {
			$errors['destination'] = "Please select a destination.";
		}
		
		$trip = $_POST['trip'];	
		if (!isset($_POST['trip'])) {
			$errors['trip'] = "Please choose One Way or Round Trip travel.";
		}
		
		$purpose = $_POST['purpose'];	
		if (!strlen($_POST['purpose'])) {
			$errors['purpose'] = "Please tell us why you're traveling.";
		}
		
		$arrest = $_POST['arrest'];	
		if (!isset($_POST['arrest'])) {
			$errors['arrest'] = "Please tell us if you were ever arrested.";
		} else if (!array_key_exists($_POST['arrest'],$arrest_options)) {
			$errors['arrest'] = 'Please select a valid option';
		}
		
		$arrest_reason = $_POST['arrest_reason'];
		if (empty($_POST['arrest_reason']) && $arrest = $_POST['arrest']=='yes') {
			$errors['arrest_reason'] = "Please tell us why you were arrested.";
		}
		
		$terrorist = $_POST['terrorist'];	
		if (!isset($_POST['terrorist'])) {
			$errors['terrorist'] = "Come on now, tell us if you're a terrorist. We really need to know!";
		}
		
		$fears = $_POST['fears'];
		if (!isset($_POST['fears'])) {
			$errors['fears'] = "Please tells us what scares you.";
		}
		
		$insurance = $_POST['insurance'];
		if (!isset($_POST['insurance'])) {
			$errors['insurance'] = "Please indicate whether or not you'd like to purchase insurance.";
		}
		
		$terms = $_POST['terms'];
		if (!isset($_POST['terms'])) {
			$errors['terms'] = "You must agree to the terms & conditions before submitting.";
		}
			
		// IF NO ERRORS
			
		if (!$errors) {
			$success = true;
		}
				
	}
	
	
?>

<!DOCTYPE HTML>
<html>
	<head>
		
		<!-- font-family: 'Roboto', sans-serif; -->
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300,500' rel='stylesheet' type='text/css'>
		
		<title>Interplanetary Travel Registration</title>
		
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">	

		<style type="text/css">
			html {font-family:'Roboto',Helvetica,Arial,sans-serif;}
			body {background-color:#eeeeee;}
			#container {width:98%; min-width:480px; max-width:1000px; margin:0 auto; margin-bottom:50px;}
			#intro {text-align:center; text-transform:uppercase;}
			legend {font-weight:700; font-size:1.8rem; text-transform:uppercase;border-bottom-color:#cccccc;}
			label {font-weight:normal;}
			input {padding:0.4rem; border:1px solid #cccccc; border-radius:2px;}
			textarea {padding:0.5rem; border:1px solid #cccccc; border-radius:2px;}
			span.italic {font-style:italic;}
			.finePrint {font-size:1rem;}
			button {padding:0.7rem;cursor: pointer;}
			.buttonDiv {margin:0 auto; width:250px; text-align:center;}
			.error {color:#be1e2d; font-weight:700;}
			.outlierBox {width:98%; min-width:480px; max-width:500px; border:2px solid #be1e2d; padding:1.2rem; margin:0 auto;}
		</style>
		
	</head>
	
	<body>
			
		<div id="container">
		
			<div id="intro">
				<img src="img/p_logo.svg" alt="logo" />
				
				<?php 
					
					//IF SOMEONE CHOOSES THE MOON, PLUTO OR UB313 AS TRAVEL DESTINATIONS
					
					function outliers() {
						
						$outlierPlanet = ($_POST['destination']);
						
						if ($_POST['destination'] == 'pluto' || $_POST['destination'] == 'ub313') {
							echo '<div class="outlierBox"><p class="error">Travel to ' .$outlierPlanet. ' poses additional safety risks and may require additional expenses. Please review with your PlanetCo agent for final pricing.</p></div>';
						} else if ($_POST['destination'] == 'moon') {
							echo '<div class="outlierBox"><p class="error">You\'re going to the moon!<br />Please watch this orientation video to familiarize yourself with Lunar Station Alpha Delta.</p><iframe width="480" height="250" src="https://www.youtube.com/embed/kG-0V-85H_0" frameborder="0" allowfullscreen></iframe></div>';
						}
						
					}
					
					// IF NO ERRORS AND FORM SUBMITS SUCCESSFULLY
					
					if ($success) {
						
						// If not arrested or a terrorist, message based on insurance option
												
						if ($_POST['arrest']=='no' && $_POST['terrorist']=='no'){  
							
							if ($_POST['insurance'] == 'regular') {
								echo '<p class="error">THANK YOU! Your application is being processed.<br />Your estimated trip cost with REGULAR insurance is $110,000. <br />You will hear from a representative shortly to finalize the details. <br />Get ready to go to space!</p>';
								outliers();
								
						    } else if ($_POST['insurance'] == 'deluxe') {
							    echo '<p class="error">THANK YOU! Your application is being processed.<br />Your estimated trip cost with DELUXE insurance is $120,000. <br />You will hear from a representative shortly to finalize the details. <br />Get ready to go to space!</p>';
							    outliers();
							    
						    } else if ($_POST['insurance'] == 'no') {
							    echo '<p class="error">THANK YOU! Your application is being processed and you will hear from a representative soon.<br />Get ready to go to space!</p>';
							    outliers();
						    }
						}
						
						if ($_POST['arrest']=='yes' || $_POST['terrorist']=='yes') {
							echo '<p class="error">BASED ON YOUR INFORMATION, YOU HAVE BEEN DEEMED A SECURITY RISK AND YOUR APPLICATION IS DENIED.<br />AN AGENT WILL REACH YOU SHORTLY.</p>';
						}
						
						// CLEAR FIELDS	
						list($name, $origin, $dob, $sex, $destination, $trip, $purpose, $arrest, $arrest_reason, $terrorist, $fears, $insurance, $terms) = array(null, null, null, null, null, null, null, null, null, null, null, null, null);
						
					}
										
				?>
				
				
				<h1>Interplanetary Shuttle Registration</h1>
				<p>
					Please complete all fields. <br />Introductory special price for space travel is a flat $100,000.<br />Other costs will be calculated and applicants accepted or rejected for travel based on input.<br />
					If approved, you will be contacted by a PlanetCo agent to complete the process.
				</p>		
			</div>
			
			<form action="space_trip_registration.php" method="POST">
				<fieldset>
					
					<legend>Basic Info</legend>
					<div class="row">
						<div class="col-sm-6">
							<label for="name">Full Name</label>
							<input class="form-control" type="text" id="name" name="name" tabindex="1" value="<?php echo htmlspecialchars($name, ENT_QUOTES); ?>" />
								<?php
									if (isset($errors['name']))	{									 
										echo '<p class="error">'.htmlspecialchars($errors['name'],ENT_QUOTES).'</p>';
									}					
								?>
						</div>
						<div class="col-sm-6">
							<label for="dob">Date of Birth</label>
							<input class="form-control" type="text" name="dob" maxlength="10" placeholder="MM/DD/YYYY" tabindex="3" value="<?php echo htmlspecialchars($dob, ENT_QUOTES);?>" />
								<?php
									if (isset($errors['dob'])) {								 
										echo '<p class="error">'.$errors['dob'].'</p>';
									}
								?>
						</div>
					</div>	
					<br>
					<div class="row" style="margin-bottom:25px;">
						<div class="col-sm-6">
							<label for="origin">Country of Origin</label>
							<input class="form-control" type="text" id="origin" name="origin" tabindex="2" value="<?php echo htmlspecialchars($origin, ENT_QUOTES); ?>" />
							<?php
								if (isset($errors['origin'])) {									 
									echo '<p class="error">'.$errors['origin'].'</p>';
								}						
							?>
						</div>
						<div class="col-sm-6" style="padding-top:30px;">
							<label for="sex">Sex</label>
							<?php
								$i=1; 
								foreach($sex_options as $key => $value) {
									echo '<label for="sex'.$i.'">&nbsp;<input type="radio" name="sex" tabindex="4" id="sex"' .$i. '"value="' .$key. '"'.(in_array($key, $sex_options) ? ' checked="checked" ' : ''). '/>&nbsp;' .htmlspecialchars($value, ENT_QUOTES). '</label>&nbsp;&nbsp;';
									$i++;
								}
							?>
							
							<?php
								if (isset($errors['sex'])) {							 
									echo '<p class="error">'.$errors['sex'].'</p>';	
								}						
							?>
						</div>
					</div>					
					
					<legend>Destination</legend>
					<label for="destination">Destination</label>
					<select class="form-control" name="destination" id="destination" tabindex="5">
						<option>--Please Select a Destination--</option>
						<?php 
							foreach($destination_options as $key => $value) 
								echo '<option value="' .$key. '"'.($key == $destination ? ' selected="selected"' : '').'>'.htmlspecialchars($value, ENT_QUOTES).'</option>'	
						?>
					</select>
						<?php
							if (isset($errors['destination'])) {									 
								echo '<p class="error">'.$errors['destination'].'</p>';	
							}						
						?>
					
					<br /><br />
					
					<label for="trip">Is This A One-Way or Round-Trip?</label>
					<br />
					
						<?php
							$i=1; 
							foreach($trip_options as $key => $value) {
								echo '<label for="trip'.$i.'">&nbsp;<input type="radio" name="trip" tabindex="6" id="trip'.$i.'" value="' .$key. '"'.(in_array($key, $trip_options) ? ' checked="checked" ' : ''). '/>&nbsp;'.htmlspecialchars($value, ENT_QUOTES). '</label>&nbsp;&nbsp;';
								$i++;
							}
						?>
					
						<?php
							if (isset($errors['trip'])) {									 
								echo '<p class="error">'.$errors['trip'].'</p>';
							}							
						?>
						
					<br /><br />
					<label for="purpose">Primary Reason for Traveling</label>
					<br />
					<textarea rows="10" cols="50" id="purpose" class="form-control" name="purpose" tabindex="7" <?php echo htmlspecialchars($purpose, ENT_QUOTES); ?> ></textarea>
						<?php
							if (isset($errors['purpose'])) 	{								 
								echo '<p class="error">'.$errors['purpose'].'</p>';	
							}						
						?>			
					<br /><br />
					
					<legend>Additional Info</legend>
					
					<label for="arrest">Have You Ever Been Arrested?</label>
						<?php
							$i=1; 
							foreach($arrest_options as $key => $value) {
								echo '<label for="arrest'.$i.'">&nbsp;<input type="radio" name="arrest" tabindex="8" id="arrest'.$i.'" value="' .$key. '"'.(in_array($key, $arrest_options) ? ' checked="checked" ' : ''). '/>&nbsp;'.htmlspecialchars($value, ENT_QUOTES). '</label>&nbsp;&nbsp;';
								$i++;
							}
						?>
					
						<?php
							if (isset($errors['arrest'])) {								 
								echo '<p class="error">'.$errors['arrest'].'</p>';
							}							
						?>
					<br>
					
					<label for="arrest_reason">If yes, why?</label>
					<input class="form-control" type="text" name="arrest_reason" tabindex="9" value="<?php echo htmlspecialchars($arrest_reason, ENT_QUOTES); ?>" />
						<?php
							if (isset($errors['arrest_reason'])) {								 
								echo '<p class="error">'.$errors['arrest_reason'].'</p>';		
							}					
						?>
					<br>
					<label for="terrorist">Are you a terrorist?</label>
					
						<?php
							$i=1; 
							foreach($terrorist_options as $key => $value) {
								echo '<label for="terrorist'.$i.'">&nbsp;<input type="radio" name="terrorist" tabindex="10" id="terrorist'.$i.'" value="' .$key. '"'.(in_array($key, $terrorist_options) ? ' checked="checked" ' : ''). '/>&nbsp;'.htmlspecialchars($value, ENT_QUOTES). '</label>&nbsp;&nbsp;';
								$i++;
							}
							
							if (isset($errors['terrorist'])) {								 
								echo '<p class="error">'.$errors['terrorist'].'</p>';
							}
						?>

					<br><br>
					
					<!-- FEARS CHECKBOXES -->
					
					<label for="fears">What Do You Fear? <span class="italic finePrint">(Check all that apply)</span></label><br />
					
						<?php
							$i=1; 
							foreach($fears_options as $key => $value) {
								echo '<label for="fears'.$i.'">&nbsp;<input type="checkbox" name="fears[]" tabindex="11" id="fears'.$i.'" value="' .$key. '"'.(in_array($key, $fears_options) ? ' checked="checked" ' : ''). '/>&nbsp;'.htmlspecialchars($value, ENT_QUOTES). '</label>&nbsp;&nbsp;';
								$i++;
							}

							if (isset($errors['fears'])) {								 
								echo '<p class="error">'.$errors['fears'].'</p>';
							}
						?>
					<br /><br />
					
					<!-- END FEARS CHECKBOXES --> 
					
					<legend>Insurance</legend>
					<p>Trip insurance is offered in two packages: Deluxe and Regular.<br /><span class="italic finePrint">Insurance does not cover cases of catastrophic malfunction, alien abduction, or discharge from airlock by a malicious, self-aware computer.</span></p>
					<label for="insurance">Purchase Trip Insurance</label>
						<?php
							$i=1; 
							foreach($insurance_options as $key => $value) {
								echo '<label for="insurance'.$i.'">&nbsp;<input type="radio" name="insurance" tabindex="12" id="insurance'.$i.'" value="' .$key. '"'.(in_array($key, $trip_options) ? ' checked="checked" ' : ''). '/>&nbsp;'.htmlspecialchars($value, ENT_QUOTES). '</label>&nbsp;&nbsp;';
								$i++;
							}
							
							if (isset($errors['insurance'])) {									 
								echo '<p class="error">'.$errors['insurance'].'</p>';	
							}		
						?>
					
					<br /><br />
					
					<!-- FINAL SUBMIT SECTION -->
					
					<legend>Terms and Conditions</legend>
					<label for="terms"><input type="checkbox" id="terms" name="terms" value="terms" tabindex="24"></label>&nbsp;&nbsp;I agree to the <a href="#" data-toggle="modal" data-target="#terms_modal" title="Terms and Conditions">terms and conditions</a> set by PlanetCo.
						<?php
							if (isset($errors['terms'])) {					 
								echo '<p class="error">'.$errors['terms'].'</p>';	
							}
						?>
					<br /><br />
					
					<div class="buttonDiv">
						<button class="btn btn-block btn-primary" type="submit" id="submit" tabindex="25">APPLY</button>
					</div>
					
				</fieldset>
			</form>
		
		</div>
	
		<!-- Modal -->
		<div id="terms_modal" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Terms & Conditions</h4>
					</div>
					<div class="modal-body">
						<p>It's space, dude. We're gonna do our best, but you travel at your own risk. It's like, how the hell can we do anything about aliens, right?</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		
	</body>
</html>
