<?php
require_once('../includes/config.php');
require_once('../includes/database.class.php');

$db= new database($pdo);


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" href="images/icon.png">

	<title>AFRIAIRLINES - ADMIN</title>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,400italic,700,800' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway:300,200,100' rel='stylesheet' type='text/css'>

	<!-- Bootstrap core CSS -->
	<link href="js/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="js/jquery.gritter/css/jquery.gritter.css" />
	<link rel="stylesheet" href="fonts/font-awesome-4/css/font-awesome.min.css">

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="../../assets/js/html5shiv.js"></script>
	  <script src="../../assets/js/respond.min.js"></script>
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="js/jquery.nanoscroller/nanoscroller.css" />

    
	<link href="css/style.css" rel="stylesheet" />	
    <script type="text/jscript">
	function check() {
    if(document.getElementById('full_pay').value == "No") {
        document.getElementById('min_pay').style.display = 'none';
		document.getElementById('min_pay').value="";
    } else {
        document.getElementById('min_pay').style.display = 'block';
    }
	}
	function check_cancel() {
	if(document.getElementById('cancel_book').value == "No") {
        document.getElementById('cancel_fee').style.display = 'none';
    } else {
        document.getElementById('cancel_fee').style.display = 'block';
		document.getElementById('cancel_fee').value="";
    }
	}
	function check_adv_prch() {
	if(document.getElementById('adv_prch').value == "No") {
        document.getElementById('adv_pay_day').style.display = 'none';
    } else {
        document.getElementById('adv_pay_day').style.display = 'block';
		document.getElementById('adv_pay_day').value="";
    }
	}
	function check_book() {
	if(document.getElementById('book_by').value == "0") {
        document.getElementById('book_bfr').style.display = 'none';
    } else {
        document.getElementById('book_bfr').style.display = 'block';
		document.getElementById('book_bfr').value="";
    }
	}
	function slider(chk) {
      document.getElementById("div1").style.display = chk.checked ? "block" : "none";
 	}
</script>
<style type="text/css">
.setmyheight
  {
	  height:70px;

  }
</style>    
</head>
<body>

