<?php
include_once 'corefunction.php';
include_once("function.php");
//$par_id=$_POST['contentid'];
$par_id=isset($_POST['contentid'])?$_POST['contentid']:'';
$entry_name=$_POST['contentName'];
$pager_pageIndex=$_POST['pageindex'];
$pagelimit=isset($_POST['limitval'])?$_POST['limitval']:5;$searchTextMatch='';$filtervalue='';
// $pagelimit=5;
$adjacents = 3;
    $query = "SELECT COUNT(entryid) as num FROM entry  where puser_id='$par_id' and status!='3'";
    $totalpages =db_select($conn,$query);
    $countRowTotal = db_totalRow($conn,$query);
    $total_pages = $totalpages[0]['num'];
    $limit = $pagelimit;
    if($pager_pageIndex)
            $start = ($pager_pageIndex - 1) * $limit; 			//first item to display on this page
    else
            $start = 0;

echo $kquery="SELECT * FROM entry where puser_id='$par_id' and status!='3'  LIMIT $start, $limit";
$countRow = db_totalRow($conn,$kquery);
$fetchKaltura =db_select($conn,$kquery);
if($countRow==0)
{echo "<div align='center'><strong>No Record Found</strong> </div><br/>";}
?>
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">&times;</button>
<h4 class="modal-title">Content Partner Entries of&nbsp; - &nbsp;<?php echo $entry_name;?></h4>
</div>
<div class="modal-body">
<div class="box">
<div class="box-body">

                  <div><b>Total Entries:</b>&nbsp;&nbsp;<?php echo $total_pages;?></div>
                  <div class="box-body" id="inner-content-div">
                  <table   class="table table-bordered table-striped" >
                  <thead>
                   <tr>
                    <th>Thumbnail</th><th>Name</th><th>Created-On</th><th>Duration</th><th>U-Status</th><th>V-Status</th>
                    </th>
                   </tr>
                  </thead>
                  <tbody>
                  <?php
                  $count=1;
                  foreach($fetchKaltura as $entry_media)
                  {
                    $id=$entry_media['entryid'];
                  $statusDB=$entry_media['status'];
                  // kaltura entry table
                  $kquery="select id,status,thumbnail,categories,length_in_msecs,subp_id from kaltura.entry where id='$id' and partner_id='$partnerID'";
                  $fetchKaltura =db_select($conn,$kquery);
                  $totalEntryKaltura = db_totalRow($conn,$kquery);
                    $name=$entry_media['name']; $categoryid=$entry_media['categoryid'];
                    $duration=$entry_media['duration']; $createdAt=$entry_media['created_at'];
                    $planid = $entry_media['planid']; $isPremium=$entry_media['ispremium'];
                    $isfeatured = $entry_media['isfeatured']=="1"? "#DAA520":"#C0C0C0";
                    $planname="NA"; $plan_title="Plan Not Added"; $ptag='';
                    if($isPremium!='')
                        {
                            $ptag=$isPremium=='1' ? "p": "f";
                            $planname= ucwords($ptag);
                            $plan_title=$ptag=='p'?"Premium":"Free";
                            if($ptag=='p'){$plan_title="Premium";}
                            if($ptag=='f'){$plan_title="Free";}
                        }
                    $sync=$entry_media['sync'];
                    $starColor=$isfeatured; $disableLink=''; $redyColor='label-success'; $vStatus='label-success'; $disableLink='';
                    $actColor=""; $disable="";
                    $in=1; $d1=0; $d2=1;   $bname1="A"; $bname2='D'; $class1="btn-success active"; $class2="btn-danger"; $disable1="disabled"; $disable2="";
                    $video_status=$entry_media['video_status'];
                    if($video_status=="inactive" || $video_status=="Inactive")
                    {
                        $in=0; $d1=1; $d2=0;   $bname1="A"; $bname2='D'; $class2="btn-success active"; $class1="btn-danger";
                        $actColor="#e8e8e8";   $disable1=""; $disable2="disabled"; $vStatus='label-default';
                    }
                    if($totalEntryKaltura==1)
                    {
                       $status=$fetchKaltura[0]['status'];
                       $thumbnailimg=$fetchKaltura[0]['thumbnail'];
                       $duration_k=$fetchKaltura[0]['length_in_msecs'];
                       $subp_id=$fetchKaltura[0]['subp_id'];
                       //str(serviceUrl) + '/p/' + str(partnerid) + '/sp/' + str(subpid) + '/thumbnail/entry_id/' + str(entryid) + '/version/' + str(thumbnail_id)
                       $thumbnailUrl=$serviceURL.'/p/'.$partnerID.'/sp/'.$subp_id.'/thumbnail/entry_id/'.$id.'/version/'.$thumbnailimg;
                       $tumnail_height_width="/width/90/height/60";
                       $categories=$fetchKaltura[0]['categories'];
                       if($statusDB==1 && $status==2)
                        {
                           $duration_in_second= ceil($duration_k/1000);
                           $up="update entry set status='2',duration='$duration_in_second' where status='1' and entryid='$id'"; db_query($conn,$up); }
                        }
                       else
                       {
                       $statusc='NA';  $categories=''; $disable1="disabled"; $disable2="disabled";
                       $disableLink='not-active-href';
                       $tumnail_height_width=""; $thumbnailUrl='';
                       }
                       $downloadURL=$entry_media['downloadURL'];
                       if($video_status=="inactive" && ($downloadURL=='' || $downloadURL===false))
                        {
                          $statusc='inProgress'; $redyColor='label-warning';
                          $disableLink='not-active-href'; $disable2="disabled"; $disable1="disabled";
                        }
                       else{
                       if($status=='-1') { $statusc="error_converting"; }
                       if($status=='-2') { $statusc="error_importing"; }
                       if($status==2) { $statusc="Ready"; }
                       if($status==0) { $statusc="import"; }
                       if($status==1) { $statusc="converting"; }
                       if($status==2) { $statusc="Ready"; }
                        }

                  ?>

                  <tr id="<?php echo $count."_r"; ?>" style="font-size: 12px; background:<?php echo $actColor; ?>">
                  <td><img class="img-responsive customer-img"  src="<?php echo $thumbnailUrl.$tumnail_height_width; ?>" alt="" /></td>

                  <td><?php echo $name;?></td>
                  <td><?php echo date("d/m/y H:i:s", strtotime($createdAt)); ?></td>
                  <td><?php echo gmdate("H:i:s", $duration);?></td>
                  <td >
                  <span class="label <?php echo $redyColor; ?> label-white middle"><?php echo  $statusc; ?></span>
                  </td>
                  <td>
                     <span  class="label <?php echo $vStatus; ?> label-white middle"><?php echo  $video_status; ?></span>
                  </td>
                  <?php   $count++; }   ?>
                  </tr>
                  </tbody>
                  </table>
                  </div>
