<?php


//$users=array("moderncrush","ladygaga");

$users = array();

/* upload and parse CSV */
echo '
<form id="formid" action="index.php" method="post" enctype="multipart/form-data" style="display:none;"> 
<h2>1. Confirm Your File Directory</h2>
  <fieldset class="form-group"> 
    <input type="text"  id="filedir" name="filedir" value="'.getcwd() .'/downloads" style="width:100%;"> 
    <small class="text-muted bg-danger"> Edit this field carefully or leave it.</small>
  </fieldset>

  <div class="row">
    <div class="col-sm-6">
      <h2>2. Upload your User File</h2>
      <fieldset class="form-group"> 
        <input type="file" class="form-control-file" id="csv" name="csv">
        <small class="text-muted"> Make sure your users are in a column with heading "Instagram"</small>
        <small class="text-muted bg-danger"> CSV File Only!</small>
      </fieldset>
   </div>
    <div class="col-sm-6">
      <fieldset class="form-group"> 
        <h2>Or Paste Instagram Users</h2>
        <textarea name="rawusers" style="width:100%;height:120px;" ></textarea> <br />
        <small class="text-muted">One username per line</small> 
      </fieldset>
   </div>
  </div>

<input type="submit" name="submit" value="Start Scraping!" class="btn btn-primary" /></form>
<hr />';

$csv = array();
 
if (!isset($_POST['filedir'])) {
  $filedir = getcwd() . '/downloads';
  ?>
    <style>form{display: none;}</style>
  <?
}else{
  $filedir = $_POST['filedir'];
}  
// If file uploaded but not users pasted
if(strlen(trim($_POST['rawusers'])) == 0){
    // check there are no errors
    if(isset($_FILES['csv']['error']) && $_FILES['csv']['error'] == 0){
        $name = $_FILES['csv']['name']; 
        $type = $_FILES['csv']['type']; 
        $tmpName = $_FILES['csv']['tmp_name'];

        // check the file is a csv
        if($type === 'text/csv'){

            if(($handle = fopen($tmpName, 'r')) !== FALSE) {
                // necessary if a large csv file
                set_time_limit(0);

                $row=0;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
              $num = count($data);
              //echo "<p> $num fields in line $row: <br /></p>\n";
              $row++;
              for ($c=0; $c < $num; $c++) {
                  //echo $data[$c] . "<br />\n";
                  /* only grab the instagram column */
                  if(!isset($instacolumn) && strtolower($data[$c]) == 'instagram' ){
                    $instacolumn = $c;
                  }

                  if(isset($instacolumn) && $instacolumn == $c && strtolower($data[$c]) != 'instagram' && $data[$instacolumn] != ''){
                    //echo $data[$instacolumn] . '<br />';
                    $users[] = $data[$c];
                  }
              }
            }
            fclose($handle);
            }
        }


    }
}else{
  // work with pasted users
  $text = trim($_POST['rawusers']); // remove the last \n or whitespace character 
  $text = nl2br($text);
  $text = str_replace('<br />', ',', $text);
  $users = explode(',', $text);
  //print_r($users);
}

//Create array for CSV Export
$_SESSION['csv_export'] = array();

//print_r($users);
/* RUN USERS ARRAY */
foreach ($users as &$username) {

  $next_url = 'first'; 
  while ($next_url != '') {

    /* FORMULATE URL */
    $userid = getInstaID($username,$_SESSION['access_token']);
    if($next_url == 'first'){
      $url = build_url($userid,$_SESSION['access_token']);
    }else{
      $url = $next_url;
    }

    $json = file_get_contents($url);
    $obj = json_decode($json);

    /* CHECK FOR MORE PAGES */
    if(isset($obj->pagination->next_url)){
      //echo print_r($obj->pagination->next_url);
      $next_url = $obj->pagination->next_url;
    }else{
      $next_url = '';
    }

    /* RUN THE SCRAPER */
    image_scrape($obj,$filedir,$username);

    if ($username === end($users)){  
      //display status when done! 
?>

      <div class="row"  id="">
        <div class="col-sm-12 bg-success alert">  
          <h3 class=" ">Download Complete!</h3>  
          <div class="col-sm-6">
             <p><a href="zipit.php" target="_blank" class="btn btn-primary">Download Zip</a>
              Checkyour local files here: <?php echo $filedir; ?></p>
          </div>
          <div class="col-sm-6">
            <p><a href="export.php" target="_blank" class="btn btn-primary">Download File List (CSV)</a></p>
          </div>
        </div> 
      </div>

       <script type="text/javascript"> 
           <?php
                // scroll down so you know the download is done!
               echo "scrolldown();";
           ?>
       </script>


<?php
    }
  } 

}




?>

     