<div id="cl-wrapper">
  <div class="container-fluid" id="pcont">
    <!-- TOP NAVBAR -->

    <div class="cl-mcont">
	  <div class="row">
	    <div class="col-sm-12 col-md-12">
        <div class="block-flat">
          <div class="header">							
            <h3>Add New Offers <?php $db->getMemID(); ?></h3>
          </div>
          <div class="content">
          <div class="form-group col-md-12 ">
            <?php if (isset($_POST['destination'])){
			$db->insertOffersNew();  
			}
			?>
			
        <form action="" id="myform" method="post" enctype="multipart/form-data"> 
          <div class="form-group col-md-3 setmyheight">
              <label class="control-label">Select Destination</label>
               
                  <select name="destination" class="form-control">
                  <option value=""></option>
                  <?php
				  $db->getDesCountry();
				  ?>
     			
                   
                  </select>			
            </div>
          <div class="form-group col-md-3 setmyheight">
                <label class="control-label">From City</label>
               
                  <select name="city" class="form-control">
                    <option value=""></option>
                    <?php
				  $db->getDepCountry();
				  ?>
                  </select>									
               
          </div>
          
            <div class="form-group col-md-3 setmyheight">
              <label>Fare Offer</label> <input type="text" name="offer" parsley-trigger="change"   placeholder="" class="form-control">
            </div>
            <div class="form-group col-md-3 setmyheight">
              <label class="control-label">Tax Included</label>
               
                  <select name="tax" class="form-control">
                  <option value=""></option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                  </select>			
            </div>
            <div class="form-group col-md-3 setmyheight">
              <label class="control-label">Return Tax Included</label>
               
                  <select name="r_tax" class="form-control">
                  	<option value=""></option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                  </select>	
            </div>
            <div class="form-group col-md-3 setmyheight">
              <label>Airline</label> 
              <select name="airline" class="form-control">
                  <option value=""></option>
                  <?php
				  $db->getAirline();
				  ?>
     			
                   
                  </select>
              
            </div>
            <div class="form-group col-md-3 setmyheight">
              <label class="control-label">Travel Class</label>
               
                  <select  name="class" class="form-control">
                    <option value="Economy">Economy</option>
                    <option value="business">business</option>
                  </select>
            </div>
            <div class="form-group col-md-3 setmyheight">
              <label>Select Travel Date From</label> <input type="date" name="date_from" parsley-trigger="change"   placeholder="Select Travel Date" class="form-control">
            </div>
            <div class="form-group col-md-3 setmyheight">
              <label>Select Travel Date To</label> <input type="date" name="date_to" parsley-trigger="change"   placeholder="" class="form-control">
            </div>
            
            <div class="form-group col-md-3 setmyheight">
              <label>Minimum Stay</label> <input type="text" name="min_stay" parsley-trigger="change"   placeholder="" class="form-control">
            </div>
            <div class="form-group col-md-3 setmyheight">
              <label>Maximum Stay</label> <input type="text" name="max_stay" parsley-trigger="change"   placeholder="" class="form-control">
            </div>
            <div class="form-group col-md-3 setmyheight">
              <label>Book By</label> 
              <select id="book_by"  onchange="check_book();" name="book_by" class="form-control">
                    <option value="0">Now Onwards</option>
                    <option value="1">Till</option>
                    
                  </select>
            </div>
            <div id="book_bfr" class="form-group col-md-3 setmyheight" style="display:none">
              <label>Book Before</label> <input type="date" name="book_bfre" parsley-trigger="change"   placeholder="" class="form-control">
            </div>
            <div class="form-group col-md-3 setmyheight">
              <label class="control-label">Full Payment on Booking</label>
               
                  <select id="full_pay"  onchange="check();" name="full_pay" class="form-control">
                    <option value="No">No</option>
                    <option value="Yes">Yes</option>
                    
                  </select>
            </div>
            <div id="min_pay" class="form-group col-md-3 setmyheight" style="display:none">
              <label>Minimum Payment</label> <input type="text" value="0" name="min_pay" parsley-trigger="change"   placeholder="" class="form-control">
            </div>
            <div class="form-group col-md-3 setmyheight">
              <label class="control-label">Booking Changes Allowed</label>
               
                  <select name="booking_change" class="form-control">
                  <option value="No">No</option>
                    <option value="Yes">Yes</option>
                    
                  </select>
            </div>
            <div class="form-group col-md-3 setmyheight">
              <label class="control-label">Allow Booking Cancellation</label>
               
                  <select onChange="check_cancel();" id="cancel_book" name="cancel_book" class="form-control">
                    <option value="No">No</option>
                    <option value="Yes">Yes</option>
                   
                  </select>
            </div>
            <div id="cancel_fee" class="form-group col-md-3 setmyheight" style="display:none">
              <label>Booking Cancellation Fee</label> <input value="0" type="text" name="cancel_fee" parsley-trigger="change"   placeholder="" class="form-control">
            </div>
            <div class="form-group col-md-3 setmyheight">
              <label class="control-label">Advance Purchase Allowed</label>
               
                  <select onChange="check_adv_prch();" id="adv_prch" name="adv_prch" class="form-control">
                    <option value="No">No</option>
                    <option value="Yes">Yes</option>
                    
                  </select>
            </div>
            <div id="adv_pay_day" class="form-group col-md-3 setmyheight" style="display:none">
              <label>Advance Purchase Withing (Days)</label> <input value="0" type="text" name="adv_pay_day" parsley-trigger="change"   placeholder="" class="form-control">
            </div>
            <div id="div1" class="form-group col-md-3 setmyheight"  style="display:none">
              <label class="control-label">Upload Slider Image</label>

               <input type="file" name="slider_img"  class="form-control">
            </div>
            <div class="clear"></div>
            
         
            <div class="form-group col-md-12 ">
            
            
                 <div class="checkbox"><label> <input name="to_map" value="1" type="checkbox"> Add this offer to Map </label> </div>
                 <div class="checkbox"><label> <input name="to_slide" id="chk1"  onClick="slider(this);" value="1" type="checkbox"> Add this offer to Slider </label> </div>              
               
              <button class="btn btn-primary" type="submit">Submit</button>
              <button class="btn btn-default">Cancel</button>
              </div>
            </form>

          </div>
        </div>				
      </div>
      
      
  </div>
    
    	</div>
	
	</div> 
	
