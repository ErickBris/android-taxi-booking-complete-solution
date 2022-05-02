<?php
require_once('../includes/config.php');
require_once('../includes/database.class.php');

$db= new database($pdo);


?>
<!DOCTYPE html> 
<html lang="en"> 
<head> 
<link href='http://fonts.googleapis.com/css?family=Dosis:400,700' rel='stylesheet' type='text/css'>

	<title>map</title> 
	
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD1IaX4kSk5-iTChJlpPd_7VUJTeW-_Lio&callback=initMap"></script>
   <script type="text/javascript">
 
 var icon = new google.maps.MarkerImage("http://maps.google.com/mapfiles/ms/micons/blue.png",
 new google.maps.Size(32, 32), new google.maps.Point(0, 0),
 new google.maps.Point(16, 16));
 var center = null;
 var map = null;
 var currentPopup;
 var bounds = new google.maps.LatLngBounds();
 function addMarker(lat, lng, info ) {
	
 var pt = new google.maps.LatLng(lat, lng);
 bounds.extend(pt);
 var marker = new google.maps.Marker({
 position: pt,
 icon: icon,
 map: map,
 
 });
 
 var popup = new google.maps.InfoWindow({
 
 
 });
 popup.setContent(info);
 popup.open(map, marker);

 google.maps.event.addListener(marker, "click", function() {
 if (currentPopup != null) {
 currentPopup.close();
 currentPopup = null;
 
 
 }
 popup.open(map, marker);
 currentPopup = popup;
 });
 google.maps.event.addListener(popup, "closeclick", function() {
 map.panTo(center);
 currentPopup = null;
 });
 }
 function initMap() {
 map = new google.maps.Map(document.getElementById("map"), {
 center: new google.maps.LatLng(0, 0),
 zoom: 1,
 mapTypeId: google.maps.MapTypeId.ROADMAP,
 mapTypeControl: false,
 mapTypeControlOptions: {
 style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR
 },
 navigationControl: true,
 navigationControlOptions: {
 style: google.maps.NavigationControlStyle.SMALL
 }
 });
 <?php  
  $db->mapOffers();
 ?>
 center = bounds.getCenter();
 map.fitBounds(bounds);

 }
 </script>
    <style>
     #map{
		border:#CCC thin solid;
        height: 480px;
		margin: 0 0 10px 0;
        padding: 0px
      }
    </style>
	
</head> 

<body onload="initMap()" style="font-family: 'Roboto', sans-serif;" class="home"> 
 <?php  
//  $db->mapOffers();
 ?>
 <div id="map"></div>
    
</body> 
</html>