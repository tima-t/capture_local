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
      echo "1";
      // valid capture gestute
      deleteCapture();
      include "$_SERVER[DOCUMENT_ROOT]/capture/views/success.php";
  }
  else if (isset($_SESSION["fails"]) && $_SESSION["fails"]>=2 ){
  echo "2";
  deleteCapture();
  createCapture();
  }
  else if(isset($_POST["capt"])){
    echo "3";
  $_SESSION["fails"] +=1;
 // include required page
  include "$_SERVER[DOCUMENT_ROOT]/capture/views/login.php";
  }
}




//create image function
function makeImg($text){
  $im = imagecreatetruecolor(220, 50);
  $color = imagecolorallocate($im, 40, 130, 0);
  imagefill($im, 0, 0, $color);
  $text_color = imagecolorallocate($im, 255, 255, 255);
  imagestring($im, 5, 100, 20,  $text, $text_color);
  //imagettftext($im, 20, 0, 10, 20, $text_color,"ARIAL",$text );


  // Save the image as 'simpletext.jpg'
  $imageSrc= './captures/capture'.time().'.png';
  imagepng($im, $imageSrc);
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
