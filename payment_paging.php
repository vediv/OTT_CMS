<?php 
include_once 'corefunction.php';
include_once("function.php");
$searchKeword = (isset($_POST['searchInputall']))? $_POST['searchInputall']: "";
$page =(isset($_POST['pageNum']))? $_POST['pageNum']: 0;
$pagelimit = (isset($_POST['limitval']))? $_POST['limitval']: 10;
$action = (isset($_POST['action'])) ? $_POST['action']: "";
switch($action)
{
     case "save_new_plan":
     $plan_name =trim($_POST['planName']); $plan_duration =trim($_POST['plan_duration']);  $plan_amount =trim($_POST['plan_amount']);
     $plan_desc= db_quote_new($conn,trim($_POST['plan_desc'])); $plantype=($_POST['plantype']);
     $pin = unique_planID();  $Plan_unique_id=trim($pin);
     $que="select count(planID) as totalcount from plandetail where planID='$Plan_unique_id'";
     $pp=  db_select($conn,$que);
     $rowcount=$pp[0]['totalcount'];
      if($rowcount==1)
        {     
                unique_planID();
        }
        else{ 
        $plan_created_by=$get_user_id; // this is define in corefunction.php
        $Query_plan="insert into plandetail(planID,planName,pValue,pduration,pdescription,plan_added_date,plan_update_date,plan_created_by,planuniquename)
        values('$Plan_unique_id','$plan_name','$plan_amount','$plan_duration','$plan_desc',NOW(),NOW(),'$plan_created_by','$plantype')";
        $saveplan = db_query($conn,$Query_plan);
            if($saveplan)
             {
                /*----------------------------update log file begin-------------------------------------------*/
              $error_level=1;$msg="Add New Plan ($plan_name)"; $lusername=DASHBOARD_USER_NAME."_".PUBLISHER_UNIQUE_ID;
              $qry='';
              write_log($error_level,$msg,$lusername,$qry);
               echo 1;  
              /*----------------------------update log file End---------------------------------------------*/   
             }
            else 
             {
                 /*----------------------------update log file begin-------------------------------------------*/
                 $error_level=5;$msg="plan not Created($plan_name)"; $lusername=DASHBOARD_USER_NAME."_".PUBLISHER_UNIQUE_ID;
                  $qry=$Query_plan;
                  write_log($error_level,$msg,$lusername,$qry);
                echo 2; 
               die();
                /*----------------------------update log file End---------------------------------------------*/ 
            }
        
        }

   break;  
     case "save_edit_plan":
     $plan_name =trim($_POST['planName']); $plan_duration =trim($_POST['plan_duration']);  $plan_amount =trim($_POST['plan_amount']);
     $plan_desc= db_quote_new($conn,trim($_POST['plan_desc'])); $plantype=($_POST['plantype']);
     $planid=($_POST['planid']);
     $query3="update plandetail set planName='$plan_name',pValue='$plan_amount',pduration='$plan_duration',pdescription='$plan_desc',planuniquename='$plantype',plan_update_date=Now() where planID='$planid'";
     $q= db_query($conn,$query3);
     if($q)
        {
           /*----------------------------update log file begin-------------------------------------------*/
          $error_level=1;$msg="update plan Detail($plan_name)"; $lusername=DASHBOARD_USER_NAME."_".PUBLISHER_UNIQUE_ID;
          $qry='';
           write_log($error_level,$msg,$lusername,$qry);
         /*----------------------------update log file End---------------------------------------------*/   
        }
        else 
        {
            /*----------------------------update log file begin-------------------------------------------*/
            $error_level=5;$msg="update plan Detail Fail($plan_name)"; $lusername=DASHBOARD_USER_NAME."_".PUBLISHER_UNIQUE_ID;
            $qry=$query3;
            write_log($error_level,$msg,$lusername,$qry);
            echo 2;
            die();
           /*----------------------------update log file End---------------------------------------------*/ 
        }
    
   break;   
   
   
}

