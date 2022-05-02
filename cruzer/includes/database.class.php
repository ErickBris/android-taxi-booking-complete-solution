<?php
ob_start();
session_start();
class database
{
private $func;

function __construct($pdo)
{
$this->pdo=$pdo; 
}
function adminLogin()
{
	$username= $_POST['username'];
	$password= $_POST['password'];
	$query=$this->pdo->prepare("select * from admin where username='$username' && password= '$password'");
	$query->execute();
	$count =$query->rowCount();
	if ($count == 1)
	{	
		session_start();
		$_SESSION['username']=$_POST['username'];
		header("Location:index.php");
		exit();
	}
	else
	{
		print "Incorrect Username or Password";
	}
}


function addDriver()
{
	$name = $_POST['name'];
	$no = $_POST['no'];
	$email = $_POST['email'];
	$brand = $_POST['brand'];
	$model = $_POST['model'];
	$color = $_POST['color'];
	$reg_no = $_POST['reg_no'];
	$lic_no = $_POST['lic_no'];
	$pass = $_POST['pass'];
	$rate = $_POST['rate'];
	$password = $_POST['password'];
	$lat = 6.855948 ; 
	$lon = 79.862968 ; 
	$insert = $brand.", ".$model.", Color:".$color.", ".$pass. " passenger vehicle";
	
	$query= $this->pdo->prepare("insert into locations values ('','$name',$no, '$email', '$lat', '$lon', '$insert', '$rate', '1','90.00')");
	$query->execute();
	$query1= $this->pdo->prepare("insert into users values ('','$name', '$password', '$email', '$no', 'driver')");
	$query1->execute();
	$rowsadded = $query->rowCount();
	if ($rowsadded == 1 )
	{	
	print "<div class='alert alert-primary alert-white-alt rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-check'></i></div>
								<strong>Success! </strong>New Driver added!</div>";
	}
	else
	{
	print "<div class='alert alert-danger alert-white rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-times-circle'></i></div>
								<strong>Error!</strong> Try again.</div>";
	}
	
}


function getMemID()
{	
$user =$_SESSION["username"] ;
	$date = date('Y-m-d');
	$today = date("Y-m-d h:i:s");
	$query0=$this->pdo->prepare("SELECT * FROM admin where username = '$user' ");
	$query0->execute();
	while ($if=$query0->fetch(PDO::FETCH_OBJ))
	{	
	
	
		return $getuser= $if->admin_id;
		
		
	}
	
}

function adminLogout()
{
session_start();
session_destroy();
header('Location:login.php');
exit();
}

function addUser()
{
	$name = $_POST['name'];
	$password = $_POST['password'];
	
	$query= $this->pdo->prepare("insert into admin values ('','$name','$password')");
	$query->execute();
	$rowsadded = $query->rowCount();
	if ($rowsadded == 1 )
	{	
	print "<div class='alert alert-primary alert-white-alt rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-check'></i></div>
								<strong>Success! </strong>New admin added!</div>";
	}
	else
	{
	print "<div class='alert alert-danger alert-white rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-times-circle'></i></div>
								<strong>Error!</strong> Try again.</div>";
	}
	
}

function getUser()
{

$query= $this->pdo->prepare("select * from admin");
$query->execute();

while ($results=$query->fetch(PDO::FETCH_OBJ))
	{
		print "<option value='$results->admin_id'>$results->username</option>";

	}
}
function deleteAdmin()
{
	$delete = $_POST['del_admin'];
	print "<div class='alert alert-warning alert-white rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-warning'></i></div>
								<strong>Alert!</strong> Do you want to delete the Admin of Id $delete ? <strong>						<a href='del_admin.php?yes=$delete'> YES</a> | <a href='del_admin.php'>NO</a> </strong>
							 </div>";
}
function deleteAdminIfYes()
{
	$delete_id = $_GET['yes'];
	$query=$this->pdo->prepare("delete from admin where admin_id = $delete_id");
	$query->execute();
	$deleted_row= $query->rowCount();
	if ($deleted_row == 1 )
	{	
	print "<div class='alert alert-primary alert-white-alt rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-check'></i></div>
								<strong>Success! </strong>Admin Deleted</div>";
	}
	else
	{
	print "<div class='alert alert-danger alert-white rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-times-circle'></i></div>
								<strong>Error!</strong> Try again.</div>";
	}
	
	
}


function addDeparture()
{
	$name = $_POST['add_dep'];
	$query= $this->pdo->prepare("insert into departure_city values ('','$name')");
	$query->execute();
	$rowsadded = $query->rowCount();
	if ($rowsadded == 1 )
	{	
	print "<div class='alert alert-primary alert-white-alt rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-check'></i></div>
								<strong>Success! </strong>New departue country added!</div>";
	}
	else
	{
	print "<div class='alert alert-danger alert-white rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-times-circle'></i></div>
								<strong>Error!</strong> Try again.</div>";
	}
	
}


function getDepCountry()
{

$query= $this->pdo->prepare("select * from departure_city");
$query->execute();

while ($results=$query->fetch(PDO::FETCH_OBJ))
	{	
	
		print "<option value='$results->dep_city_id'>$results->dep_city_name</option>";

	}
}
function deleteDeparture()
{
	$delete = $_POST['del_dep'];
	print "<div class='alert alert-warning alert-white rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-warning'></i></div>
								<strong>Alert!</strong> Do you want to delete the country of Id $delete ? <strong>						<a href='del_location.php?yes=$delete'> YES</a> | <a href='del_location.php'>NO</a> </strong>
							 </div>";
}
function deleteIfYes()
{
	$delete_id = $_GET['yes'];
	$query=$this->pdo->prepare("delete from departure_city where dep_city_id = $delete_id");
	$query->execute();
	$deleted_row= $query->rowCount();
	if ($deleted_row == 1 )
	{	
	print "<div class='alert alert-primary alert-white-alt rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-check'></i></div>
								<strong>Success! </strong>Country Deleted</div>";
	}
	else
	{
	print "<div class='alert alert-danger alert-white rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-times-circle'></i></div>
								<strong>Error!</strong> Try again.</div>";
	}
	
	
}

function addDestination()
{
	$name = $_POST['add_des'];
	$lat = $_POST['lat'];
	$lon= $_POST['lon'];
	if(isset($_POST['to_scroll'])){	
	$scroll = $_POST['to_scroll'];
	}
	else 	
	$scroll = 0;
	$des = $_POST['des_des'];
	$query= $this->pdo->prepare("insert into destination values ('','$name','$lat','$lon','$des',$scroll)");
	$query->execute();
	$pid= $this->pdo->lastInsertId();
	$newname = "$pid.jpg";
	
	move_uploaded_file( $_FILES['img_path']['tmp_name'], "../images/destination/$newname");
	move_uploaded_file( $_FILES['logo']['tmp_name'], "../images/countries/$newname");
	
	$rowsadded = $query->rowCount();
	
	if ($rowsadded == 1 )
	{	
	print "<div class='alert alert-primary alert-white-alt rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-check'></i></div>
								<strong>Success! </strong>New destination added! </div>";
	}
	else
	{
	print "<div class='alert alert-danger alert-white rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-times-circle'></i></div>
								<strong>Error!</strong> Try again.</div>";
	}
	
}

function getDesCountry()
{

$query= $this->pdo->prepare("select * from destination");
$query->execute();

while ($results=$query->fetch(PDO::FETCH_OBJ))
	{
		
		print "<option value='$results->c_id'>$results->country</option>";

	}
}

function deleteDestination()
{
	$delete = $_POST['del_des'];
	print "<div class='alert alert-warning alert-white rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-warning'></i></div>
								<strong>Alert!</strong> Do you want to delete the country of Id $delete ? <strong>						<a href='del_destination.php?yes=$delete'> YES</a> | <a href='del_destination.php'>NO</a> </strong>
							 </div>";
}


function deleteDesIfYes()
{
	$delete_id = $_GET['yes'];
	$query=$this->pdo->prepare("delete from destination where c_id = $delete_id");
	$query->execute();
	$deleted_row= $query->rowCount();
	if ($deleted_row == 1 )
	{	
	print "<div class='alert alert-primary alert-white-alt rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-check'></i></div>
								<strong>Success! </strong>Country Deleted</div>";
	}
	else
	{
	print "<div class='alert alert-danger alert-white rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-times-circle'></i></div>
								<strong>Error!</strong> Try again.</div>";
	}
	
	
}


function addAirline()
{
	$name = $_POST['name'];
	$kg = $_POST['kg'];
	$max= $_POST['max'];
	$size = $_POST['size'];
	$max_h = $_POST['max_hand'];
	$h_size = $_POST['hand_size'];
	$query= $this->pdo->prepare("insert into airline_details values ('','$name','$kg','$max','$size','$max_h','$h_size')");
	$query->execute();
	$pid= $this->pdo->lastInsertId();
	$newname = "$pid.jpg";
	
	move_uploaded_file( $_FILES['logo']['tmp_name'], "../images/country_logo/$newname");
	
	$rowsadded = $query->rowCount();
	
	if ($rowsadded == 1 )
	{	
	print "<div class='alert alert-primary alert-white-alt rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-check'></i></div>
								<strong>Success! </strong>New destination added! $newname</div>";
	}
	else
	{
	print "<div class='alert alert-danger alert-white rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-times-circle'></i></div>
								<strong>Error!</strong> Try again.</div>";
	}
	
}

function getAirline()
{

$query= $this->pdo->prepare("select * from airline_details");
$query->execute();

while ($results=$query->fetch(PDO::FETCH_OBJ))
	{
		print "<option value='$results->airline_id'>$results->airline</option>";

	}
}


function deleteAirline()
{
	$delete = $_POST['del_airline'];
	print "<div class='alert alert-warning alert-white rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-warning'></i></div>
								<strong>Alert!</strong> Do you want to delete the country of Id $delete ? <strong>						<a href='del_airline.php?yes=$delete'> YES</a> | <a href='del_airline.php'>NO</a> </strong>
							 </div>";
}


function deleteAirlineIfYes()
{
	$delete_id = $_GET['yes'];
	$query=$this->pdo->prepare("delete from airline_details where airline_id = $delete_id");
	$query->execute();
	$deleted_row= $query->rowCount();
	if ($deleted_row == 1 )
	{	
	print "<div class='alert alert-primary alert-white-alt rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-check'></i></div>
								<strong>Success! </strong>Country Deleted</div>";
	}
	else
	{
	print "<div class='alert alert-danger alert-white rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-times-circle'></i></div>
								<strong>Error!</strong> Try again.</div>";
	}
	
	
}


function getoffers()
{
$expiry = date("Y-m-d");	
$query=$this->pdo->prepare("SELECT  * FROM all_offer
INNER JOIN fair_details ON all_offer.fare_id = fair_details.fair_id
INNER JOIN travel_details ON all_offer.travel_id = travel_details.travel_id
INNER JOIN booking_details ON all_offer.booking_id = booking_details.booking_id
INNER JOIN departure_city ON all_offer.departure_id = departure_city.dep_city_id
INNER JOIN airline_details ON all_offer.airline_id = airline_details.airline_id
INNER JOIN destination ON all_offer.destinations_c_id = destination.c_id where all_offer.valid_till > '$expiry' or booking_details.book_by = '0'");
$query->execute();

while($offers=$query->fetch(PDO::FETCH_OBJ))
	{
		if ($offers->book_by == 1)
		{
			$printit ="<div style='float:right; font-weight:bold; color:#124c5e'>Book By : $offers->valid_till</div>";
		}
		else
		$printit ="<div style='float:right; font-weight:bold; color:#124c5e'>Book By : Now Onwards</div>";
		
		print "<li class='$offers->c_id print photography item-small myli'><a class='vimeo' href='more.php?moreinfo=$offers->offer_id'><div class='grid-item-on-hover'><div class='grid-text'><h1 align='center'>Click for more Information &amp; Book Now</h1></div></div><div align='left' class='myoffer'><div class='mydes'>Destination: <span class='mydestext'>$offers->country</span></div><div style='font-size:14px; font-weight:bold; color:#124c5e'>Airline: $offers->airline</div><div align='right' style='float:right'><div class='myfare'>Fare from</div><div><span class='myfareprice'>&pound$offers->fare_price</span> </div></div><div>$offers->travel_class</div><div style='font-weight:bold; color:#124c5e'>From London</div><div>Max Stay: $offers->max_stay</div><div>Travel dates : $offers->travel_date_from - $offers->travel_date_to</div>$printit<div style='font-size:10px; float:left; color:#000'>Subject to Availability / Terms and Conditions Apply</div></div></a></li>";
	
		
	}
}
function getOffersToSlide()
{
	$expiry = date("Y-m-d");
$query=$this->pdo->prepare("SELECT  * FROM all_offer
INNER JOIN fair_details ON all_offer.fare_id = fair_details.fair_id
INNER JOIN travel_details ON all_offer.travel_id = travel_details.travel_id
INNER JOIN booking_details ON all_offer.booking_id = booking_details.booking_id
INNER JOIN departure_city ON all_offer.departure_id = departure_city.dep_city_id
INNER JOIN airline_details ON all_offer.airline_id = airline_details.airline_id
INNER JOIN destination ON all_offer.destinations_c_id = destination.c_id
where all_offer.add_to_slide='1' and all_offer.valid_till > '$expiry'  or booking_details.book_by = '0' limit 5 ");
$query->execute();

while($offers=$query->fetch(PDO::FETCH_OBJ))
	{
		if ($offers->book_by == 1)
		{
			$printit ="<div style='float:right; font-weight:bold; margin-top:10px;  background:#21748c; padding:3px; color:#FFF'>Click to book now : $offers->valid_till</div></a></div>";
		}
		else
		$printit ="<div style='float:right; font-weight:bold; margin-top:10px;  background:#21748c; padding:3px; color:#FFF'>Click to book now : Now Onwards</div></a></div>";
		
		print "<li data-transition='slidedown' data-slotamount='15' data-masterspeed='300'>
					<img src='images/slides/$offers->travel_id.jpg' alt=''/><div style='background:#f2fcff' class='caption box-slide1 lfl ltl' 
						 data-x='0' 
						 data-y='150' 
						 data-speed='300' 
						 data-start='1000' 
						 data-easing='easeOutExpo'><div align='left' class='myoffer'><div class='mydes'>Destination: <span class='mydestext'>$offers->destination</span></div><div style='font-size:14px; font-weight:bold; color:#124c5e'>Airline: $offers->airline</div><div align='right' style='float:right'><div class='myfare'>Fare from</div><div><span class='myfareprice'>&pound$offers->fare_price</span> </div></div><div>$offers->travel_class</div><div style='font-weight:bold; color:#124c5e'>From London</div><div>Max Stay: $offers->max_stay</div><div>Travel dates : $offers->travel_date_from - $offers->travel_date_to</div><div style='font-size:10px; float:left; color:#000'>Subject to Availability / Terms and Conditions Apply</div><a class='vimeo' href='more.php?moreinfo=$offers->offer_id'>$printit</li>";
	
		
	}
}
function moreInfo()

{
$url_id = $_GET['moreinfo'];
$query=$this->pdo->prepare("SELECT  * FROM all_offer
INNER JOIN fair_details ON all_offer.fare_id = fair_details.fair_id
INNER JOIN travel_details ON all_offer.travel_id = travel_details.travel_id
INNER JOIN booking_details ON all_offer.booking_id = booking_details.booking_id
INNER JOIN departure_city ON all_offer.departure_id = departure_city.dep_city_id
INNER JOIN airline_details ON all_offer.airline_id = airline_details.airline_id
INNER JOIN destination ON all_offer.destinations_c_id = destination.c_id
where all_offer.offer_id=$url_id");
$query->execute();
while($info=$query->fetch(PDO::FETCH_OBJ))
{	
if ($info->book_by == 1)
		{
			$printit ="<span class='m_title1'> $info->valid_till</span>";
		}
		else
		$printit ="<span class='m_title1'> Now Onwards</span>";
		
	print "<div><h4>From $info->dep_city_name to $info->country</h4>fare from <span class='m_fare'>&pound;$info->fare_price</span></div><div class='team'>";
	

	
	print "<div class='percent-one-third m_fourth'><span class='m_title'>Travel dates:</span><span class='m_title1'> $info->travel_date_from - $info->travel_date_to</span></div><div class=percent-one-third m_fourth><span class='m_title'>Airline:</span><span class='m_title1'> $info->airline</span></div><div class='percent-one-third m_fourth column-last'><span class='m_title'>Max Stay:</span><span class='m_title1'> $info->max_stay</span></div><div class='percent-one-third m_fourth'><span class='m_title'>Book By:</span>$printit</div><div class='percent-one-third m_fourth'><span class='m_title'>Fair Inc Tax:</span><span class='m_title1'> $info->fair_tax</span></div><div class='percent-one-third m_fourth column-last'><span class='m_title'>Return Inc Tax:</span><span class='m_title1'> $info->return_tax</span></div><div class='percent-one-third m_fourth'><span class='m_title'>Travel Class:</span><span class='m_title1'> $info->travel_class</span></div><div class='percent-one-third m_fourth'><span class='m_title'>Minimum stay period:</span><span class='m_title1'> $info->min_stay</span></div><div class='percent-one-third m_fourth'><span class='m_title'>Maximum stay period:</span><span class='m_title1'> $info->max_stay</span></div><div class='percent-one-third m_fourth'><span class='m_title'>Full Payment:</span><span class='m_title1'> $info->full_payment</span></div><div class='percent-one-third m_fourth'><span class='m_title'>Baggage Allowance:</span><span class='m_title1'> $info->allowed_kg Kg</span></div><div class='percent-one-third m_fourth'><span class='m_title'>Maximum baggage allowed:</span><span class='m_title1'> $info->max_bag</span></div><div class='percent-one-third m_fourth'><span class='m_title'>Maximum hand baggage allowed:</span><span class='m_title1'> $info->max_hand_bag</span></div>
</div>  
		<div class='clear'></div>
                    <div class='one-fourth m_fourth'>		
            <ul>
            <li>Booking changes allowed: $info->booking_chngs</li>
            <li>Maximum baggage size: $info->allowed_size cm<sup>2</sup></li>
			<li>Booking cancellation fee: &pound;$info->cancel_fee</li>
            <li>Advance purchase allowed: $info->advance_purchase</li>	
	";
	}
}

function insertOffers()
{	
	$des= $_POST['destination'];
	$city= $_POST['city'];
	$offer= $_POST['offer'];
	$tax= $_POST['tax'];
	$r_tax= $_POST['r_tax'];
	$airline= $_POST['airline'];
	$class= $_POST['class'];
	$date_from= $_POST['date_from'];
	$date_to= $_POST['date_to'];
	$min_stay= $_POST['min_stay'];
	$max_stay= $_POST['max_stay'];
	$book_by= $_POST['book_by'];
	$full_pay= $_POST['full_pay'];
	$min_pay= $_POST['min_pay'];
	$booking_change= $_POST['booking_change'];
	$cancel_book = $_POST['cancel_book'];
	$cacnel_fee = $_POST['cancel_fee'];
	$adv_prch= $_POST['adv_prch'];
	$adv_pay_day= $_POST['adv_pay_day'];
	$bag_allow= $_POST['bag_allow'];
	$bag_size= $_POST['bag_size'];
	$bag_max= $_POST['bag_max'];
	$lug_allow= $_POST['lug_allow'];
	$lug_size= $_POST['lug_size'];
	$lug_max= $_POST['lug_max'];
	$add_to_slide = $_POST['add_to_slide'];

	$query6=$this->pdo->prepare("BEGIN; insert into all_offers values ('','$city', '$des','$add_to_slide'); insert into fair_details values ('',LAST_INSERT_ID(),'', '$tax',$offer, '$r_tax'); insert into travel_details values ('',LAST_INSERT_ID(), '$airline', '$class', '$date_from', '$date_to','$min_stay', '$max_stay',''); insert into baggage_details  values ('',LAST_INSERT_ID(), $bag_allow, '$bag_size', $bag_max, $lug_max, '$lug_size'); insert into booking_details values ('',LAST_INSERT_ID(),'$book_by','$full_pay',$min_pay, '$booking_change', '$adv_prch', $adv_pay_day, '$cancel_book', $cacnel_fee);  COMMIT;");

	$query6->execute();
	print "<div class='alert alert-primary'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button><i class='a fa-check sign'></i><strong>Success!</strong>New offer added successfully</div>";
}


function insertOffersNew()
{	
	$mem_id= $this->getMemID();
	$today = date("Y-m-d h:i:s");
	$des= $_POST['destination'];
	$city= $_POST['city'];
	$offer= $_POST['offer'];
	$tax= $_POST['tax'];
	$r_tax= $_POST['r_tax'];
	$airline= $_POST['airline'];
	$class= $_POST['class'];
	$date_from= $_POST['date_from'];
	$date_to= $_POST['date_to'];
	$min_stay= $_POST['min_stay'];
	$max_stay= $_POST['max_stay'];
	$book_by= $_POST['book_by'];
	$book_before=$_POST['book_bfre'];
	$full_pay= $_POST['full_pay'];
	$min_pay= $_POST['min_pay'];
	$booking_change= $_POST['booking_change'];
	$cancel_book = $_POST['cancel_book'];
	$cacnel_fee = $_POST['cancel_fee'];
	$adv_prch= $_POST['adv_prch'];
	$adv_pay_day= $_POST['adv_pay_day'];
	$add_to_map='';
	$add_to_slide='';
	if(isset($_POST['to_map'])){
	$add_to_map = $_POST['to_map'];	}
	if(isset($_POST['to_slide'])){
	$add_to_slide = $_POST['to_slide'];}
	
	$query0=$this->pdo->prepare("insert into travel_details values ('','$class','$date_from','$date_to','$min_stay','$max_stay');");
	$query0->execute();
	$pid= $this->pdo->lastInsertId();
	$newname = "$pid.jpg";	
	move_uploaded_file($_FILES['slider_img']['tmp_name'], "../images/slides/$newname");
	
	$query6=$this->pdo->prepare("BEGIN; insert into fair_details values ('','','$tax',$offer, '$r_tax');insert into booking_details values ('','$des','$book_by','$full_pay',$min_pay, '$booking_change', '$adv_prch', $adv_pay_day, '$cancel_book', $cacnel_fee);	insert into all_offer values ('','','$des', '$city', LAST_INSERT_ID(), LAST_INSERT_ID(), LAST_INSERT_ID(),'$airline','$add_to_slide','$add_to_map','$book_before',$mem_id,'$today'); COMMIT;");
	$query6->execute();
	
	$countme = $query6->rowCount();
	
	print "<div class='alert alert-primary alert-white-alt rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-check'></i></div>
								<strong>Success! </strong>New offer added!</div>";
	
}

function mapOffers()
{
	$expiry = date("Y-m-d");
	$query=$this->pdo->prepare("SELECT  * FROM locations");
	$query->execute();
	while ($maps=$query->fetch(PDO::FETCH_OBJ))
	{
		
		$name= "Name: $maps->name";
		$no= "Contact: $maps->number";

		echo ("addMarker($maps->latitude, $maps->longitude,'$name<br/>$no' )\n;");
	}
	
}

function mapOfferss()
{
	$expiry = date("Y-m-d");
	$query=$this->pdo->prepare("SELECT  * FROM locations");
	$query->execute();
	while ($maps=$query->fetch(PDO::FETCH_OBJ))
	{
		//$check_pic="images/countries/$maps->c_id.jpg";
		//$pic= "<img src=\"$check_pic\"/>";
		//$price= "<div style=\"font-weight:bold; color:#900\">£$maps->fare_price</div>";
		print "$maps->latitude,$maps->longitude";
		echo ("addMarker($maps->latitude,$maps->longitude,'hi' )\n;");
	}
	
}

function blog_details()
{ 
	$query=$this->pdo->prepare("select * from destination  where to_scroll = 1");
	$query->execute();
	while ($blog=$query->fetch(PDO::FETCH_OBJ))
	{ 
	
		
	print "<li><img src='images/destination/$blog->c_id.jpg'/><div class='blog-carousel-details'><h2>$blog->country</h2><p>$blog->description</p></div></li>";

	}
	
}

function displayProduct()
{
$query=$this->pdo->prepare("select * from ");
$query->execute();

while ($products=$query->fetch(PDO::FETCH_OBJ))
	{
	print "<div align='center' class=product_box>";
	print "<a href=#><img width=200 height=150 src=images/product/$products->product_id.jpg alt=Image></a>";
	print "<h3><a href=#>$products->name </a></h3>";
	print "<p class=product_price>Rs $products->price</p>";
	print "<a href=p_details.php?product_id=$products->product_id class=add_to_card>Read more</a>";
	print "</div>";
	}
	
}
function categoryProducts()
{
$url_catid = $_GET['catid'];
$query=$this->pdo->prepare("select * from product where catid = $url_catid ");
$query->execute();
$count= $query->rowCount();
if ($count > 0) {
while($products=$query->fetch(PDO::FETCH_OBJ))
	{
	print "<div align='center' class=product_box>";
	print "<a href=#><img width=200 height=150 src=images/product/$products->product_id.jpg alt=Image></a>";
	print "<h3><a href=#>$products->name</a></h3>";
	print "<p class=product_price>Rs $products->price</p>";
	print "<a href=p_details.php?product_id=$products->product_id class=add_to_card>Read more</a>";
	print "</div>";
	}
	}
	else
	print "We have no products listed yet under this category";
}

function p_details()
{
$available="";
$url_id = $_GET['product_id'];
//print $url_id;
$query=$this->pdo->prepare("select * from product where product_id = $url_id");
$query->execute();
while($detail=$query->fetch(PDO::FETCH_OBJ))
	{
		if ($detail->availability=="yes")
			{
			$available="In Stock";
			}
		else if ($detail->availability=="No")
			{
			 $available="Not In Stock";
			}
	 print  "<div class=content_half float_l><img width='250' height='200' src=images/product/$detail->product_id.jpg alt=$detail->name/></div>";
	 print "</div><div class='content_half float_r'>";
	 print "<table><tr>
	 		<td height='30' width='160'>Price</td><td>RS $detail->price</td></tr><tr><td height='30'>Availability:</td><td>$available</td></tr></table>";
	print "<div class='cleaner h20'></div>

			</div>
            <div class='cleaner h30'></div>
            
            <h5>Product Description</h5>
            <p>$detail->description</p>	";
			
			
	} 
}
function searchProduct()
{
if (isset($_POST['term'])) {

$searching = $_POST['term'];
//$searchterm =  $pdo->quote($searching);

$query=$this->pdo->prepare("select * from product where name like '%$searching%' or price like '%$searching%' or description like '%$searching%' ");
$query->execute();
$count= $query->rowCount();
if ($count > 0) {

while ($products=$query->fetch(PDO::FETCH_OBJ))
	{
	print "<div align='center' class=product_box>";
	print "<a href=#><img width=200 height=150 src=images/product/$products->product_id.jpg alt=Image></a>";
	print "<h3><a href=#>$products->name </a></h3>";
	print "<p class=product_price>Rs $products->price</p>";
	print "<a href=p_details.php?product_id=$products->product_id class=add_to_card>Read more</a>";
	print "</div>";
	}
	}
	else
	print "Sorry your search keyword did not match any of our products. Please search again.";

}
}

function insertProduct()
{
if (isset($_POST['name']))
{
$id = "5";
$cat = $_POST['category'];
$name = $_POST['name'];
$desc = $_POST['desc'];
$price = $_POST['price'];
$instock = $_POST['instock'];
//$img_path = $_POST['img_path'];

$query=$this->pdo->prepare("insert into product ( product_id ,  catid ,	 name ,  description ,  price  ,  availability) values ('','$cat','$name','$desc','$price','$instock') ");
$query->execute();
$pid= $this->pdo->lastInsertId();
$newname = "$pid.jpg";

move_uploaded_file( $_FILES['img_path']['tmp_name'], "../images/product/$newname");

}
}
function getCountry()
{
$expiry = date("Y-m-d");
$query= $this->pdo->prepare("SELECT DISTINCT * FROM all_offer
INNER JOIN fair_details ON all_offer.fare_id = fair_details.fair_id
INNER JOIN travel_details ON all_offer.travel_id = travel_details.travel_id
INNER JOIN booking_details ON all_offer.booking_id = booking_details.booking_id
INNER JOIN departure_city ON all_offer.departure_id = departure_city.dep_city_id
INNER JOIN airline_details ON all_offer.airline_id = airline_details.airline_id
INNER JOIN destination ON all_offer.destinations_c_id = destination.c_id where all_offer.valid_till > '$expiry' or booking_details.book_by = '0'  GROUP BY destination.c_id");
$query->execute();

while ($results=$query->fetch(PDO::FETCH_OBJ))
	{
		print "<li><a href='#filter' data-option-value='.$results->c_id'>$results->country</a></li>";

	}
}
function getCountryOld()
{
$expiry = date("Y-m-d");
$query= $this->pdo->prepare("select DISTINCT * from destination INNER JOIN all_offer ON all_offer.destinations_c_id = destination.c_id where all_offer.valid_till > '$expiry'  GROUP BY destination.c_id");
$query->execute();

while ($results=$query->fetch(PDO::FETCH_OBJ))
	{
		print "<li><a href='#filter' data-option-value='.$results->c_id'>$results->country</a></li>";

	}
}
function offersToAdmin()
{
$expiry = date("Y-m-d");	
$query=$this->pdo->prepare("SELECT  * FROM texirequest");
$query->execute();

while($offers=$query->fetch(PDO::FETCH_OBJ))
	{
				
		print "<tr class='odd gradeX'>
					<td>$offers->name</td>
					<td>$offers->location</td>
					<td>$offers->droplocation</td>
					<td>$offers->driver_name</td>
					<td>$offers->timedate</td>
					
				</tr>";
	
		
	}
}
function allCustomer()
{
$expiry = date("Y-m-d");	
$query=$this->pdo->prepare("SELECT  * FROM users where category ='client'");
$query->execute();

while($offers=$query->fetch(PDO::FETCH_OBJ))
	{
				
		print "<tr class='odd gradeX'>
					<td>$offers->user_name</td>
					<td>$offers->user_email</td>
					<td>$offers->user_num</td>
					
					
				</tr>";
	
		
	}
}
function allDriver()
{
$expiry = date("Y-m-d");	
$query=$this->pdo->prepare("SELECT  * FROM users where category ='driver'");
$query->execute();

while($offers=$query->fetch(PDO::FETCH_OBJ))
	{
				
		print "<tr class='odd gradeX'>
					<td>$offers->user_name</td>
					<td>$offers->user_email</td>
					<td>$offers->user_num</td>
					
					
				</tr>";
	
		
	}
}
function deleteOffer()
{
	$delete = $_GET['delete'];
	print "<div class='alert alert-warning alert-white rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-warning'></i></div>
								<strong>Alert!</strong> Do you want to delete the Offer of Id $delete ? <strong>						<a href='all_offer.php?yes=$delete'> YES</a> | <a href='all_offer.php'>NO</a> </strong>
							 </div>";
}
function deleteOfferIfYes()
{
	$delete_id = $_GET['yes'];
	$query=$this->pdo->prepare("delete from all_offer where offer_id = $delete_id");
	$query->execute();
	$deleted_row= $query->rowCount();
	if ($deleted_row == 1 )
	{	
	print "<div class='alert alert-primary alert-white-alt rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-check'></i></div>
								<strong>Success! </strong>Offer Deleted</div>";
	}
	else
	{
	print "<div class='alert alert-danger alert-white rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-times-circle'></i></div>
								<strong>Error!</strong> Try again.</div>";
	}
	
	
}
function editOffer()
{

	$query1=$this->pdo->prepare("select * from destination");
	$query1->execute();	
	
	print "<form action='' id='myform2' method='post' enctype='multipart/form-data'><div class='header'>							
            <h3>Edit Selected Offer</h3>
          </div><div class='form-group col-md-3 setmyheight'>
              <label class='control-label'>Select Destination</label><select name='destination' class='form-control'>";
	 while ($des=$query1->fetch(PDO::FETCH_OBJ))
	{ 	
    print"<option value='$des->c_id'>$des->country</option>";
	
	}
	print "</select> </div>";
	$query2=$this->pdo->prepare("select * from  departure_city");
	$query2->execute();	
	
	print "<div class='form-group col-md-3 setmyheight'>
              <label class='control-label'>Select Depature</label><select name='departure' class='form-control'>";
	 while ($dep=$query2->fetch(PDO::FETCH_OBJ))
	{ 	
    print"<option value='$dep->dep_city_id'>$dep->dep_city_name</option>";
	
	}
	print "</select> </div>";
	
	 
	$targetID = $_GET['edit'];
    $query=$this->pdo->prepare("SELECT  * FROM all_offer
INNER JOIN fair_details ON all_offer.fare_id = fair_details.fair_id
INNER JOIN travel_details ON all_offer.travel_id = travel_details.travel_id
INNER JOIN booking_details ON all_offer.booking_id = booking_details.booking_id
INNER JOIN departure_city ON all_offer.departure_id = departure_city.dep_city_id
INNER JOIN airline_details ON all_offer.airline_id = airline_details.airline_id
INNER JOIN destination ON all_offer.destinations_c_id = destination.c_id where all_offer.offer_id ='$targetID' ");
	$query->execute();
    while ($offer=$query->fetch(PDO::FETCH_OBJ))
	{           
			 $to = $offer->country;
			 $from= $offer->dep_city_name;
			 $price=$offer->fare_price;
			 $airline=$offer->airline;
			 $from=$offer->travel_date_from;
			 $to=$offer->travel_date_to;
			 $min=$offer->min_stay;
			 $max=$offer->max_stay;			 			 
	 print " <div class='form-group col-md-3 setmyheight'>
              <label>Fare Offer</label> <input type='text' value='$offer->fare_price' name='offer' parsley-trigger='change'  placeholder='' class='form-control'>
            </div>
			<div class='form-group col-md-3 setmyheight'>
              <label>Offer Date From</label> <input type='date' value='$from' name='offer_from' parsley-trigger='change'  placeholder='' class='form-control'>
            </div>
			<div class='form-group col-md-3 setmyheight'>
              <label>Offer Date To</label> <input type='date' value='$to' name='offer_to' parsley-trigger='change'  placeholder='' class='form-control'>
            </div>
			<div class='form-group col-md-3 setmyheight'>
              <label>Min Stay</label> <input type='text' value='$min' name='min' parsley-trigger='change'  placeholder='' class='form-control'>
            </div>
			<div class='form-group col-md-3 setmyheight'>
              <label>Max Stay</label> <input type='text' value='$max' name='max' parsley-trigger='change'  placeholder='' class='form-control'>
			  <input type='hidden' value='$targetID' name='myid' parsley-trigger='change'  placeholder='' class='form-control'>
			  <input type='hidden' value='$offer->travel_id' name='travelid' parsley-trigger='change'  placeholder='' class='form-control'>
 			  <input type='hidden' value='$offer->fare_id' name='fareid' parsley-trigger='change'  placeholder='' class='form-control'>
            </div>
			<div class='clear'></div>
			<div class='form-group col-md-3 setmyheight'>
			<button class='btn btn-primary' type='submit'>Update</button>
            <button type='reset' class='btn btn-default'>Cancel</button>
            </div>
			<div class='clear'></div></form>
			
			";
     }

}
function completeUpdate()
{
$myid= ($_POST['myid']);
$travelid  	= ($_POST['travelid']);
$fareid= ($_POST['fareid']);
$max =($_POST['max']); 
$min= ($_POST['min']);
$offer_to = ($_POST['offer_to']);
$offer_from =($_POST['offer_from']);
$offer = ($_POST['offer']);
$departure = ($_POST['departure']);
$destination = ($_POST['destination']);	

$query1=$this->pdo->prepare("UPDATE fair_details SET fare_price	='$offer' WHERE fair_id='$fareid'");
$query1->execute();
$query2=$this->pdo->prepare("UPDATE  travel_details SET travel_date_from='$offer_from', travel_date_to='$offer_to', min_stay='$min', max_stay='$max' WHERE travel_id='$travelid'");
$query2->execute();
$query3=$this->pdo->prepare("UPDATE all_offer SET destinations_c_id='$destination', 	departure_id='$departure' WHERE offer_id='$myid'");
$query3->execute();
print "<div class='alert alert-primary alert-white-alt rounded'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
								<div class='icon'><i class='fa fa-check'></i></div>
								<strong>Success! </strong>Offer Updated</div>";
}





}
?>


