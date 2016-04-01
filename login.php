<?php
session_start();




if( isset($_GET['action']) && $_GET['action'] == "login"){
    if(isset($_SESSION["image_src"])){
      deleteCapture();
    }
    createCapture();
}

if(isset($_POST["capt"]) &&  strlen($_POST["capt"])>0 ) {
  $userGesture = $_POST["capt"];
  if($userGesture == $_SESSION["capture"]){
      // valid capture gestute
      deleteCapture();
      include "$_SERVER[DOCUMENT_ROOT]/capture/views/success.php";
  }
  else if (isset($_SESSION["fails"]) && $_SESSION["fails"]>=2 ){

  deleteCapture();
  createCapture();
  }
  else if(isset($_POST["capt"])){
  $_SESSION["fails"] +=1;
 // include required page
  include "$_SERVER[DOCUMENT_ROOT]/capture/views/login.php";
  }
}




//create image function
function makeImg($text){
  $im = imagecreatetruecolor(220, 50);
  $color = imagecolorallocate($im, 40, 130, 0);
  $lineColor = imagecolorallocate($im, 40, 0, 140);
  imagefill($im, 0, 0, $color);
  $text_color = imagecolorallocate($im, 255, 255, 255);
  imagestring($im, 5, 100, 20,  $text, $text_color);
  imageline( $im , 1 , 50, 200 , 0 , $lineColor);
  imageline( $im , 70 , 0, 200 , 50 , $lineColor);
  //imagettftext($im, 20, 0, 10, 20, $text_color,"ARIAL",$text );


  // Save the image as 'simpletext.jpg'
  // here we have to replace time() with $_SESSION['id'] because time() could be duplicated !
  $imageSrc= './captures/capture'.time().'.png';
  try {
    if(!imagepng($im, $imageSrc)){
      throw new Exception('Could not cretate capture');
    }
  } catch (Exception $e) {
    die ('Capture not created: ' . $e->getMessage());
  }


  // Free up memory
  imagedestroy($im);
  return $imageSrc;
}

function createCapture(){

  //this will count the wrong answers
  $_SESSION['fails']=0;
  //generate random string
  $captureString = rand(1000,10000);

  // save string in session
  $_SESSION["capture"] = $captureString;

  //make image from string and save in Directory
  $_SESSION["image_src"]=makeImg($captureString);

  // include required page
  include "$_SERVER[DOCUMENT_ROOT]/capture/views/login.php";

}

function deleteCapture(){
  //delete captcha img
  unlink($_SESSION["image_src"]);
  // clear Session variables
  unset($_SESSION['capture']);
  if (isset($_SESSION["fails"])){
    unset($_SESSION["fails"]);
  }
  unset($_SESSION["image_src"]);
}


?>