</div>
<!-- Right Chat--><script src="js/jquery.js"></script>
	<script src="js/jquery.cookie/jquery.cookie.js"></script>
  <script src="js/jquery.pushmenu/js/jPushMenu.js"></script>
	<script type="text/javascript" src="js/jquery.nanoscroller/jquery.nanoscroller.js"></script>
	<script type="text/javascript" src="js/jquery.sparkline/jquery.sparkline.min.js"></script>
  <script type="text/javascript" src="js/jquery.ui/jquery-ui.js" ></script>
	<script type="text/javascript" src="js/jquery.gritter/js/jquery.gritter.js"></script>
	<script type="text/javascript" src="js/behaviour/core.js"></script>
    
    <style type="text/css">
    #color-switcher{
      position:fixed;
      background:#272930;
      padding:10px;
      top:50%;
      right:0;
      margin-right:-109px;
    }
    
    #color-switcher .toggle{
      cursor:pointer;
      font-size:15px;
      color: #FFF;
      display:block;
      position:absolute;
      padding:4px 10px;
      background:#272930;
      width:25px;
      height:30px;
      left:-24px;
      top:22px;
    }
    
    #color-switcher p{color: rgba(255, 255, 255, 0.6);font-size:12px;margin-bottom:3px;}
    #color-switcher .palette{padding:1px;}
    #color-switcher .color{width:15px;height:15px;display:inline-block;cursor:pointer;}
    #color-switcher .color.purple{background:#7761A7;}
    #color-switcher .color.green{background:#19B698;}
    #color-switcher .color.red{background:#EA6153;}
    #color-switcher .color.blue{background:#54ADE9;}
    #color-switcher .color.orange{background:#FB7849;}
    #color-switcher .color.prusia{background:#476077;}
    #color-switcher .color.yellow{background:#fec35d;}
    #color-switcher .color.pink{background:#ea6c9c;}
    #color-switcher .color.brown{background:#9d6835;}
    #color-switcher .color.gray{background:#afb5b8;}
 </style>
  

  <script type="text/javascript">
    var link = $('link[href="css/style.css"]');
    
    if($.cookie("css")) {
      link.attr("href",'css/skin-' + $.cookie("css") + '.css');
    }
    
    $(function(){
      $("#color-switcher .toggle").click(function(){
        var s = $(this).parent();
        if(s.hasClass("open")){
          s.animate({'margin-right':'-109px'},400).toggleClass("open");
        }else{
          s.animate({'margin-right':'0'},400).toggleClass("open");
        }
      });
      
      $("#color-switcher .color").click(function(){
        var color = $(this).data("color");
        $("body").fadeOut(function(){
          //link.attr('href','css/skin-' + color + '.css');
          $.cookie("css", color, {expires: 365, path: '/'});
          window.location.href = "";
          $(this).fadeIn("slow");
        });
      });
    });
  </script> <script src="js/jquery.parsley/parsley.js" type="text/javascript"></script>
  
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
  <script src="js/behaviour/voice-commands.js"></script>
  <script src="js/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.flot/jquery.flot.js"></script>
<script type="text/javascript" src="js/jquery.flot/jquery.flot.pie.js"></script>
<script type="text/javascript" src="js/jquery.flot/jquery.flot.resize.js"></script>
<script type="text/javascript" src="js/jquery.flot/jquery.flot.labels.js"></script>
</body>
</html>