</div>
<?php
/* paging code............*/

//$pager_pageIndex."---".$total_pages;
if($pager_pageIndex==0){ $pager_pageIndex=1; }
$prev = $pager_pageIndex - 1;	//previous page is page - 1
$next = $pager_pageIndex + 1;
$limit=$pagelimit;	//next page is page + 1
$lastpage = ceil($total_pages/$limit);
//$adjacents=$limit==10?1:3;
$adjacents = 3;
$lpm1 = $lastpage - 1;
$pagination = "";
if($lastpage > 1)
	{
	    $pagination .= "<div class=\"pagination\">";
		//previous button
		if($pager_pageIndex >1)
		 $pagination.= '<a href="javascript:void(0)"  onclick="changePaginationC(\''.$prev.'\',\''.$limit.'\',\''.$searchTextMatch.'\',\''.$filtervalue.'\',\''.$par_id.'\')"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>
Previous</a>';
		else
			$pagination.= "<span class=\"disabled \"> <i class='fa fa-long-arrow-left' aria-hidden='true'></i> Previous</span>";
		//pages
		if ($lastpage < 2 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{

			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
		          ?>
			<?php 	if ($counter == $pager_pageIndex)
					$pagination.= "<span class=\"current\">$counter</span>";
				else

				    $pagination.= '<a href="javascript:void(0)" onclick="changePaginationC(\''.$counter.'\',\''.$limit.'\',\''.$searchTextMatch.'\',\''.$filtervalue.'\',\''.$par_id.'\')">'.$counter.'</a>';

                                }
		}
		elseif($lastpage > 2 + ($adjacents * 2))	//enough pages to hide some
		{

			//close to beginning; only hide later pages
			if($pager_pageIndex < 1 + ($adjacents * 2))
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $pager_pageIndex)
						$pagination.= "<span class=\"current\">$counter</span>";
					else

					    $pagination.= '<a href="javascript:void(0)" onclick="changePaginationC(\''.$counter.'\',\''.$limit.'\',\''.$searchTextMatch.'\',\''.$filtervalue.'\',\''.$par_id.'\')">'.$counter.'</a>';
				}
				$pagination.= "...";
				$pagination.= '<a href="javascript:void(0)" onclick="changePaginationC(\''.$lpm1.'\',\''.$limit.'\',\''.$searchTextMatch.'\',\''.$filtervalue.'\',\''.$par_id.'\')">'.$lpm1.'</a>';
				$pagination.= '<a href="javascript:void(0)" onclick="changePaginationC(\''.$lastpage.'\',\''.$limit.'\',\''.$searchTextMatch.'\',\''.$filtervalue.'\',\''.$par_id.'\')">'.$lastpage.'</a>';
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $pager_pageIndex && $pager_pageIndex > ($adjacents * 2))
			{


				$pagination.= '<a href="javascript:void(0)" onclick="changePaginationC(\'1\',\''.$limit.'\',\''.$searchTextMatch.'\',\''.$filtervalue.'\',\''.$par_id.'\')">1</a>';
				$pagination.= '<a href="javascript:void(0)" onclick="changePaginationC(\'2\',\''.$limit.'\',\''.$searchTextMatch.'\',\''.$filtervalue.'\',\''.$par_id.'\')">2</a>';
				$pagination.= "...";
				for ($counter = $pager_pageIndex - $adjacents; $counter <= $pager_pageIndex + $adjacents; $counter++)
				{
					if ($counter == $pager_pageIndex)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
					$pagination.= '<a href="javascript:void(0)" onclick="changePaginationC(\''.$counter.'\',\''.$limit.'\',\''.$searchTextMatch.'\',\''.$filtervalue.'\',\''.$par_id.'\')">'.$counter.'</a>';
				}
				$pagination.= "...";
				$pagination.= '<a href="javascript:void(0)" onclick="changePaginationC(\''.$lpm1.'\',\''.$limit.'\',\''.$searchTextMatch.'\',\''.$filtervalue.'\',\''.$par_id.'\')">'.$lpm1.'</a>';
				$pagination.= '<a href="javascript:void(0)" onclick="changePaginationC(\''.$lastpage.'\',\''.$limit.'\',\''.$searchTextMatch.'\',\''.$filtervalue.'\',\''.$par_id.'\')">'.$lastpage.'</a>';
			}
			//close to end; only hide early pages
			else
			{

				$pagination.= '<a href="javascript:void(0)" onclick="changePaginationC(\'1\',\''.$limit.'\',\''.$searchTextMatch.'\',\''.$filtervalue.'\',\''.$par_id.'\')">1</a>';
				$pagination.= '<a href="javascript:void(0)" onclick="changePaginationC(\'2\',\''.$limit.'\',\''.$searchTextMatch.'\',\''.$filtervalue.'\',\''.$par_id.'\')">2</a>';
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $pager_pageIndex)
						$pagination.= "<span class=\"current\">$counter</span>";
					else

						$pagination.= '<a href="javascript:void(0)" onclick="changePaginationC(\''.$counter.'\',\''.$limit.'\',\''.$searchTextMatch.'\',\''.$par_id.'\')">'.$counter.'</a>';
				}
			}
		}

		//next button
		if ($pager_pageIndex < $counter - 1)
		    $pagination.= '<a href="javascript:void(0)" onclick="changePaginationC(\''.$next.'\',\''.$limit.'\',\''.$searchTextMatch.'\',\''.$filtervalue.'\',\''.$par_id.'\')">Next  <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>';
		else
			$pagination.= "<span class=\"disabled\"> Next <i class='fa fa-long-arrow-right' aria-hidden='true'></i></span>";
		$pagination.= "</div>\n";
	}
