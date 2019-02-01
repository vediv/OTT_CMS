<?php 
include_once 'corefunction.php';
include_once("config.php");
$Entryid=$_POST['Entryid']; $getpageindex=$_POST['pageindex'];
$ptag=$_POST['ptag']; $limitval=$_POST['limitval'];
$entryId = $Entryid;
$version = null;
$result = $client->baseEntry->get($entryId, $version);
$name=$result->name;
?>
<div class="modal-header">
    <button type="button" class="close" >&times;</button>
    <h4 class="modal-title">Edit Entry - <?php echo $name; ?></h4>
</div>
<div class="modal-body" style="border:0px solid ; min-height: 400px;"> 
   <div class="row">
   <div class="col-md-12">
    <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
    <?php  if(in_array(1, $otherPermission)){ ?><li class="active"><a href="#metadata" data-toggle="tab" onclick="showMetaData('<?php echo $Entryid; ?>','<?php echo $getpageindex; ?>');">Metadata</a></li><?php } ?>
    <?php  if(in_array(2, $otherPermission)){ ?><li><a href="#thumb" data-toggle="tab" onclick="showThumbnail('<?php echo $Entryid; ?>','<?php echo $getpageindex; ?>');">Thumbnail</a></li><?php } ?>
    <?php  if(in_array(3, $otherPermission)){?><li><a href="#access_control" data-toggle="tab" onclick="accessControl('<?php echo $Entryid; ?>','<?php echo $getpageindex; ?>');">Access Control</a></li><?php } ?>
    </ul>
    <div class="tab-content">
       <div class="tab-pane active"  id="metadata"><span id="flashMeta"></span></div>
        <div class="tab-pane" id="thumb"><span id="flashthumb"></span></div>
        <div class="tab-pane" id="access_control"><span id="flash_access_control"></span></div>
        <div class="tab-pane" id="currency"><span id="flash_currency"></span></div>
        <div class="tab-pane" id="content_partner"><span id="flash_content_partner"></span></div>
        <div class="tab-pane" id="plan"><span id="flashplan"></span></div>
        <div class="tab-pane" id="advertisement"><span id="flashadvg"></span></div>
    </div>
</div>
</div>
           
 </div> 
</div>
<script type="text/javascript">
/*function CloseVideo(){
document.getElementById("show_detail_live_modal_view").innerHTML="";
$('#myModalLiveEdit').modal('hide');
}*/
var entryid="<?php echo $entryId; ?>"; 
var pageindex="<?php echo $getpageindex; ?>";
var limitval="<?php echo $limitval ?>";
showMetaData(entryid,pageindex,limitval);
function showMetaData(eid,pindex,limitval)
{
    $("#flashMeta").show();
    $("#flashMeta").fadeIn(800).html('Loading <img src="img/image_process.gif" />');
    var dataString = 'Entryid='+eid+"&pageindex="+pindex+'&limitval='+ limitval; 
     $.ajax({
       type: "POST",
       url: "liveMetaDataShow.php",
       data: dataString,
       cache: false,
       success: function(r){
             $("#metadata").html(''); $("#thumb").html('');  $("#access_control").html('');$("#currency").html('');
             $("#plan").html(''); $("#currency").html('');$("#content_partner").html('');
             $("#flashMeta").hide();
             $("#metadata").html(r);
       }
     });
}
function showThumbnail(eid,pindex)
{
    
    $("#flashthumb").show();
    $("#flashthumb").fadeIn(800).html('Loading <img src="img/image_process.gif" />');
    var dataString = 'Entryid='+eid+"&pageindex="+pindex+'&limitval='+ limitval; 
     $.ajax({
       type: "POST",
       url: "liveThumnailShow.php",
       data: dataString,
       cache: false,
       success: function(r){
              $("#metadata").html('');$("#thumb").html('');  $("#access_control").html('');$("#currency").html('');
              $("#plan").html(''); $("#currency").html('');$("#content_partner").html('');
             $("#flashthumb").hide();
             $("#thumb").html(r);
       }
     });
}
function accessControl(eid,pindex)
{
    $("#flash_access_control").show();
    $("#flash_access_control").fadeIn(800).html('Loading <img src="img/image_process.gif" />');
    var dataString = 'Entryid='+eid+"&pageindex="+pindex+'&limitval='+ limitval; 
     $.ajax({
       type: "POST",
       url: "liveaccessControl.php",
       data: dataString,
       cache: false,
       success: function(r){
              $("#metadata").html('');$("#thumb").html('');  $("#access_control").html('');$("#currency").html('');
              $("#plan").html(''); $("#currency").html('');$("#content_partner").html('');
              $("#flash_access_control").hide();
              $("#access_control").html(r);
       }
     });
}
function currencyMataData(eid,pindex)
{
   
    $("#currency").show();
    $("#flash_currency").fadeIn(800).html('Loading <img src="img/image_process.gif" />');
    var dataString = 'Entryid='+eid+"&pageindex="+pindex+'&limitval='+ limitval; 
     $.ajax({
       type: "POST",
       url: "currencyMetaData.php",
       data: dataString,
       cache: false,
       success: function(r){
             $("#metadata").html('');$("#thumb").html('');  $("#access_control").html('');$("#currency").html('');
             $("#plan").html(''); $("#currency").html('');$("#content_partner").html('');
             $("#flash_currency").hide();
             $("#currency").html(r);
       }
     });
}
function showPlantab(eid,pindex,ptag)
{
   
   
    $("#flashplan").show();
    $("#flashplan").fadeIn(800).html('Loading <img src="img/image_process.gif" />');
    var dataString = 'Entryid='+eid+"&pageindex="+pindex+"&ptag="+ptag+'&limitval='+ limitval; 
     $.ajax({
       type: "POST",
       url: "planShow.php",
       data: dataString,
       cache: false,
       success: function(r){
             $("#metadata").html('');$("#thumb").html('');  $("#access_control").html('');$("#currency").html('');
             $("#plan").html(''); $("#currency").html('');$("#content_partner").html('');
             $("#flashplan").hide();
             $("#plan").html(r);
       }
     });
}
function showAdvg(eid,partner_uniqueid,duration,msDuration)
{
 
    $("#flashadvg").show();
    $("#flashadvg").fadeIn(800).html('Loading <img src="img/image_process.gif" />');
    var dataString = 'Entryid='+eid+"&partner_uniqueid="+partner_uniqueid+"&duration="+duration+"&msDuration="+msDuration; 
     $.ajax({
       type: "POST",
       url: "advertisementShow.php",
       data: dataString,
       cache: false,
       success: function(r){
            $("#metadata").html('');$("#thumb").html('');  $("#access_control").html('');$("#currency").html('');
             $("#plan").html(''); $("#currency").html('');$("#content_partner").html('');
             $("#flashadvg").hide();
             $("#advertisement").html(r);
       }
     }); 
}
function contentPartner(eid,pindex)
{
   
    $("#flash_content_partner").show();
    $("#flash_content_partner").fadeIn(800).html('Loading <img src="img/image_process.gif" />');
    var dataString = 'Entryid='+eid+"&pageindex="+pindex+'&limitval='+ limitval; 
     $.ajax({
       type: "POST",
       url: "contentPartnerMetaData.php",
       data: dataString,
       cache: false,
       success: function(r){
         $("#metadata").html('');$("#thumb").html('');  $("#access_control").html('');
         $("#currency").html('');$("#plan").html(''); $("#currency").html('');$("#content_partner").html('');
        $("#flash_content_partner").hide();
        $("#content_partner").html(r);
       }
     });
}
</script>