?>
<div class="box-header" >
    <div class="row" style="border: 0px solid red; margin: 0px 5px 10px 5px;">
    <table border='0' style="width:98%; margin-left: 10px;">
    <tr>
    <td width="15%">
    <select id="pagelmt"  style="width:60px;" onchange="selpagelimit('pagelmt','planDetail_paging.php','flash');" >
        <option value="10"  <?php echo $pagelimit==10? "selected":""; ?> >10</option>
        <option value="20"  <?php echo $pagelimit==20? "selected":""; ?> >20</option>
         <option value="50"  <?php echo $pagelimit==50? "selected":""; ?> >50</option>
        <option value="100" <?php echo $pagelimit==100? "selected":""; ?> >100</option>
        <option value="200" <?php echo $pagelimit==200? "selected":""; ?> >200</option>
        <option value="500" <?php echo $pagelimit==500? "selected":""; ?> >500</option>
        </select> Records Per Page
    </td>
  
<td width="60%">
<form class="navbar-form" role="search" method="post" style="  padding: 0 !important;">
       <div class="input-group add-on" style="float: right;">
       <input id='pagelimit' type="hidden" height="30px"  value="<?php echo $pagelimit; ?>">   
       <input class="form-control" size="30" onkeyup="SeachDataTable('planDetail_paging.php','<?php echo $pagelimit;?>','<?php //echo $page; ?>','flash')"  placeholder="Search Entries"  autocomplete="off" name='searchQuery' id='searchInput' class="searchInput" type="text" value="<?php echo $searchKeword; ?>">
       <div class="input-group-btn">
           <button class="enableOnInput btn btn-default" disabled='disabled' id='submitBtn' type="button" style="height: 26px;   padding: 4px 6px !important;" onclick="SearchDataTableValue('planDetail_paging.php','<?php echo $pagelimit;?>','<?php //echo $page; ?>','flash')"><i class="glyphicon glyphicon-search"></i></button>	
       <!--<button class="enableOnInput btn btn-default" disabled='disabled' id='clearcBtn' type="button" >
       <span class="glyphicon glyphicon-remove"></span>-->
       	
       </div>
       </div>
  </form>
</td>
</tr>
</table>
<div class="">
  <div class="pull-left" id="flash" style="text-align: center;"></div>
  <div class="pull-left" id="msg" style="text-align: center;"></div> 
</div>        
</div>
<?php 

$query_search='';
if($searchKeword!='')
{
    $query_search = " where  (planName LIKE '%". $searchKeword . "%' OR pdescription LIKE '%". $searchKeword . "%' )";
}    
//***** following code doing delete end ***/				
$adjacents = 3;
    $query = "SELECT COUNT(planID) as num FROM plandetail $query_search ";
    $totalpages =db_select($conn,$query);
    $total_pages = $totalpages[0]['num'];
    $limit = $pagelimit; 
    if($page) 
            $start = ($page - 1) * $limit; 			//first item to display on this page
    else
            $start = 0;

$sql="Select * from plandetail  $query_search order by  planID DESC LIMIT $start, $limit";
$que = db_select($conn,$sql);
$countRow=  db_totalRow($conn,$sql);
if($countRow==0)
{echo "<div align='center'><strong>No Record Found</strong> </div><br/>";}   
/* Setup page vars for display. */
?>
<form id="form" name="myform" style="border: 0px solid red;" method="post" action="priority.php">
  <table id="example1" class="table table-fixedheader table-bordered table-striped" style="width: 100%;">
<thead>
    <tr>
           <th>Plan-ID</th>
           <th>Plan-Name</th>
           <th>Plan Duration(days)</th>
           <th>Plan-Value</th>
           <th title="Plan description">Plan-Desc</th>
           <th>Create-Date</th>
           <th>Status</th>
           <th width="10%">Action</th>
    </tr> 
