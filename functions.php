<?php
 
/* Function that formats the first URL */
function build_url($user_id,$access_token){ 

	$url = 'https://api.instagram.com/v1/users/' . $user_id . '/media/recent?access_token='. $access_token . '&count=50';
	return $url;
}


/* Get User ID from Username */

function getInstaID($username,$token)
{

    $username = strtolower($username); // sanitization 
    $url = "https://api.instagram.com/v1/users/search?q=".$username."&access_token=".$token;
    $get = file_get_contents($url);
    $json = json_decode($get);

    foreach($json->data as $user)
    {
        if($user->username == $username)
        {
            return $user->id;
        }
    }

    return '00000000'; // return this if nothing is found
}


//Before download a file we need to find if file is exists, filesize  etc.

function getFileInfo($url){
  $ch = curl_init($url);
  curl_setopt( $ch, CURLOPT_NOBODY, true );
  curl_setopt( $ch, CURLOPT_HEADER, false );
  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, false );
  curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
  curl_setopt( $ch, CURLOPT_MAXREDIRS, 3 );
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_exec( $ch );
 
  $headerInfo = curl_getinfo( $ch );
  curl_close( $ch );
 
  return $headerInfo;
}


function splitpath($url){

  $filename = basename($url);
  $filesplit = explode("?", $filename); 

  return $filesplit[0];
}


//Now after checking information you can call fileDownload function to download the file.
function fileDownload($url, $destination){
 
  $path = $destination .'/' . splitpath($url);

  $fp = fopen ($path, 'w+') or die('Cannot open file: '.$url);
  $ch = curl_init();
  curl_setopt( $ch, CURLOPT_URL, $url );
  curl_setopt( $ch, CURLOPT_BINARYTRANSFER, true );
  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, false );
  curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
 
  curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
  curl_setopt( $ch, CURLOPT_FILE, $fp );
  curl_exec( $ch );
  curl_close( $ch );
  fclose( $fp );
 
  if (filesize($path) > 0) return true;
}

/* Parse JSON and show results */ 
$_SESSION['prev_user'] = ''; //default session setting for prev_user
function image_scrape($obj,$filedr,$username){
	//Make root directory if missing
	if (!file_exists($filedr)) {
		mkdir($filedr);
	}  
    
	foreach($obj->data as $item){
		//print_r($item);
    	$image_link = $item->images->standard_resolution->url; 
    	//echo '<img src="'.$image_link.'" />';
		if($_SESSION['prev_user'] != $username){
			if($_SESSION['prev_user'] != ''){ 
			    echo '<p class="bg-success alert"><strong>'.$_SESSION['prev_user'].'</strong> Complete!</p>';
		    	flush();
			}
		    echo '<p class="bg-info alert">Files for <strong>'.$username.'</strong> downloading.</p>';
		    flush();
		}
      	$_SESSION['prev_user'] = $username;
    	//check or create new file dir for user
    	$userdr = $filedr . '/' . $username;
    	if (!file_exists($userdr)) {
			mkdir($userdr);
		}  
		 
		$fileInfo = getFileInfo($image_link);
		 if($fileInfo['http_code'] == 200 && $fileInfo['download_content_length'] < 2000*2000){
		 	//print_r($fileInfo);
		    if(fileDownload($image_link, $userdr)){
		        //echo "File dowloaded: " . $image_link;

			    // Store in CSV Array
			    $_SESSION['csv_export'][] =  array($username,splitpath($image_link));
		    }
		 	
		} 

	} 
}

 
/* CSV FILE PARSE */
function parse_file($file){
	$fh = fopen($_FILES['file']['tmp_name'], 'r+');
	$lines = array();
	while( ($row = fgetcsv($fh, 8192)) !== FALSE ) {
		$lines[] = $row;
	}
	var_dump($lines); 
}




?>