<?php
include_once 'auths.php'; 
include_once 'auth.php'; 
include_once 'function.inc.php';
$conn=db_connect(DASHBOARD_USER_ID,PUBLISHER_UNIQUE_ID);
$get_user_id=DASHBOARD_USER_ID;  $publisher_unique_id=PUBLISHER_UNIQUE_ID;$login_access_level=ACCESS_LEVEL;
$partnerID=PARTNER_ID; $serviceURL=SERVICEURL;
$apiURL="http://ott.planetcast.in:6080";
switch($publisher_unique_id)
{
    case "ott503":
    $apiURL1="http://ott.planetcast.in:6086";
    break;
    case "ott333":
    $apiURL1="http://ott.planetcast.in:6088";
    break;
    default:
    $apiURL1="http://ott.planetcast.in:6086";    
}
function getUserRights($userID) {
        global $conn;
        $get_user_id = intval($userID);
        $uquery="SELECT operation_permission FROM publisher where par_id='".$get_user_id."'";
        $fetch_access_list= db_select($conn,$uquery);
        $operation_permission = $fetch_access_list[0]['operation_permission'];
        return $operation_permission;
}
function getOtherPermission($userID) {
        global $conn;
        $get_user_id = intval($userID);
        $uquery="SELECT other_permission FROM publisher where par_id='".$get_user_id."'";
        $fetch_access_list= db_select($conn,$uquery);
        $other_permission = $fetch_access_list[0]['other_permission'];
        return $other_permission;
}
function getMenuRights1($userID) {
        global $conn;
        $get_user_id = intval($userID);
        $uquery="SELECT menu_permission FROM publisher where par_id='".$get_user_id."'";
        $fetch_access_list= db_select($conn,$uquery);
        $get_access_list = $fetch_access_list[0]['menu_permission'];
        return $get_access_list;
    }
function getUserallMainMenus($userID) {
        global $conn;
        $get_user_id = intval($conn,$userID);
        $rights = getMenuRights1($userID);
        $get_main_menu_query = "SELECT mid FROM menus where mid IN ($rights) and mstatus='1'";
        $uaccessMenu=  db_select($conn,$get_main_menu_query);
        return $uaccessMenu;
    }
function search_array($needle, $haystack) {
     if(in_array($needle, $haystack)) {
          return true;
     }
     foreach($haystack as $element) {
          if(is_array($element) && search_array($needle, $element))
               return true;
     }
   return false;
}   
$UserRight=explode(",",getUserRights($get_user_id));
$otherPermission=explode(",",getOtherPermission($get_user_id));
$getAllMenus=explode(",",getMenuRights1($get_user_id));
$pagenamerequest= basename($_SERVER['REQUEST_URI']);
$pname=explode("?",$pagenamerequest);
$ExactName=trim($pname[0]); 
if($ExactName!='404.php')
{    
    $qry="SELECT mid FROM menus where menu_url='".$ExactName."' ";
    $fet= db_select($conn,$qry);
    $menuid=$fet[0]['mid'];
    if($menuid!='')
    {    
        $menu_array=getUserallMainMenus($get_user_id);
        if(!search_array($menuid,$menu_array)){ 
        header("Location:404.php");
        }
    }
    /*else
    {
        $included_page_name=array('live_channel_paging.php','dashboard.php','media_paging.php','youtube_paging.php','dashboarduser.php');
        if (!in_array($ExactName,$included_page_name))
            {
              header("Location:404.php");
            }
    } */   
    
}


function write_log($errorLebel,$errormsg,$duserName,$query)
{
$new_path = "files/".DATABASE_Name;
if(is_dir($new_path)) { //echo "The Directory $new_path exists";
} else {mkdir($new_path, 0777,true);}
$cdate=date("Ymd"); 
$log_file_name=$new_path.'/access_'.$cdate.".log";
$lcdate=date('d/m/Y H:i:s');  $duserName1="By-".$duserName;
switch($errorLebel)
{
    case 0:
      $level='OFF';  
     $str="";   
    break;    
    case 1:
     $level='SUCCESS';   
     $str="$lcdate | $level | $errormsg | $duserName1 | $query".PHP_EOL;;   
    break;    
    case 2:
      $level='INFO';  
     $str="$lcdate | $level | $errormsg | $duserName1".PHP_EOL;   
    break;   
    case 3:
     $level='WARNING';     
     $str="$lcdate | $level | $errormsg | $duserName1".PHP_EOL;   
    break;
    case 4:
      $level='ERROR';
         $str="$lcdate | $level | $errormsg | $duserName1".PHP_EOL;   
        break;   
    case 5:
        $level='ERROR-SQL';
         $str="$lcdate | $level | $errormsg | $duserName1 | $query".PHP_EOL;   
        break;
    default:
      $level='OFF';    
      $str="";    
}

$fp = fopen($log_file_name,'a+');
fwrite($fp,($str));
fclose($fp);
}
?>
