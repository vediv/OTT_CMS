<?php
include_once 'auths.php';
$action=$_POST['action'];
switch($action)
{
    case "image_popup":
    $image_info = getimagesize($_FILES["photoimg"]["tmp_name"]);
    $image_width = $image_info[0];
    $image_height = $image_info[1];
    //print_r($image_info);
    if(($image_width >=650 && $image_width<=780)  && ($image_height >=1024 && $image_height<=1280) )
    {
         echo 1;
    }
    else{
    echo 0;
    }
    break;
    case "menu_icon":
     $image_info = getimagesize($_FILES["photoimg"]["tmp_name"]);
     $image_width = $image_info[0];
     $image_height = $image_info[1];
     //print_r($image_info);
     if(($image_width==45 && $image_width==45)  )
     {
      echo 1;
     }
     else{
        echo 0;
     }
    
     break; 
     
    case "slider_image":
    $image_info = getimagesize($_FILES["photoimg"]["tmp_name"]);
    $image_width = $image_info[0];
    $image_height = $image_info[1];
    //print_r($image_info);
    if(($image_width >=1800 && $image_width<=2400)  && ($image_height >=900 && $image_height<=1005) )
    {
         echo 1;
    }
    else{
    echo 0;
    } 
     break;  
    case "marketing":
    //echo "yes" ; 
    $image_info = getimagesize($_FILES["imageUpload"]["tmp_name"]);
    $image_width = $image_info[0];
    $image_height = $image_info[1];
    //print_r($image_info);
    echo 1;
    /*if(($image_width >=300 && $image_width<=350)  && ($image_height >=150 && $image_height<=170) )
    {
         echo 1;
    }
    else{
    echo 0;
    }*/
    break;
    
    
}



?>