</thead>
<tbody>
<?php
$count=1;
foreach($que as $fetch)
{
    $planID=$fetch['planID'];  $planName=$fetch['planName']; $pValue=$fetch['pValue']; $pduration=$fetch['pduration']; 
    $plan_added_date=$fetch['plan_added_date']; $plan_update_date=$fetch['plan_update_date']; $pstatus=$fetch['pstatus']; 
    $plan_desc=$fetch['pdescription'];
 	
 ?> 
<tr id="pdel<?php echo $planID; ?>">
<td><?php echo $planID; ?></td>
<td><?php echo ucwords($planName); ?></td>
<td><?php echo $pduration ;?> days</td>
<td><?php echo $pValue;?> <i class="fa fa-rupee"></i></td>
<td><?php echo $plan_desc;?></td>
<td><?php echo $plan_added_date; ?></td>
<td id="getstatus<?php  echo $planID; ?>"><?php echo $pstatus==1?"active": "inactive"; ?></td>
<input type="hidden" size="1" id="ad_status<?php echo $planID;  ?>" value="<?php echo $pstatus;  ?>" >
<td>
<div class="dropdown">    
<?php  if(in_array(4, $UserRight)){ ?>     
<a href="javascript:void(0)" class="delete" title="Delete" onclick="pdelete('<?php echo $planID; ?>')"><span class="glyphicon glyphicon-trash"></span></a> &nbsp;&nbsp;&nbsp;
<?php }  if(in_array(2, $UserRight)){ ?>
<a href="javascript:void(0)" class="myBtnn" onclick="editPlan('<?php echo $planID; ?>','<?php echo $page; ?>','<?php echo $pagelimit; ?>')" id="<?php  echo $planID; ?>"   title="Edit Plan" class="result"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;&nbsp;
<a href="javascript:void(0)">
    <i id="icon_status<?php echo $planID; ?>"   class="status-icon fa <?php  echo ($pstatus == 1) ? 'fa-check-square-o':'fa-ban';?>" onclick=plan_act_deact('<?php echo $planID;  ?>')></i> </a>
<?php } ?>
</div>
</td> 
</tr>       
<?php $count++; } ?>         
</tbody>
</table>

<div class="page" style="border: 0px solid red; text-align: center; background-color:#fff !important; height:40px;">
<?php if($start==0) { 
       if($total_pages==0){  $startShow=0;  } else {  $startShow=1;}
       $lmt=$limit<$total_pages ? $limit :$total_pages;
       }
      else { $startShow=$start+1;  $lmt=$start+$countRow;  }
?>    
    <div class="pull-left" style="border: 0px solid red;">
      Showing <?php echo $startShow; ?>  to <?php echo $lmt; ?>   of <?php echo $total_pages; ?> entries   
      <span style="margin-left: 50px;" id="paging_loader"></span>
    </div> 
    <div class="pull-right" style="border: 0px solid red;">
    <?php
    if ($page == 0) $page = 1;
    $adjacent=1; $targetpage=''; $fromdate=''; $todate='';
    echo pagination($page,$limit,$total_pages,$adjacent,$targetpage,$searchKeword,$fromdate,$todate);
    ?>
    </div> 
</div>

</form> 
<script src="js/commonFunctionJS.js" type="text/javascript"></script>
<script type="text/javascript">
function createPlan()    
{
     $("#myModal_add_new_plan").modal();
     $("#flash").fadeIn(500).html('Loading <img src="img/image_process.gif" />');
      var info = 'action=add_new_plan'; 
        $.ajax({
	    type: "POST",
	    url: "planDetailModal.php",
	    data: info,
             success: function(result){
             $("#flash").hide();
             $('#view_modal_new_plan').html(result);
            return false;
        }
 
        });
     return false;    
}
function editPlan(planid,pageindex,limitval)    
{
     $("#LegalModal_modal_edit").modal();
     //$("#flash").fadeIn(500).html('Loading <img src="img/image_process.gif" />');
      var info = 'action=edit_plan&planid='+planid+'&pageindex='+pageindex+'&limitval='+limitval; 
        $.ajax({
	    type: "POST",
	    url: "planDetailModal.php",
	    data: info,
             success: function(result){
            // $("#flash").hide();
             $('#edit_modal_plan').html(result);
            return false;
        }
 
        });
     return false;  
}
function changePagination(pageid,limitval,searchtext,fromdate,todate){    
      $("#paging_loader").show();
      $("#paging_loader").fadeIn(800).html('Loading <img src="img/image_process.gif" />');
      var dataString ='pageNum='+pageid+'&limitval='+limitval+'&searchInputall='+searchtext+'&fromdate='+fromdate+'&todate='+todate;
      $.ajax({
           type: "POST",
           url: "planDetail_paging.php",
           data: dataString,
           cache: false,
           success: function(result){
           	 $("#results").html('');
                 $("#paging_loader").hide();
           	 $("#results").html(result);
           }
     }); 
}


</script>