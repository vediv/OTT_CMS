<?php
include_once 'corefunction.php';
include_once("config.php");
$action=trim($_REQUEST['action']);
switch($action)
{
    case "category_in_add_new_catgory_old":
    $filter = null; $pager = null;
    $res = $client->category->listAction($filter, $pager);
    $totalCategoryCount=$res->totalCount;
    $category_id_from_table=array();    
    $qry="SELECT category_id  FROM categories";
    $que = db_select($conn,$qry);
    foreach($que as $fetchc)
    {
        $category_id_from_table[]=$fetchc['category_id'];
    } 
    include_once("setting_functions.php");
    $getCategoryLevel=getCategoryLevel();
    ?>
    <link href="dist/css/navAccordion.css" rel="stylesheet" type="text/css"> 
    <div id="sidebar" class="well sidebar-nav" style="border: 1px solid #c7d1dd;max-height:160px;overflow:hidden;overflow-y:scroll;"> 
    <div class="mainNav" style="border: 1px solid #c7d1dd ;">
       <div style="margin: 0px 0 9px 0px !important; " >
       <?php if($getCategoryLevel!=''){ ?>  <input type="radio" name="category_value"  id="example-getting-started"  value="0" style="margin: 3px 23px 10px 6px  !important"> <?php } ?>
          NO PARENT
       </div>
       <ul>
        <?php
        if($totalCategoryCount!=0)
        {
        $default_categorieID=$res->objects[0]->id;
        $get_categorieID=isset($_GET['catID']) ? $_GET['catID'] : $default_categorieID;
        $action_cat=isset($_GET['action']) ? $_GET['action'] : '';
        $parentCatid=isset($_GET['parentCatid']) ? $_GET['parentCatid'] : '';
        $id = $get_categorieID;
        $result_cat = $client->category->get($id);
        $categorieName=$result_cat->fullName;
        $count=1;
        foreach ($res->objects as $entry_categorie) {                                    
        $directSubCategoriesCount=$entry_categorie->directSubCategoriesCount;           
        $parentId=$entry_categorie->parentId; $categoryID=$entry_categorie->id; 
        $directEntriesCount=$entry_categorie->directEntriesCount;
        $categoriesName=$entry_categorie->name;
        if(in_array($categoryID, $category_id_from_table)){
        if(($parentId)==0)
        {
        ?>    
            <li>
            <?php if($getCategoryLevel > 0 && $getCategoryLevel < 7){ ?><input type="radio" name="category_value"  id="example-getting-started"  value="<?php echo $categoryID; ?>"><?php } ?>	
            <a href="#"> <?php echo strtoupper($categoriesName);?> </a>
            <ul class="mainNav-scroll">
            <?php
            $filter = new KalturaCategoryFilter();
            $filter->advancedSearch = null;
            $filter->parentIdEqual = $categoryID;
            $filter->parentIdIn = '';
            $filter->appearInListEqual = null;
            $filter->statusEqual = null;
            $pager = null;
            $result_sub_categorie = $client->category->listAction($filter, $pager);
            $total_sub_categorie=$result_sub_categorie->totalCount;
            if($total_sub_categorie>0)
            {
                $c=1;
                foreach ($result_sub_categorie->objects as $entry_sub_categorie) {
                $subcategoriName=$entry_sub_categorie->name; $subcategori_ID=$entry_sub_categorie->id;
                //$bgcolor_sub=$subcategori_ID==$get_categorieID ? "red" :""; 
                if(in_array($subcategori_ID, $category_id_from_table)){
            ?>	
            <li style="border-top: 0 solid #444 !important;  border-bottom: 1px solid #888 !important;  color: #888!important;    font-size: 13px;">
            <?php if($getCategoryLevel > 1 && $getCategoryLevel < 7){ ?><input type="radio" name="category_value"  id="example-getting-started"  value="<?php echo $subcategori_ID; ?>"> <?php } ?>
            <a href="#"> <?php echo $subcategoriName; ?></a>
            <ul>
            <?php 
            //$filter = new KalturaCategoryFilter();
            $filter->advancedSearch = null;
            $filter->parentIdEqual = $subcategori_ID;
            $filter->parentIdIn = '';
            $filter->appearInListEqual = null;
            $filter->statusEqual = null;
            $pager = null;
            $result_sub_sub_categorie = $client->category->listAction($filter, $pager);
            // print '<pre>'.print_r($result_sub_sub_categorie, true).'</pre>';
            $total_sub_sub_categorie=$result_sub_sub_categorie->totalCount;
            foreach ($result_sub_sub_categorie->objects as $entry_sub_sub_categorie) {
            $sub_subcategoriName=$entry_sub_sub_categorie->name; $sub_sub_categori_ID=$entry_sub_sub_categorie->id;
            if(in_array($sub_sub_categori_ID, $category_id_from_table)){
            ?>	   
            <li style="padding-left: 2em !important;" >
            <?php if($getCategoryLevel >2 && $getCategoryLevel < 7){ ?><input type="radio" name="category_value"  id="example-getting-started"  value="<?php echo $sub_sub_categori_ID; ?>"><?php } ?>
            <a href="#"><?php echo $sub_subcategoriName; ?> </a>
            <ul>
            <?php 
            //$filter = new KalturaCategoryFilter();
            $filter->advancedSearch = null;
            $filter->parentIdEqual = $sub_sub_categori_ID;
            $filter->parentIdIn = '';
            $filter->appearInListEqual = null;
            $filter->statusEqual = null;
            $pager = null;
            $result_level3 = $client->category->listAction($filter, $pager);
            $total_category_level3=$result_level3->totalCount;
            foreach ($result_level3->objects as $entry_level3) {
            $categoryName_level3=$entry_level3->name; $categoryid_level3=$entry_level3->id;
           if(in_array($categoryid_level3, $category_id_from_table)){
            ?>	   
            <li style="padding-left: 2em !important;" >
            <?php if($getCategoryLevel > 3 && $getCategoryLevel < 7){ ?><input type="radio" name="category_value"  id="example-getting-started"  value="<?php echo $categoryid_level3; ?>"><?php } ?>
            <a href="#"><?php echo $categoryName_level3; ?> </a>
                <ul>
                <?php 
                //$filter = new KalturaCategoryFilter();
                $filter->advancedSearch = null;
                $filter->parentIdEqual = $categoryid_level3;
                $filter->parentIdIn = '';
                $filter->appearInListEqual = null;
                $filter->statusEqual = null;
                $pager = null;
                $result_level4 = $client->category->listAction($filter, $pager);
                $total_category_level4=$result_level4->totalCount;
                foreach ($result_level4->objects as $entry_level4) {
                $categoryName_level4=$entry_level4->name; $categoryid_level4=$entry_level4->id;
                 if(in_array($categoryid_level4, $category_id_from_table)){
                ?>	   
                <li style="padding-left: 2em !important;" >
                <?php if($getCategoryLevel > 5 && $getCategoryLevel < 7){ }?>
                <!--<input type="radio" name="category_value"  id="example-getting-started"  value="<?php echo $categoryid_level4; ?>">-->
                <a href="#"><?php echo $categoryName_level4; ?> </a>
                </li>
                <?php } } ?>	
                </ul>  
                </li>
            <?php } } ?>	
                </ul>
                </li>
            <?php } } ?>	
                </ul>
                </li>
                <?php }  } } ?>
                </ul>
                </li>			
                <?php 
                  }
                  
        }
                }
                 ?>
                </ul>
                 <?php } ?>
                </div>
                </div>
                <script src="js/navAccordion.min.js" type="text/javascript"></script>
                <script type="text/javascript">
                                $(document).ready(function(){
                                        //Accordion Nav
                                        jQuery('.mainNav').navAccordion({
                                                expandButtonText: '<i class="fa fa-chevron-right"></i>',  //Text inside of buttons can be HTML
                                                collapseButtonText: '<i class="fa fa-chevron-down"></i>'
                                        }, 
                                        function(){
                                                //console.log('Callback')
                                        });

                                });
                </script>
                <?php 
    include_once 'ksession_close.php';
    break;
    case "category_in_add_new_catgory":
    $category_id_from_table=array();    
    $qry="SELECT id FROM kaltura.category where partner_id='$partnerID' and status='2'";
    $que = db_select($conn,$qry);
    foreach($que as $fetchc)
    {
        $category_id_from_table[]=$fetchc['id'];
    } 
    include_once("setting_functions.php");
    $getCategoryLevel=getCategoryLevel();
    $kcategory="select catid,category_id,parent_id,partner_id,cat_name,
        fullname,fullids,entry_count,direct_sub_categories_count,status,priority,direct_entries_count,created_at
        from categories  where status!='3'";
    $totalCategoryCount= db_totalRow($conn, $kcategory);
    $fetchCat= db_select($conn, $kcategory);
    ?>
    <link href="dist/css/navAccordion.css" rel="stylesheet" type="text/css"> 
    <div id="sidebar" class="well sidebar-nav" style="border: 1px solid #c7d1dd;max-height:160px;overflow:hidden;overflow-y:scroll;"> 
    <div class="mainNav" style="border: 1px solid #c7d1dd ;">
       <div style="margin: 0px 0 9px 0px !important; " >
       <?php if($getCategoryLevel!=''){ ?>  <input type="radio" name="category_value"  id="example-getting-started"  value="0" style="margin: 3px 23px 10px 6px  !important"> <?php } ?>
          NO PARENT
       </div>
       <ul>
        <?php
        if($totalCategoryCount!=0)
        {
        $count=1;
        foreach ($fetchCat as $entry_categorie) {                                    
        $directSubCategoriesCount=$entry_categorie['direct_sub_categories_count'];           
        $parentId=$entry_categorie['parent_id']; $categoryID=$entry_categorie['category_id']; 
        $directEntriesCount=$entry_categorie['direct_entries_count'];
        $categoriesName=$entry_categorie['cat_name'];
        if(in_array($categoryID, $category_id_from_table)){
        if(($parentId)==0)
        {
        ?>    
            <li>
            <?php if($getCategoryLevel > 0 && $getCategoryLevel < 7){ ?><input type="radio" name="category_value"  id="example-getting-started"  value="<?php echo $categoryID; ?>"><?php } ?>	
            <a href="#"> <?php echo strtoupper($categoriesName);?> </a>
            <ul class="mainNav-scroll">
            <?php
            $result_sub_categorie="select category_id,cat_name from categories where parent_id='$categoryID' and status!='3' ";
            $total_sub_categorie= db_totalRow($conn, $result_sub_categorie);
            $fetch_sub_categorie= db_select($conn, $result_sub_categorie);
            if($total_sub_categorie>0)
            {
                $c=1;
                foreach ($fetch_sub_categorie as $entry_sub_categorie) {
                $subcategoriName=$entry_sub_categorie['cat_name']; $subcategori_ID=$entry_sub_categorie['category_id'];
                if(in_array($subcategori_ID, $category_id_from_table)){
            ?>	
            <li style="border-top: 0 solid #444 !important;  border-bottom: 1px solid #888 !important;  color: #888!important;    font-size: 13px;">
            <?php if($getCategoryLevel > 1 && $getCategoryLevel < 7){ ?><input type="radio" name="category_value"  id="example-getting-started"  value="<?php echo $subcategori_ID; ?>"> <?php } ?>
            <a href="#"> <?php echo $subcategoriName; ?></a>
            <ul>
            <?php 
            $result_sub_sub_categorie="select category_id,cat_name from categories where parent_id='$subcategori_ID' and status!='3' ";
            $total_sub_sub_categorie= db_totalRow($conn, $result_sub_sub_categorie);
            $fetch_sub_sub_categorie= db_select($conn, $result_sub_sub_categorie);
            foreach ($fetch_sub_sub_categorie as $entry_sub_sub_categorie) {
            $sub_subcategoriName=$entry_sub_sub_categorie['cat_name']; $sub_sub_categori_ID=$entry_sub_sub_categorie['category_id'];
            if(in_array($sub_sub_categori_ID, $category_id_from_table)){
            ?>	   
            <li style="padding-left: 2em !important;" >
            <?php if($getCategoryLevel >2 && $getCategoryLevel < 7){ ?><input type="radio" name="category_value"  id="example-getting-started"  value="<?php echo $sub_sub_categori_ID; ?>"><?php } ?>
            <a href="#"><?php echo $sub_subcategoriName; ?> </a>
            <ul>
            <?php 
            
            $result_level3="select category_id,cat_name from categories where parent_id='$sub_sub_categori_ID' and status!='3' ";
            $total_category_level3= db_totalRow($conn, $result_level3);
            $fetch_result_level3= db_select($conn, $result_level3);
            
            foreach ($fetch_result_level3 as $entry_level3) {
            $categoryName_level3=$entry_level3['cat_name']; $categoryid_level3=$entry_level3['category_id'];
           if(in_array($categoryid_level3, $category_id_from_table)){
            ?>	   
            <li style="padding-left: 2em !important;" >
            <?php if($getCategoryLevel > 3 && $getCategoryLevel < 7){ ?><input type="radio" name="category_value"  id="example-getting-started"  value="<?php echo $categoryid_level3; ?>"><?php } ?>
            <a href="#"><?php echo $categoryName_level3; ?> </a>
                <ul>
                <?php 
                
                $result_level4="select category_id,cat_name from categories where parent_id='$categoryid_level3' and status!='3' ";
                $total_category_level3= db_totalRow($conn, $result_level4);
                $fetch_result_level4= db_select($conn, $result_level4);
                foreach ($fetch_result_level4 as $entry_level4) {
                $categoryName_level4=$entry_level4['cat_name']; $categoryid_level4=$entry_level4['category_id'];
                if(in_array($categoryid_level4, $category_id_from_table)){
                ?>	   
                <li style="padding-left: 2em !important;" >
                <?php if($getCategoryLevel > 5 && $getCategoryLevel < 7){ }?>
                <!--<input type="radio" name="category_value"  id="example-getting-started"  value="<?php echo $categoryid_level4; ?>">-->
                <a href="#"><?php echo $categoryName_level4; ?> </a>
                </li>
                <?php } } ?>	
                </ul>  
                </li>
            <?php } } ?>	
                </ul>
                </li>
            <?php } } ?>	
                </ul>
                </li>
                <?php }  } } ?>
                </ul>
                </li>			
                <?php 
                  }
                  
        }
                }
                 ?>
                </ul>
                 <?php } 
                 sleep(1);
                 ?>
                </div>
                </div>
                <script src="js/navAccordion.min.js" type="text/javascript"></script>
                <script type="text/javascript">
                                $(document).ready(function(){
                                        //Accordion Nav
                                        jQuery('.mainNav').navAccordion({
                                                expandButtonText: '<i class="fa fa-chevron-right"></i>',  //Text inside of buttons can be HTML
                                                collapseButtonText: '<i class="fa fa-chevron-down"></i>'
                                        }, 
                                        function(){
                                                //console.log('Callback')
                                        });

                                });
                </script>
                <?php 
                //include_once 'ksession_close.php';
            break;
            case "tags_in_add_new_category":
                ?>
            <link href="dist/css/custom.css" rel="stylesheet" type="text/css" />
            <input id="tags_1" type="text" class="tags form-control"  name="tags_1"  size="100"  placeholder="Enter tags :eg red,green,blue" />
            <script src="dist/jquery.tagsinput/src/jquery.tagsinput.js" type="text/javascript"></script>
            <script src="dist/js/custom.js" type="text/javascript"></script> 
    <?php  
    break;
    case "tags_in_metaData":
    $entryid=trim($_POST['entryid']);
    $selEntry="select tag from entry where entryid='".$entryid."'";
    $f= db_select($conn,$selEntry);$tags=$f[0]['tag'];
    ?>    
    <link href="dist/css/custom.css" rel="stylesheet" type="text/css" />
    <input id="tags_1" type="text" class="tags form-control"  name="entrytags" value="<?php echo $tags; ?>"  placeholder="Enter tags :eg red,green,blue" />
    <script src="dist/jquery.tagsinput/src/jquery.tagsinput.js" type="text/javascript"></script>
    <script src="dist/js/custom.js" type="text/javascript"></script> 
    <?php 
    break;
    case "tags_in_ugcmetaData":
    $entryid=trim($_POST['entryid']);
    $selEntry="select tag from ugc_entry where id='".$entryid."'";
    $f= db_select($conn,$selEntry);$tags=$f[0]['tag'];
    ?>    
    <link href="dist/css/custom.css" rel="stylesheet" type="text/css" />
    <input id="tags_1" type="text" class="tags form-control"  name="entrytags" value="<?php echo $tags; ?>"  placeholder="Enter tags :eg red,green,blue" />
    <script src="dist/jquery.tagsinput/src/jquery.tagsinput.js" type="text/javascript"></script>
    <script src="dist/js/custom.js" type="text/javascript"></script> 
    <?php 
    break;
    case "tags_in_live_metaData":
    $entryid=trim($_POST['entryid']);
    if($entryid!=''){    
    $selEntry="select tag from entry where entryid='".$entryid."'";
    $f= db_select($conn,$selEntry);$tags=$f[0]['tag'];
    }
    else{
        $tags='';
    }
    ?>    
    <link href="dist/css/custom.css" rel="stylesheet" type="text/css" />
    <input id="tags_1" type="text" class="tags form-control"  name="entrytags" value="<?php echo $tags; ?>"  placeholder="Enter tags :eg red,green,blue" />
    <script src="dist/jquery.tagsinput/src/jquery.tagsinput.js" type="text/javascript"></script>
    <script src="dist/js/custom.js" type="text/javascript"></script> 
   <?php 
    break;
    case "category_in_metaData_old":
    $category_id_from_table=array();    
    $qry="SELECT category_id  FROM categories";
    $que = db_select($conn,$qry);
    foreach($que as $fetchc)
    {
        $category_id_from_table[]=$fetchc['category_id'];
    }     
    $entryid=trim($_POST['entryid']);    
    $result = $client->baseEntry->get($entryid, $version);
    $categoriesIds=$result->categoriesIds;
    $categoriesIdsdlist = explode(',',$categoriesIds);  
    $filter = null;
    $pager = null;
    $res = $client->category->listAction($filter, $pager);
    ?>
    <select id="category_value" class="category_all"  multiple="multiple">
   <?php foreach ($res->objects as $entry_categorie) {                                    
             $fullName=$entry_categorie->fullName;  $categoryID=$entry_categorie->id; 
             if(in_array($categoryID, $category_id_from_table)){
              $categoriesName=$entry_categorie->name; 
              $sel='';
              if(in_array($categoryID, $categoriesIdsdlist)) {$sel="selected"; } 
     ?>   
       <option value="<?php echo $categoryID;?>" <?php echo $sel; ?> ><?php echo $fullName ?></option>
             <?php } } ?>
   </select>
   <link rel="stylesheet" href="js/bootstrap-multiselect.css" type="text/css">
   <script type="text/javascript" src="js/bootstrap-multiselect.js"></script>
   <script type="text/javascript">
      $('.category_all').multiselect({ 
            includeSelectAllOption: true,
            buttonWidth: 310,
            enableFiltering: true });
  
    </script>
  <?php 
  include_once 'ksession_close.php';
  break; 
  case "category_in_metaData":
   $filter_name=(isset($_GET['filter_name']))? $_GET['filter_name']: "";
   $where='';    
   if($filter_name!='')
            {
              $where=" and fullname LIKE '%$filter_name%' ";
      }
    $query="select category_id,fullname from categories where  direct_sub_categories_count='0'  $where limit 10";
    $fetchData= db_query($conn,$query);
    $result = array();$rows=array(); 
    foreach ($fetchData as $row) { 
    $rows['category_id']=$row['category_id'];
    $rows['fullname']=$row['fullname'];
    $result[]=$rows;
    }    
    echo json_encode($result);
   break;
   case "category_in_youtube_case":
   $filter_name=(isset($_GET['filter_name']))? $_GET['filter_name']: "";
   $where=" WHERE cat_name='YT CASE' ";    
   if($filter_name!='')
            {
              $where=" where (cat_name='YT CASE') and (fullname LIKE '%$filter_name%' OR cat_name LIKE '%$filter_name%')  ";
      }
    //$query="select category_id,fullname from categories  $where limit 10";
    $query="SELECT category_id,fullname FROM categories $where";
    $fetchData= db_query($conn,$query);
    $result = array();$rows=array(); 
    foreach ($fetchData as $row) { 
    $rows['category_id']=$row['category_id'];
    $rows['fullname']=$row['fullname'];
    $result[]=$rows;
    }    
    echo json_encode($result);
  break;
  case "countryCode_in_metaData":
  $entryid=trim($_POST['entryid']);      
  include_once('countries.php');
  $selEntry="select country_data from entry where entryid='".$entryid."'";
  $f= db_select($conn,$selEntry);
  $country_data=$f[0]['country_data'];
  $country_data_expload=explode(",", $country_data);
   ?>
  <select name="SelectList[]" id="SelectList" size="8" multiple="multiple" style="width:100%; height:200px;">
  <?php foreach ($countries as $key => $value) {
      if (!in_array($key, $country_data_expload)){
   ?>   
   <option value="<?php echo $key;?>"  ><?php echo $value." (".$key.")" ?></option>
      <?php } } ?>
  </select>  
   
   <?php
    break; 
    case "player_in_metaData":
    $entryid=trim($_POST['entryid']);    
    $result = $client->baseEntry->get($entryid, $version);
    $thumbnailUrl=$result->thumbnailUrl; $dataUrl=$result->dataUrl;
    $sese="select downloadURL,puser_id from entry where entryid='".$entryid."'";
    $fff= db_select($conn,$sese);
    $downloadURL=$fff[0]['downloadURL']; $puser_id=$fff[0]['puser_id'];
    $ses="select name from publisher where par_id='".$puser_id."'";
    $ff= db_select($conn,$ses);
    $Creator=$ff[0]['name']; 
    $createdAt=$result->createdAt;
    
    
    $plays=$result->plays;  $duration=$result->duration; 
    $msDuration=$result->msDuration ; $mediaType=$result->mediaType;   
    $mdeitype= $mediaType==1 ? "video" : ""; $moderationStatus=$result->moderationStatus;
    $moderationStatus_main= $moderationStatus==6 ? "Auto approved" : "";
    /*include_once'function.inc.publisher.php';
    $select="select cdnURL,cdnDIR from ott_publisher.cdn_details where publisherID='".$publisher_unique_id."'";
    $fmain= db_select1($select);
     $cdnURL=$fmain[0]['cdnURL']; $cdnDIR=$fmain[0]['cdnDIR'];
     $video_play_url=$cdnURL."/".$cdnDIR."/".$entryid."/a.m3u8";
     */
    ?>
   <!--<link href="player_css/functional.css" rel="stylesheet" type="text/css" />
   <script src="player_js/flowplayer.min.js" type="text/javascript"></script>
   <script src="player_js/flowplayer.mpegdash.min.js" type="text/javascript"></script>
   -->
   <link rel="stylesheet" href="layouts/css/flowplayer.css"/>
   <script type="text/javascript" src="layouts/js/flowplayer.js"></script>
   <script type="text/javascript" src="layouts/js/flowplayer.hlsjs.light.min.js"></script>
   <div id="fp-dash" class="is-closeable" style="background-image:url(<?php echo $thumbnailUrl."/height/100/width/200"; ?>);"></div>  
    <p> <strong>Creator </strong>  : <?php echo $Creator; ?></p>
    <p><strong>Created on :</strong> <?php echo gmdate("d/m/y", $createdAt); ?></p>
   <hr/>
   <p><strong>Type : </strong>  <?php echo $mdeitype; ?>   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Duration : </strong><?php echo gmdate("H:i:s", $duration); ?></p> 
   <!--<p><strong>Moderation : </strong><?php //echo $moderationStatus_main; ?>      </p>--> 
   <script type="text/javascript">
        var dataurl="<?php echo $downloadURL; ?>";
        //var dataUrl=dataurl+"/a.m3u8";
        //var resDataUrl = dataUrl.replace("url", "applehttp");
        flowplayer("#fp-dash", {
           splash: true,
           ratio: 9/16,
           clip: {
           sources: [
           { 
              type: "application/x-mpegurl",
              src:dataurl
           },
        ]
        },
        embed: false
        }).on("ready", function (e, api, video) {
        // document.querySelector("#fp-dash .fp-title").innerHTML =
         //api.engine.engineName + " engine playing " + video.type;

        });
   </script>
   <?php
    include_once 'ksession_close.php';
    break;    
    case "player_in_live_metaData":
    $entryid=trim($_POST['entryid']);    
    $result_live = $client->baseEntry->get($entryid, $version);
    $primaryBroadcastingUrl=$result_live->primaryBroadcastingUrl;
    $thumbnailUrl=$result_live->thumbnailUrl;
    ?>
   <!--<link rel="stylesheet" href="player_css/flowplayer.css"/>
   <script type="text/javascript" src="player_css/js/flowplayer.js"></script>
   <script type="text/javascript" src="player_css/js/flowplayer.hlsjs.light.min.js"></script>-->
   <link rel="stylesheet" href="layouts/css/flowplayer.css"/>
   <script type="text/javascript" src="layouts/js/flowplayer.js"></script>
   <script type="text/javascript" src="layouts/js/flowplayer.hlsjs.light.min.js"></script>
   <script type="text/javascript">
    $("#watch-video").html();
    var primaryBroadcastingUrl="<?php echo $primaryBroadcastingUrl;  ?>"
     flowplayer("#watch-video", {
            splash: false,
            ratio: 9/16,
            autoplay:true,
            clip: {
                // enable hlsjs in desktop Safari for manual quality selection
                // CAVEAT: may cause decoding problems with some streams!
                hlsjs: {
                    safari: true
                },
                live: true,
                sources: [
                    { type: "application/x-mpegurl",
                        src: primaryBroadcastingUrl }
                ]
            }

        });
    
    </script>
   <?php
   include_once 'ksession_close.php';
   break;    
  case "category_in_youtube":
    $category_id_from_table=array();    
    $qry="SELECT category_id  FROM categories";
    $que = db_select($conn,$qry);
    foreach($que as $fetchc)
    {
        $category_id_from_table[]=$fetchc['category_id'];
    }    
    $filter = null;
    $pager = null;
    $res = $client->category->listAction($filter, $pager); 
    ?>
    <select id="category_value" class="category_all" multiple="multiple">
   <?php foreach ($res->objects as $entry_categorie) {                                    
             $fullName=$entry_categorie->fullName;  $categoryID=$entry_categorie->id;           
              $categoriesName=$entry_categorie->name; 
              if(in_array($categoryID, $category_id_from_table)){
     ?>   
       <option value="<?php echo $categoryID;?>" <?php //echo $sel; ?> ><?php echo $fullName ?></option>
   <?php } } ?>
   </select>
   <link rel="stylesheet" href="js/bootstrap-multiselect.css" type="text/css">
   <script type="text/javascript" src="js/bootstrap-multiselect.js"></script>
   <script type="text/javascript">
      $('.category_all').multiselect({ 
            includeSelectAllOption: true,
            buttonWidth: 350,
            enableFiltering: true });
  
    </script>
  <?php
  include_once 'ksession_close.php'; 
  break;
  case "countryCode_in_youtube_add":
  include_once('countries.php');
  ?>
  <select id="country_code" class="boot-multiselect-demo" multiple="multiple">
  <?php foreach ($countries as $key => $value) {
   ?>   
      <option value="<?php echo $key;?>" <?php //echo $sel; ?> ><?php echo $value ?></option>
  <?php } ?>
</select>  
   <link rel="stylesheet" href="js/bootstrap-multiselect.css" type="text/css">
   <script type="text/javascript" src="js/bootstrap-multiselect.js"></script>
   <script type="text/javascript">
     $('.boot-multiselect-demo').multiselect({ 
            includeSelectAllOption: true,
            buttonWidth: 310,
            enableFiltering: true });
     
    </script>        
   <?php     
    break; 
    case "getHeaderMenu";
    $categoryid=trim($_POST['categoryid']);    
    $qry="SELECT category_id,header_name  FROM header_menu where header_status='1' and menu_type='h' order by header_name";
    $fetch = db_select($conn,$qry);
    $totalRow = db_totalRow($conn,$qry);
    if($totalRow==0){ echo 0; exit;  };
    ?>
    <select id="category_value"  style="width: 350px;">
   <?php foreach ($fetch as $fetchCat) {                                    
             $category_id=$fetchCat['category_id'];  $header_name=$fetchCat['header_name']; 
             $sel=$category_id==$categoryid?'selected':'';
    ?>   
        <option value="<?php echo $category_id;?>" <?php echo $sel;  ?>  ><?php echo $header_name; ?></option>
             <?php }  ?>
   </select>
    <?php 
    break;
    case "category_view_entry":
    //sleep(1);
    $categoryID=$_POST['categoryID'];
    $pagelimit = (isset($_POST['limitval']))? $_POST['limitval']: "10";    
    $filter = new KalturaMediaEntryFilter();
    $filter->categoriesIdsMatchAnd = $categoryID;
    $pager = new KalturaFilterPager();
    $pager->pageSize = $pagelimit;
    $pager->pageIndex = (isset($_POST['pageNum']))? $_POST['pageNum']: 1;
    $filter->statusIn = '-1,-2,0,1,2,4';
    $filter->orderBy= '-createdAt';
    $result_entry = $client->media->listAction($filter, $pager);
    $total_pages=$result_entry->totalCount;
    $limit = $pagelimit;
    if($pager->pageIndex) 
            $start = ($pager->pageIndex - 1) * $limit; 			
    else
            $start = 0;
    ?>
    <table class="table table-bordered table-striped" >
            <thead>
            <tr>
              <th>Thumbnail</th> <th>Entry ID</th> <th>Entry Name</th> <th>Created-Date</th> <th>Status</th>  <th width="10%">Action</th>
            </tr> 
            </thead>
            <tbody>
                <?php
                $count=1;
                //print '<pre>'.print_r($result_entry, true).'</pre>';
                foreach($result_entry->objects as $entry) 
                {  
                   $name=$entry->name; $categoriesIds=$entry->categoriesIds;
                   $thumbnailUrl=$entry->thumbnailUrl;
                   $entry_id=$entry->id;
                   $createdAt=$entry->createdAt;
                   $status=$entry->status;  
                   $mediaType=$entry->mediaType;
                   //$mdeitype= $mediaType==1 ? "video" : "";
                if($status=='-1') { $statusc="error_converting"; }
                if($status=='-2') { $statusc="error_importing"; }
                if($status==2) { $statusc="Ready"; }
                if($status==0) { $statusc="import"; }
                if($status==1) { $statusc="converting"; }
                if($status==2) { $statusc="Ready"; }
                if($status==4) { $statusc="Pending"; }
                ?>
                <tr id="rmv<?php echo $count; ?>">
                <td>
                    <img class="img-responsive customer-img"  src="<?php echo $thumbnailUrl; ?>" height="30" width="70" /></td>
                <td><?php echo $entry_id;?></td>
                <td><?php echo $name;?></td>
                <td><?php echo gmdate("d/m/y", $createdAt); ?></td>
                <td> <span class="label label-<?php echo $status==2?"success":"danger";?> label-white middle" style="cursor:pointer;"><?php echo $statusc; ?></span>
                  </td>
                <td><?php  if(in_array(4, $UserRight)){ ?>       
                    <a href="javascript:"  class="delete" title="Delete" onclick="delete_category_entry('<?php echo $categoryID; ?>','<?php echo $entry_id ?>','<?php echo $publisher_unique_id;  ?>','<?php echo $pager->pageIndex ;  ?>','<?php echo $pagelimit;  ?>')"><span class="glyphicon glyphicon-trash"></span></a>
                <?php } ?>
                </td>
                <?php  $count++; }  include_once 'ksession_close.php'; ?>
                </tr>  
                </ul>   

                </tbody>
                </table>
                <?php  /* new paging code............*/
$page = 1;					//if no page var is given, default to 1.
$prev = $pager->pageIndex - 1;							//previous page is page - 1
$next = $pager->pageIndex + 1;
$limit=$pager->pageSize;							//next page is page + 1
$lastpage = ceil($total_pages/$limit);
$adjacents = 2;				
$lpm1 = $lastpage - 1;					
$pagination = "";
if($lastpage > 1)
{	
$pagination .= "<div class=\"pagination\">"; 
    //previous button
    if ($pager->pageIndex > 1)   
     $pagination.= '<a href="javascript:void(0)" onclick="changePagination_view_category_entry(\''.$prev.'\',\''.$limit.'\',\''.$categoryID.'\')">Previous</a>';		
    else
            $pagination.= "<span class=\"disabled\"> Previous</span>";	
    //pages	
    if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
    {	
            for ($counter = 1; $counter <= $lastpage; $counter++)
            {
                    ?>
            <?php 	if ($counter == $pager->pageIndex)
                            $pagination.= "<span class=\"current\">$counter</span>";
                    else

                        $pagination.= '<a href="javascript:void(0)" onclick="changePagination_view_category_entry(\''.$counter.'\',\''.$limit.'\',\''.$categoryID.'\')">'.$counter.'</a>';		

            }
    }
    elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
    {
            //close to beginning; only hide later pages
            if($pager->pageIndex < 1 + ($adjacents * 2))		
            {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                    {
                            if ($counter == $pager->pageIndex)
                                    $pagination.= "<span class=\"current\">$counter</span>";
                            else
                                    //$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";	
                                $pagination.= '<a href="javascript:void(0)" onclick="changePagination_view_category_entry(\''.$counter.'\',\''.$limit.'\',\''.$categoryID.'\')">'.$counter.'</a>';				
                    }
                    $pagination.= "...";
                    //$pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>"; 
                    $pagination.= '<a href="javascript:void(0)" onclick="changePagination_view_category_entry(\''.$lpm1.'\',\''.$limit.'\',\''.$categoryID.'\')">'.$lpm1.'</a>';

                    //$pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";	
                    $pagination.= '<a href="javascript:void(0)" onclick="changePagination_view_category_entry(\''.$lastpage.'\',\''.$limit.'\',\''.$categoryID.'\')">'.$lastpage.'</a>';	
            }
            //in middle; hide some front and some back
            elseif($lastpage - ($adjacents * 2) > $pager->pageIndex && $pager->pageIndex > ($adjacents * 2))
            {

                    $pagination.= '<a href="javascript:void(0)" onclick="changePagination_view_category_entry(\'1\',\''.$limit.'\',\''.$categoryID.'\')">1</a>';	
                    $pagination.= '<a href="javascript:void(0)" onclick="changePagination_view_category_entry(\'2\',\''.$limit.'\',\''.$categoryID.'\')">2</a>';
                    $pagination.= "...";
                    for ($counter = $pager->pageIndex - $adjacents; $counter <= $pager->pageIndex + $adjacents; $counter++)
                    {
                            if ($counter == $pager->pageIndex)
                                    $pagination.= "<span class=\"current\">$counter</span>";
                            else

                            $pagination.= '<a href="javascript:void(0)" onclick="changePagination_view_category_entry(\''.$counter.'\',\''.$limit.'\',\''.$categoryID.'\')">'.$counter.'</a>';				
                    }
                    $pagination.= "...";
                    $pagination.= '<a href="javascript:void(0)" onclick="changePagination_view_category_entry(\''.$lpm1.'\',\''.$limit.'\',\''.$categoryID.'\')">'.$lpm1.'</a>';
                    $pagination.= '<a href="javascript:void(0)" onclick="changePagination_view_category_entry(\''.$lastpage.'\',\''.$limit.'\',\''.$categoryID.'\')">'.$lastpage.'</a>';	
            }
            //close to end; only hide early pages
            else
            {

                    $pagination.= '<a href="javascript:void(0)" onclick="changePagination_view_category_entry(\'1\',\''.$limit.'\',\''.$categoryID.'\')">1</a>';	

                    $pagination.= '<a href="javascript:void(0)" onclick="changePagination_view_category_entry(\'2\',\''.$limit.'\',\''.$categoryID.'\')">2</a>';
                    $pagination.= "...";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                    {
                            if ($counter == $pager->pageIndex)
                                    $pagination.= "<span class=\"current\">$counter</span>";
                            else

                                    $pagination.= '<a href="javascript:void(0)" onclick="changePagination_view_category_entry(\''.$counter.'\',\''.$limit.'\',\''.$categoryID.'\')">'.$counter.'</a>';					
                    }
            }
    }

    //next button
    if ($pager->pageIndex < $counter - 1) 
        $pagination.= '<a href="javascript:void(0)" onclick="changePagination_view_category_entry(\''.$next.'\',\''.$limit.'\',\''.$categoryID.'\')">Next</a>';	 
    else
            $pagination.= "<span class=\"disabled\">Next </span>";
    $pagination.= "</div>\n";		
	}
     ?>
    <div class="row" style="border: 0px solid red; padding: 5px 5px 5px 5px;">
    <div class="pull-left"  style="border: 0px solid red;">
       
      <?php if($pager->pageIndex ==1) { 
       $startShow=1; 
       $lmt=$limit<$total_pages ? $limit :$total_pages;
       }
      else 
      { 
             $startShow=(($pager->pageIndex - 1) * $limit)+1;
             $lmt=($pager->pageIndex*$limit) >$total_pages ? $total_pages: $pager->pageIndex * $limit;
      }
    ?>
   <?php if($total_pages>0){ ?>     
     Showing <?php echo $startShow; ?> to <?php echo $lmt; ?> of <strong>  <?php echo $total_pages; ?> </strong> entries
   <?php } ?>
    </div>
    <div class="pull-right"><?php echo $pagination;?></div>   
    </div> 
    <?php 
    //echo 1;
    break;  
    case "country_currency":
    $countryCode=$_POST['country_code'];
    $qry="SELECT country_code,name,currency FROM countries_currency where status='1' order by name";
    $fetch = db_select($conn,$qry);
    $totalRow = db_totalRow($conn,$qry);
    if($totalRow==0){ echo 0; exit;  };
    ?>
    <select id="country_code"  style="width:225px;" onchange="showCountryCode(this);">
    <option value="">--Select Country--</option>    
    <?php foreach ($fetch as $fetchCat) {                                    
           $country_code=$fetchCat['country_code'];  $name=$fetchCat['name'];  $currency=$fetchCat['currency'];
           $sel=$country_code==$countryCode?'selected':'';
    ?>   
    <option value="<?php echo $currency."_".$country_code;?>" <?php echo $sel;  ?>  ><?php echo $name; ?></option>
    <?php }  ?>
    </select>    
    <span class="help-block has-error" id="country-error" style="color:red;"></span>
   <?php      
    break;
    
 }
?>

