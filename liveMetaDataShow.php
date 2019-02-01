<?php
include_once 'corefunction.php';
include_once("config.php");
$Entryid=$_POST['Entryid']; $getpageindex=$_POST['pageindex']; $limitval=$_POST['limitval'];
$entryId = $Entryid;
$version = null;
$result = $client->baseEntry->get($entryId, $version);
//print '<pre>'.print_r($result, true).'</pre>';
$name=$result->name; 
$description=$result->description;
$tags=$result->tags;
$categoriesIds=$result->categoriesIds;
$Creator=$result->creatorId;  
$createdAt=$result->createdAt;
$plays=$result->plays; 
$duration=$result->duration; 
$msDuration=$result->msDuration ;
$mediaType=$result->mediaType;   
$mdeitype= $mediaType==7 ? "live" : "";
$moderationStatus=$result->moderationStatus;
$moderationStatus_main= $moderationStatus==6 ? "Auto approved" : "";
$thumbnailUrl=$result->thumbnailUrl;
$dataUrl=$result->dataUrl;
$primaryBroadcastingUrl=$result->primaryBroadcastingUrl;
//$dataUrl=$result->dataUrl."/a.m3u8";
//$resDataUrl=str_replace("url","applehttp",$dataUrl);
$selEntry="select shortdescription,sub_genre,pgrating,language,
producer,director,cast,crew,startdate,enddate,contentpartnerid,countrycode,video_status,custom_data
from entry where entryid='$Entryid'";
$f= db_select($conn,$selEntry);
$shortdesc=$f[0]['shortdescription']; $sub_genre=$f[0]['sub_genre']; $pgrating=$f[0]['pgrating'];
$language=$f[0]['language']; $producer=$f[0]['producer']; $director=$f[0]['director'];
$cast=$f[0]['cast']; $crew=$f[0]['crew']; $startdate=$f[0]['startdate']; $enddate=$f[0]['enddate'];
$contentpartnerid=$f[0]['contentpartnerid']; $countrycode=$f[0]['countrycode'];
$video_status=$f[0]['video_status']; $custom_data_json=$f[0]['custom_data'];
?> 
<div class="row" style="border: 0px solid red; margin-top: 5px;">
 <div class="pull-left" style=" border:0px solid #c7d1dd ; border-top:0px ; width: 55%; margin-left: 10px;">
     <form class="form-horizontal" method="post"  action="javascript:" id="LivemetaDataForm" name="LivemetaDataForm" >
    <div style="height:430px;overflow-y: scroll; overflow-x: hidden; display: block;">
    <div class="form-group">
            <label for="inputEmail" class="control-label col-xs-3">Publishing points:</label>
            <div class="col-xs-8 padding-right">
                <p style="border: 0px solid green; margin-top: 5px;"><?php echo $primaryBroadcastingUrl; ?></p>  
            </div>
          </div>     
    <div class="form-group">
        <label for="entryname" class="control-label col-xs-3">Title *:</label>
        <div class="col-xs-8">
            <input type="text" class="form-control" required name="entryname" id="entryname" placeholder="Entry Name" maxlength="35" value="<?php echo htmlentities($name); ?>">
            <input type="hidden" class="form-control" name="duration" id="duration" value="<?php echo $duration; ?>">
             <span class="help-block has-error" id="entryname-error" style="color:red;"></span>
        </div>
    </div>
    <div class="form-group">
       <label for="entrydescription" class="control-label col-xs-3">Description:</label>
       <div class="col-xs-8">
           <textarea class="form-control" rows="3" id="entrydescription" maxlength="300" name="entrydescription" placeholder="Description" ><?php echo $description; ?></textarea>
       </div>
    </div>
    <div class="form-group">
        <label for="tags_1" class="control-label col-xs-3">Tags:</label>
        <div class="col-xs-8" id="drawTags">
            <span id="wait_loader_tags"></span>
        </div>
    </div>    
    <div class="form-group">
        <label for="category_value" class="control-label col-xs-3">Categories:</label>
        <div class="col-xs-8">
        <input type="text" size="10" name="category_metadata" value="" placeholder="Category" id="input-category" class="form-control" />
         <div id="metadata-category" class="well well-sm" style="height:70px; width: 100%; overflow: auto;">
            <?php
              if($categoriesIds=='')
               {
                  $qcategory="SELECT category_id,fullname FROM categories  WHERE category_id IN ('$categoriesIds')";
               }
              if($categoriesIds!='')
              {
                  $qcategory="SELECT category_id,fullname FROM categories  WHERE category_id IN ($categoriesIds)";
              }    
              $fetchCategory= db_select($conn,$qcategory);
              $totalRow= db_totalRow($conn,$qcategory);
              if($totalRow>0){
              foreach($fetchCategory as $fcategory) 
                 {   
              ?>
              <div id="metadata-category<?php echo $fcategory['category_id'];  ?>"><i class="fa fa-minus-circle"></i> <?php echo $fcategory['fullname'];  ?> 
              <input type="hidden" name="metadata_category[]" value="<?php echo $fcategory['category_id'];  ?>" />
              </div>
           <?php } 
              }  ?>
        </div>
        </div>  
    </div>

  
    
