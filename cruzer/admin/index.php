<?php
ob_start();
require_once ('../includes/config.php');
require_once ('../includes/database.class.php');
$db = new database($pdo);
//session_start();

if (isset($_GET['log']))
{ 
$db->adminLogout();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" href="images/icon.png">

	<title>Cruzer</title>
    <link href='http://fonts.googleapis.com/css?family=Dosis:400,700' rel='stylesheet' type='text/css'>
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
	
	<link href="css/style.css" rel="stylesheet" />	
    
</head>
<body>

<div id="cl-wrapper">

  <div class="cl-sidebar">
   
    <div class="cl-navblock">
      <div class="menu-space">
        <div class="content">
          <div class="sidebar-logo">
            <div class="logo">
                <a href="index2.html"></a>
            </div>
          </div>
          <div class="side-user">
            <div class="avatar"><span style="font-family: 'Dosis', sans-serif; font-size:30px">Taxi Admin
            <?php $db->getMemID();	
					?>
            </span></div>
            
          </div>
          <ul class="cl-vnavigation">
           
            <li><a href="add_driver.php" target="loader"><i class="fa fa-list-alt"></i><span>Add New Driver / Vehicle</span></a></li>
            <li><a href="all_customer.php" target="loader"><i class="fa fa-list-alt"></i><span>View Registered Customers</span></a></li>
			<li><a href="all_drivers.php" target="loader"><i class="fa fa-list-alt"></i><span>View Registered Drivers</span></a></li>
			<li><a href="tracker.php" target="loader"><i class="fa fa-list-alt"></i><span>Track Drivers</span></a></li>
           
            <li><a href="all_offer.php" target="loader"><i class="fa fa-list-alt"></i><span>Display All Rides</span></a></li>
           
            
              
          </ul>
        </div>
      </div>
      <div class="text-right collapse-button" style="padding:7px 9px;">
        <input type="text" class="form-control search" placeholder="Search..." />
        <button id="sidebar-collapse" class="btn btn-default" style=""></button>
      </div>
    </div>
  </div>
  
	<div class="container-fluid" id="pcont">
   <!-- TOP NAVBAR -->
  <div id="head-nav" class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-collapse">
        <ul class="nav navbar-nav navbar-right user-nav">
          <li class="dropdown profile_menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img alt="Avatar" src="images/avatar6.jpg" /><span>
            <?php
			if(isset($_SESSION["username"]))
					{
					print $_SESSION["username"] ;
            ?><b class="caret"></b></a>
            <ul class="dropdown-menu">
            
              
              <li class="divider"></li>
              <li><?php
								print "<a href='index.php?log=out'> Logout</a>";
					}
					else
					header('Location:login.php');

			?></li>
            </ul>
          </li>
        </ul>			
        

      </div><!--/.nav-collapse animate-collapse -->
    </div>
  </div>
  
    <iframe width="100%" frameborder="0" src="add_driver.php" height="100%" name="loader">
	<div class="cl-mcont">		<div class="row">
      
      <div class="col-md-3 col-sm-6">
        <div class="fd-tile detail tile-green">
          <div class="content"><h1 class="text-left">170</h1><p>New Visitors</p></div>
          <div class="icon"><i class="fa fa-flag"></i></div>
          <a class="details" href="#">Details <span><i class="fa fa-arrow-circle-right pull-right"></i></span></a>
        </div>
      </div>
    
      
      <div class="col-md-3 col-sm-6">
        <div class="fd-tile detail tile-red">
          <div class="content"><h1 class="text-left">24</h1><p>New Comments</p></div>
          <div class="icon"><i class="fa fa-comments"></i></div>
          <a class="details" href="#">Details <span><i class="fa fa-arrow-circle-right pull-right"></i></span></a>
        </div>
      </div>
    
      
      
      <div class="col-md-3 col-sm-6">
        <div class="fd-tile detail tile-prusia">
          <div class="content"><h1 class="text-left">25</h1><p>New Likes</p></div>
          <div class="icon"><i class="fa fa-heart"></i></div>
          <a class="details" href="#">Details <span><i class="fa fa-arrow-circle-right pull-right"></i></span></a>
        </div>
      </div>
      
      <div class="col-md-3 col-sm-6">
        <div class="fd-tile detail tile-purple">
          <div class="content"><h1 class="text-left">72</h1><p>New Views</p></div>
          <div class="icon"><i class="fa fa-eye"></i></div>
          <a class="details" href="#">Details <span><i class="fa fa-arrow-circle-right pull-right"></i></span></a>
        </div>
      </div>
    
    </div>
    <div class="row">
      <div class="col-md-7">
        <div class="block-flat">
          <div class="header">							
            <h3>Visitors</h3>
          </div>
          <div class="content full-width">
            <div id="chart3-legend" class="legend-container"></div>
            <div id="chart3" style="height: 180px;"></div>
            <div class="counter-detail">
              <div class="counter"><div class="spk1"></div><p>New Visitors</p><h5>146</h5></div>
              <div class="counter"><div class="spk2"></div><p>Old Visitors</p><h5>109</h5></div>
            </div>
          </div>
        </div>
        <div class="block-flat">
          <div class="header">							
            <h3>Tasks</h3>
          </div>
          <div class="content">
            <div class="table-responsive">
            <table class="no-border hover list">
              <tbody class="no-border-y">
                <tr class="items">
                  <td style="width: 10%;"><span class="label label-warning">Task</span></td>
                  <td><p><strong>Graphics</strong><span>description</span></p></td>
                  <td class="color-success"><div class="progress"><div class="progress-bar progress-bar-warning" style="width: 80%">80%</div></div></td>
                  <td class="text-right" style="width: 15%;"><a class="label label-default" href="#"><i class="fa fa-pencil"></i></a> <a class="label label-danger" href="#"><i class="fa fa-times"></i></a></td>
                </tr>
                <tr class="items">
                  <td><span class="label label-danger">File</span></td>
                  <td><p><strong>Contact_form.psd</strong><span>description</span></p></td>
                  <td class="color-success"><div class="progress"><div class="progress-bar progress-bar-danger" style="width: 60%">60%</div></div></td>
                  <td class="text-right"><a class="label label-default" href="#"><i class="fa fa-pencil"></i></a> <a class="label label-danger" href="#"><i class="fa fa-times"></i></a></td>
                </tr>
                <tr class="items">
                  <td><span class="label label-success">Item</span></td>
                  <td><p><strong>Header</strong><span>description</span></p></td>
                  <td class="color-success"><div class="progress"><div class="progress-bar progress-bar-success" style="width: 100%">100%</div></div></td>
                  <td class="text-right"><a class="label label-default" href="#"><i class="fa fa-pencil"></i></a> <a class="label label-danger" href="#"><i class="fa fa-times"></i></a></td>
                </tr>
                <tr class="items">
                  <td><span class="label label-info">Social</span></td>
                  <td><p><strong>Social Networks</strong><span>description</span></p></td>
                  <td class="color-success"><div class="progress"><div class="progress-bar progress-bar-info" style="width: 100%">100%</div></div></td>
                  <td class="text-right"><a class="label label-default" href="#"><i class="fa fa-pencil"></i></a> <a class="label label-danger" href="#"><i class="fa fa-times"></i></a></td>
                </tr>
              </tbody>
            </table>		
            </div>
          </div>
        </div>				

      </div>
      <div class="col-md-5">
        <div class="block-flat">
          <div class="header">							
            <h3>Services</h3>
          </div>
          <div class="content">
            <div id="site_statistics2" style="height: 165px; padding: 0px; position: relative;"></div>							
          </div>
          <div class="content">
            <table class="no-border hover">
              <thead class="no-border">
                <tr>
                  <th style="width:50%;">Service</th>
                  <th>Date</th>
                  <th class="text-right">Amount</th>
                </tr>
              </thead>
              <tbody class="no-border-y">
                <tr>
                  <td style="width:30%;"><i class="fa fa-cloud"></i>Cloud Services</td>
                  <td>01/01/2014</td>
                  <td class="text-right color-success"><i class="fa fa-angle-up"></i>33%</td>
                </tr>
                <tr>
                  <td style="width:30%;"><i class="fa fa-inbox"></i> Hosting Space</td>
                  <td>04/01/2014</td>
                  <td class="text-right color-danger"><i class="fa fa-angle-down"></i>75%</td>
                </tr>
                <tr>
                  <td style="width:30%;"><i class="fa  fa-envelope"></i> Mails Received</td>
                  <td>07/01/2014</td>
                  <td class="text-right color-primary"><i class="fa fa-angle-right"></i>256</td>
                </tr>
              </tbody>
            </table>						
          </div>
        </div>
        <div class="block-flat dark-box visitors">				
            <h4 class="no-margin">Visitors</h4>
          <div class="row">
            <div class="counters col-md-4">
            <h1>477</h1>
            <h1>23</h1>
            </div>							
            <div class="col-md-8">
              <div id="ticket-chart" style="height: 140px;"></div>
            </div>							
          </div>
          <div class="row footer">
            <div class="col-md-6"><p><i class=" fa fa-square"></i> New Visitors</p></div>
            <div class="col-md-6"><p><i class=" return fa fa-square"></i> Returning Visitors</p></div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-7">
        <div class="block ">
          <div class="content  no-padding" id="world-map" style="height: 320px;"></div>
          <div class="content">
            <table class="no-border">
              <thead class="no-border">
                <tr>
                  <th style="width:50%;">Country</th>
                  <th>New Visits</th>
                  <th>Rebound</th>
                  <th class="text-right">Visits</th>
                </tr>
              </thead>
              <tbody class="no-border-x no-border-y">
                <tr>
                  <td style="width:30%;"><img src="images/flags/us.png" alt="flag" /> United States</td>
                  <td>679</td>
                  <td>27%</td>
                  <td class="text-right">1800</td>
                </tr>
                <tr>
                  <td style="width:30%;"><img src="images/flags/in.png" alt="flag" /> India</td>
                  <td>349</td>
                  <td>57%</td>
                  <td class="text-right">1512</td>
                </tr>
                <tr>
                  <td style="width:30%;"><img src="images/flags/gb.png" alt="flag" /> United Kingdom</td>
                  <td>234</td>
                  <td>50%</td>
                  <td class="text-right">530</td>
                </tr>
                <tr>
                  <td style="width:30%;"><img src="images/flags/br.png" alt="flag" /> Brazil</td>
                  <td>34</td>
                  <td>8%</td>
                  <td class="text-right">340</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>				
      </div>
      <div class="col-md-5">
        <div class="block widget-notes">
          <div class="header dark"><h4>Notes</h4></div>
          <div class="content">
            <div class="paper" contenteditable="true">
            Send e-mails.<br>
            <s>meeting at 4 pm.</s><br>
            <s>Buy some coffee.</s><br>
            Talk with Mom about John Doe.
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-5">
        <div class="block widget-notes">
          <div class="header dark"><h4>Console</h4></div>
          <div class="content">
            <div id="console">
            </div>
          </div>
        </div>
      </div>
    </div>
	</div>
    
    </iframe>
	</div>

	
	</div> 
	
</div>
<!-- Right Chat-->

  
	<script src="js/jquery.js"></script>
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
  
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
  <script src="js/behaviour/voice-commands.js"></script>
  <script src="js/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