?>
<div class="page" style="border: 0px solid red; text-align: center; background-color:#fff !important; height:40px;">
<?php if($start==0 || $start==1) {
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
    echo $pagination;
    ?>
    </div>
</div>
</div>
<script type="text/javascript">
function changePaginationC(pageid,limitval,searchtext,filterview,parid){
  alert(parid);
     //$("#flash").fadeIn(100).html('Wait <img src="img/image_process.gif" />');
     var dataString ='pageindex='+pageid+'&limitval='+limitval+'&searchInputall='+searchtext+"&filtervalue="+filterview+"&contentid="+parid;
     // //$("#result").html();
     // $('#load_in_modal').show();
     // $('#results').css("opacity",0.1);
     $.ajax({
           type: "POST",
           url: "contentPartnerModal_new.php",
           data: dataString,
           success: function(result){
                //$("#results").html('');
                // $("#flash").hide();
                $("#viewContentDetail_modal").html(result);
           }
      });
}
</script>
<?php break;
?>
<script src="js/jquery.slimscroll.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {

$(function(){
    $('#inner-content-div').slimScroll({
    	 height: '400px',
    	// width:  '352px',
    	  size: '8px',
    	 //color: '#f5f5f5'
    });
});

});

</script>
<link rel="stylesheet" href="bootstrap/css/jquery-ui.css">