</div>
<br/>
<div class="form-group">
<div class="col-xs-offset-2 col-xs-10">
<?php  if(in_array(1, $UserRight)){ ?>    
<button type="button" class="btn btn-primary btn1" data-dismiss="modal1" disabled  id="save_button" name="submit" onclick=" save_live_metedata_in_server('saveandclose_live_metadata','<?php echo $Entryid; ?>','<?php echo $getpageindex;  ?>','<?php echo $limitval;  ?>');"   >Save & Close</button>
<span id="saving_matadata"> </span>
<?php } else{ ?>
  <!--<button type="button" class="btn btn-primary" data-dismiss="modal"  name="submit" disabled>Save & Close</button>-->
  <button type="submit" class="btn btn-primary"  data-dismiss="modal1"  name="submit" disabled>Save MetaData</button>
<?php } ?> 
</div>
</div>
</form>

</div> 
<div class="pull-right" id="drawVideoPlayer1"  style="border:1px solid #c7d1dd ; width: 40%; margin-right: 5px; padding:5px; height: 300px;">
    <!--<span id="wait_loader_videoPlay" style="margin: 0 auto;" ></span>  
    <div id="watch-video"></div>-->
    <?php include_once 'livePlayer.php'; ?>
    
</div>	 
</div>
<?php include_once 'ksession_close.php';  ?>
<script src = "js/autocomplete.js"></script>
<script type="text/javascript">
drawTags('<?php echo $entryId; ?>');  
function drawTags(entryid)
{
    $("#wait_loader_tags").fadeIn(400).html('Loading... <img src="img/image_process.gif" />');
    var dataString ='action=tags_in_live_metaData&entryid='+entryid;
    $.ajax({                                                                                                                                        
    type: "POST",
    url: "ajax_common.php",
    data: dataString,
    cache: false,
    success: function(result){
        $("#wait_loader_tags").hide();
        $("#drawTags").html(result);
        drawCateory(entryid);
       
     }
    });  
}
function drawCateory(entryid)
{
    $("#wait_loader_category").fadeIn(400).html('Loading... <img src="img/image_process.gif" />');
    var dataString ='action=category_in_metaData&entryid='+entryid;
    $.ajax({                                                                                                                                        
    type: "POST",
    url: "ajax_common.php",
    data: dataString,
    cache: false,
    success: function(r){
      $("#wait_loader_category").hide(); 
      $("#drawCategory").html(r);
      $('#save_button').attr('disabled',false);
      //videoplay(entryid);
      
      }
      
    });  
}

function videoplay(entryid)
{
    $("#wait_loader_videoPlay").fadeIn(400).html('Loading Media... <img src="img/image_process.gif" />');
    var dataString ='action=player_in_live_metaData&entryid='+entryid;
    $.ajax({                                                                                                                                        
    type: "POST",
    url: "ajax_common.php",
    data: dataString,
    cache: false,
    success: function(r){
      $("#wait_loader_videoPlay").hide(); 
      $("#watch-video").html(r); 
     }
   });  
}


function save_live_metedata_in_server(smatadata,entryid,pageindex,limitval){
    var entryname = $('#entryname').val();
    if(entryname=='')
       {
           $("#entryname-error").html("Chanel Name required");
            return false;
       } 
  var category='';     
  var key_val = document.getElementsByName('metadata_category[]');
   for (var x = 0; x < key_val.length; x++) { 
           category += key_val[x].value+',';
        }
    var categoryids=category.slice(0, -1);
    var publisher_unique_id="<?php  echo $publisher_unique_id ?>";
    var apiURl="<?php  echo $apiURL."/livefeed" ?>";
    var entrydesc = $('#entrydescription').val();	
    var entrytags = $('#tags_1').val(); 	
    $("#saving_matadata").fadeIn(400).html('Saving... <img src="img/image_process.gif" />');
    $('#save_button').attr('disabled',true);
    var apiBody = new FormData();
    apiBody.append("partnerid",publisher_unique_id); 
    apiBody.append("entryid",entryid);
    apiBody.append("name",entryname);
    apiBody.append("description",entrydesc);
    apiBody.append("kal_tag",entrytags);
    apiBody.append("tag","update");
    if(categoryids!='')
    {
        apiBody.append("cat_id",categoryids);
    }
    apiBody.append("countrycode",'NULL');
    $.ajax({
                url:apiURl,
                method: 'POST',
                dataType: 'json', 
                data:apiBody,
                processData: false,
                contentType: false,
                    success: function(jsonResult){ 
                      console.log(jsonResult);
                      var status=jsonResult.status;
                      $('#results').load("live_channel_paging.php",{'first_load':pageindex,'limitval':limitval,'maction':smatadata},
                      function() {
	  	      $("#msg").html("Update successfully");
                      $("#saving_matadata").hide();
                      $('#myModalLiveEdit').modal('hide');
                      });
                      
                     /* if(status=='updated Successfully')
                      {
                          $("#livemsg").html("Update successfully");
                          return true;
                      }*/
                    }
     }); 
  
}

$('input[name=\'category_metadata\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'ajax_common.php?action=category_in_metaData&filter_name='+request,
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['fullname'],
						value: item['category_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'category_metadata\']').val('');
		
		$('#metadata-category' + item['value']).remove();
		
		$('#metadata-category').append('<div id="metadata-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="metadata_category[]" value="' + item['value'] + '" /></div>');
	}	
});
        $('#metadata-category').delegate('.fa-minus-circle', 'click', function() {
                $(this).parent().remove();
        });
</script>
<script src="js/add_custom_row.js" type="text/javascript"></script>