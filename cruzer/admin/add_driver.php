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
            <h3>Add New Driver / Vehicle </h3>
          </div>
          <div class="content">
          <div class="form-group col-md-12 ">
            <?php if (isset($_POST['email'])){
			$db->addDriver();  
			}
			?>
			
        <form action="" id="myform" method="post" enctype="multipart/form-data"> 
          
          
          
            <div class="form-group col-md-3 setmyheight">
              <label>Driver Name</label>
			  <input type="text" name="name" parsley-trigger="change"   placeholder="" class="form-control">
            </div>
			<div class="form-group col-md-3 setmyheight">
              <label>Contact Number</label>
			  <input type="number" name="no" parsley-trigger="change"   placeholder="" class="form-control">
            </div>
			<div class="form-group col-md-3 setmyheight">
              <label>Email</label>
			  <input type="email" name="email" parsley-trigger="change"   placeholder="" class="form-control">
            </div>
			<div class="form-group col-md-3 setmyheight">
              <label>Vehicle Brand</label>
			  <input type="text" name="brand" parsley-trigger="change"   placeholder="" class="form-control">
            </div>
			<div class="form-group col-md-3 setmyheight">
              <label>Vehicle Model</label>
			  <input type="text" name="model" parsley-trigger="change"   placeholder="" class="form-control">
            </div>
			<div class="form-group col-md-3 setmyheight">
              <label>Vehicle Color</label>
			  <input type="text" name="color" parsley-trigger="change"   placeholder="" class="form-control">
            </div>
			<div class="form-group col-md-3 setmyheight">
              <label>Vehicle Registration No</label>
			  <input type="text" name="reg_no" parsley-trigger="change"   placeholder="" class="form-control">
            </div>
			<div class="form-group col-md-3 setmyheight">
              <label>Licence No</label>
			  <input type="text" name="lic_no" parsley-trigger="change"   placeholder="" class="form-control">
            </div>
			
			<div class="form-group col-md-3 setmyheight">
              <label>Allowed Passengers</label>
			  <input type="text" name="pass" parsley-trigger="change"   placeholder="" class="form-control">
            </div>
			<div class="form-group col-md-3 setmyheight">
              <label>Rate per km</label>
			  <input type="text" name="rate" parsley-trigger="change"   placeholder="" class="form-control">
            </div>
			<div class="form-group col-md-3 setmyheight">
              <label>Login Password</label>
			  <input type="password" name="password" parsley-trigger="change"   placeholder="" class="form-control">
            </div>
			
			
			
			
			
            
            <div class="clear"></div>
            
         
            <div class="form-group col-md-12 ">
            
            
                
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
