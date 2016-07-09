<?php

/* 

In this example we will:
1. Accept a file
2. Create variable to represent the file
3. Get the file extension
4. Validate the file was uploaded properly and that it's a valid size and extension
5. Check if file already exists
6. Move file to server

*/

// Create vars 
$possible_files = 3;
$errors = array();
$allowedfiles = array('gif', 'png', 'jpg', 'jpeg', 'docx');
$maxfilesize = 104857600; // 100 MB = 1024*1024*100 bytes
$showfilesize = "10MB";
$success = false;
$saved_files = array();

function validateUpload($data, $allowedfiles, $maxfilesize, $showfilesize) {

	// Initialize errors var - exists only in this function's scope
	$errors = array();

	// Get the filetype of the passed file - this may be a fake extension so it can't actually be trusted but it's a start
	$filetype = pathinfo($data['name'], PATHINFO_EXTENSION);		

	// Validate the passed file
	if ($data['error'] == 4) // No file was attached
		$errors['data_incomplete'] = 'Please select a file to upload.';
	elseif ($data['error'] == 1) // php.ini's upload_max_filesize was exceeded
		$errors['data_excessive'] = 'The file you attempted to upload is larger than the maximum allowed filesize of '.$showfilesize.'.';
	elseif ($data['error'] == 3) // The file was only partially uploaded
		$errors['data_failure'] = 'There was a problem uploading your file. Please try again.';
	elseif ($data['error'] == 2 || filesize($data['tmp_name']) > $maxfilesize) // Over php's html form max file size or manually set limit
		$errors['data_excessive'] = 'The file you attempted to upload is larger than the maximum allowed filesize of '.$showfilesize.'.';
	elseif ($data['error'] == 6 || $data['error'] == 7 || $data['error'] == 8) // No folder, cant write, or stopped
		$errors['data_failure'] = 'There was a problem uploading your file. Please try again.';
	elseif (!is_uploaded_file($data['tmp_name'])) // Not sent through POST, possible attack
		$errors['data_failure'] = 'There was a problem uploading your file. Please try again.';
	elseif (!in_array($filetype, $allowedfiles)) // File is not an allowed type
		$errors['data_invalid'] = 'The type of file you selected is invalid. Please select from '.implode(', ', $allowedfiles).'.';

	// Check if the file already exists
	if (!$errors) {
		if (file_exists($data['name'])) {
			$errors['file_exists'] = 'This file already exists.';
		}
	}

	// return errors or empty array
	return $errors;

}

// Check if our form was submitted
if (isset($_FILES) && (isset($_FILES['upload1']) || isset($_FILES['upload2']) || isset($_FILES['upload3']))) { 

	// Loop through possible files
	
	$fileuploaded = false; 
	for ($i = 1; $i <= $possible_files; $i++) {

		if (isset($_FILES['upload'.$i])) { 

			// Get the current datafield
			$data = $_FILES['upload'.$i];

			// Check for basic errors
			$file_errors = validateUpload($data, $allowedfiles, $maxfilesize, $showfilesize);
			if (!$file_errors) {  //File uploaded without errors
				$fileuploaded = true;  //Record that user uploaded a file
			} else {  //Errors found
				if (key($file_errors) != 'data_incomplete') // File uploaded but has errors
					$fileuploaded = true;  //Record that user uploaded a file
			}  

			// Move the file to our server - it is currently being saved in some temporary location as defined in your server configuration
			if (!$file_errors) {
				
				$move = move_uploaded_file($data['tmp_name'], $data['name']); // Move file to current directory
				if (!$move) {
					$errors['data_unmoved'] = 'There was a problem uploading your file. Please try again.';
				} else {
					
					// Save the passed file we moved
					$saved_files['upload'.$i] = array('name' => $data['name']);
					
				}
				
			} else { // There is an error for this one file

				// Save our errors to display for this field only
				$errors['upload'.$i] = $file_errors;

			}

		}
	}
	
	//Clear data_incomplete errors if a file was uploaded
	if ($fileuploaded) {  //At least one file uploaded
		foreach ($errors as $key => $value) { //Loop through all errors
			if (current($value) == 'Please select a file to upload.')  // This is a data_incomplete error
				unset($errors[$key]); //Remove data incomplete error
		}
	}

	// Set our success/errors conditions
	if (!$errors) {
		
		// Indicate success
		$success = true;
		
	}

}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>::PHP Uploads::</title>
		
		<meta charset="utf-8" />
	    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	    <meta name="viewport" content="width=device-width, initial-scale=1" />
	
		<!-- font-family: 'Roboto', sans-serif; -->
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300,500' rel='stylesheet' type='text/css'>
	
		<style type="text/css">
			body {margin:0; padding:0; background-color:#eeeeee;font-family:'Roboto',Helvetica,Arial,sans-serif;}
			div.container {width:400px;margin:0 auto; margin-top:10rem;}
			h1 {color:#212121;text-transform:uppercase; font-size:1.5rem;text-align:center;margin-bottom:3rem;}
			h2 {color:red;text-transform:uppercase;font-size:1rem;}
			ul {text-align:center;margin:0; padding:0;list-style-type:none;}
			ul li {margin-bottom:1rem;}
			legend {font-weight:700; font-size:1.3rem;}
			fieldset {padding:2rem 0 1.5rem 1rem; margin-bottom:2.5rem;}
			p {color:#212121; margin-top:1rem;}
			p.confirm {color:red;}
			input {padding:0.3rem 1rem;}
			input, select {margin-bottom:1rem;}
			.error {color:red; font-weight:bold;}
		</style>
	</head>
	
	<body>
		
		<div class="container">
			<?php
			if ($success) {
				echo '<p class="confirm">Thanks! We\'ve saved your files.</p>';
				foreach ($saved_files as $key => $value) {
					$filetype = pathinfo($value['name'], PATHINFO_EXTENSION);
					$isimg = in_array($filetype, array('png', 'jpg', 'jpeg')) ? true : false;
					echo '<p><a href="/'.$value['name']. '" target="_blank" title=" ' .$value['name'].'">'.($isimg ? '<img src="/' .$value['name'].'" height="40px" width="40px" />' : $value['name']). '</a></p>';
				}
			}
			?>
			<h1>PHP Uploads</h1>
			<form action="php_uploads.php" method="POST" enctype="multipart/form-data">
				<fieldset>
					<legend>Profile Update</legend>
					<label for="upload1">Upload 1</label>
					<input type="file" name="upload1" id="upload1" /><br /><br />
					<?php echo isset($errors['upload1']) ? '<p style="color:red">'.current($errors['upload1']).'</p><br />' : ''; ?>
								
					<label for="upload2">Upload 2</label>
					<input type="file" name="upload2" id="upload2" /><br /><br />
					<?php echo isset($errors['upload2']) ? '<p style="color:red">'.current($errors['upload2']).'</p><br />' : ''; ?>
									
					<label for="upload3">Upload 3</label>
					<input type="file" name="upload3" id="upload3" /><br /><br />
					<?php echo isset($errors['upload3']) ? '<p style="color:red">'.current($errors['upload3']).'</p><br />' : ''; ?>
					
					<button type="submit">Submit</button>
				</fieldset>
			</form>
		</div>
	</body>
</html>