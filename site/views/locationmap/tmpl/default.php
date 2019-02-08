<!DOCTYPE html>
<?php
/**
 * @name          : Apptha Eventz
 * @version       : 1.0
 * @package       : apptha
 * @since         : Joomla 1.6
 * @subpackage    : Apptha Eventz.
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2012 Powered by Apptha
 * @license       : GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @abstract      : Apptha Eventz.
 * @Creation Date : November 3 2012
 **/
// No direct Access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_appthaeventz/css/style.css');

?>

<!--   <script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=true&libraries=places"></script>-->
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyC4kh54XP_8Z7PkfDFGC2aFxY04uZinSI8" type="text/javascript"></script>
  <script type="text/javascript"  src="http://maps.google.com/maps/api/js?sensor=false&libraries=places"></script>
    <style>
      body {
        font-family: sans-serif;
        font-size: 14px;
      }
     
      input {
        border: 1px solid  rgba(0, 0, 0, 0.5);
      }
      input.notfound {
        border: 2px solid  rgba(255, 0, 0, 0.4);
      }
    </style>


   <div style="width: 600px;">
     <div id="map" style="width: 580px; height: 400px; float: left;"></div>
     <div id="map1" style="width: 580px; height: 400px; float: left; visibility:hidden;position:absolute; margin-top:0px;"></div>
     
   </div>
    
    <div style="clear:both;"></div>
    <div style="margin:15px 0;">
	<h3>Get directions</h3>
	<label for="from_direction">From</label>
<!--	<input type="text" size="25" id="from_direction" name="from_direction" value="" onchange="calcRoute();" />-->
        <input id="from_direction" name="from_direction" class="input_txt" value="" type="text" size="60" >
        <input  type="hidden" name="lat" id="lat" value="" />
        <input type="hidden" name="lng" id="lng" value="" />
	<input type="button" class="input_button" onclick="calcRoute();" value="<?php echo JText::_('EVENTS_GET_DIRECTIONS');?>">
    </div>

    <div id="panel" style="width: 100%;"></div>
   
    <div id="map_canvas"></div>
      <script>
      function initialize() {
        var mapOptions = {
          center: new google.maps.LatLng(-33.8688, 151.2195),
          zoom: 13,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById('map_canvas'),
          mapOptions);

        var input = document.getElementById('from_direction');
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.bindTo('bounds', map);

        var infowindow = new google.maps.InfoWindow();
        var marker = new google.maps.Marker({
          map: map
        });

        google.maps.event.addListener(autocomplete, 'place_changed', function() {
          infowindow.close();
          marker.setVisible(false);
          input.className = '';
          var place = autocomplete.getPlace();
          if (!place.geometry) {
            // Inform the user that the place was not found and return.
            input.className = 'notfound';
            return;
          }

          // If the place has a geometry, then present it on a map.
          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);  // Why 17? Because it looks good.
          }
          var image = new google.maps.MarkerImage(
              place.icon,
              new google.maps.Size(71, 71),
              new google.maps.Point(0, 0),
              new google.maps.Point(17, 34),
              new google.maps.Size(35, 35));
          marker.setIcon(image);
          marker.setPosition(place.geometry.location);

          var address = '';
          if (place.address_components) {
            address = [
              (place.address_components[0] && place.address_components[0].short_name || ''),
              (place.address_components[1] && place.address_components[1].short_name || ''),
              (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
          }

          infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
          infowindow.open(map, marker);
        });

        // Sets a listener on a radio button to change the filter type on Places
        // Autocomplete.
        function setupClickListener(id, types) {
          var radioButton = document.getElementById(id);
          google.maps.event.addDomListener(radioButton, 'click', function() {
            autocomplete.setTypes(types);
          });
        }

        setupClickListener('changetype-all', []);
        setupClickListener('changetype-establishment', ['establishment']);
        setupClickListener('changetype-geocode', ['geocode']);
      }
      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
   <script type="text/javascript">


 function load() {

      if (GBrowserIsCompatible()) {

        var map = new GMap2(document.getElementById("map"));
        map.addControl(new GSmallMapControl());
        map.addControl(new GMapTypeControl());
        var center = new GLatLng(48.89364,  	2.33739);
        map.setCenter(center, 15);
        geocoder = new GClientGeocoder();
        var marker = new GMarker(center, {draggable: true});
        map.addOverlay(marker);
        //document.getElementById("lat").value = '13.04247';
       // document.getElementById("lng").value = '80.15701' ;

	  GEvent.addListener(marker, "dragend", function() {
       var point = marker.getPoint();
	      map.panTo(point);
       document.getElementById("lat").value = point.lat().toFixed(5);
       document.getElementById("lng").value = point.lng().toFixed(5);

        });


	 GEvent.addListener(map, "moveend", function() {
		  map.clearOverlays();
    var center = map.getCenter();
		  var marker = new GMarker(center, {draggable: true});
		  map.addOverlay(marker);
		  document.getElementById("lat").value = center.lat().toFixed(5);
	          document.getElementById("lng").value = center.lng().toFixed(5);


	 GEvent.addListener(marker, "dragend", function() {
         var point =marker.getPoint();
	     map.panTo(point);
             document.getElementById("lat").value = point.lat().toFixed(5);
	     document.getElementById("lng").value = point.lng().toFixed(5);

        });

        });

      }
    }

	   function showAddress(address) {
	   var map = new GMap2(document.getElementById("map"));
       map.addControl(new GSmallMapControl());
       map.addControl(new GMapTypeControl());
       if (geocoder) {
        geocoder.getLatLng(
          address,
          function(point) {
            if (!point) {
              alert(address + " not found");
            } else {
		  document.getElementById("lat").value = point.lat().toFixed(5);
	   document.getElementById("lng").value = point.lng().toFixed(5);
		 map.clearOverlays()
			map.setCenter(point, 14);
   var marker = new GMarker(point, {draggable: true});
		 map.addOverlay(marker);

		GEvent.addListener(marker, "dragend", function() {
      var pt = marker.getPoint();
	     map.panTo(pt);
      document.getElementById("lat").value = pt.lat().toFixed(5);
	     document.getElementById("lng").value = pt.lng().toFixed(5);
        });


	 GEvent.addListener(map, "moveend", function() {
		  map.clearOverlays();
    var center = map.getCenter();
		  var marker = new GMarker(center, {draggable: true});
		  map.addOverlay(marker);
		  document.getElementById("lat").value = center.lat().toFixed(5);
	   document.getElementById("lng").value = center.lng().toFixed(5);

	 GEvent.addListener(marker, "dragend", function() {
     var pt = marker.getPoint();
	    map.panTo(pt);
    document.getElementById("lat").value = pt.lat().toFixed(5);
	   document.getElementById("lng").value = pt.lng().toFixed(5);
        });

        });

            }
          }
        );
      }
    }

    </script>
  <script type="text/javascript">
//<![CDATA[
var gs_d=new Date,DoW=gs_d.getDay();gs_d.setDate(gs_d.getDate()-(DoW+6)%7+3);
var ms=gs_d.valueOf();gs_d.setMonth(0);gs_d.setDate(4);
var gs_r=(Math.round((ms-gs_d.valueOf())/6048E5)+1)*gs_d.getFullYear();
var gs_p = (("https:" == document.location.protocol) ? "https://" : "http://");
document.write(unescape("%3Cscript src='" + gs_p + "s.gstat.orange.fr/lib/gs.js?"+gs_r+"' type='text/javascript'%3E%3C/script%3E"));
//]]>


</script>

   <script type="text/javascript">


//     var mapDiv = document.getElementById('map');
//          map_location = new google.maps.Map(mapDiv, {
//         center: new google.maps.LatLng(13.06042,80.24958),
//	 zoom: 12,
//	 mapTypeId: google.maps.MapTypeId.ROADMAP
//	});

     var directionsService = new google.maps.DirectionsService();
     var directionsDisplay = new google.maps.DirectionsRenderer();

     var map = new google.maps.Map(document.getElementById('map1'), {
       zoom:7,
       mapTypeId: google.maps.MapTypeId.ROADMAP
     });

     directionsDisplay.setMap(map);
     directionsDisplay.setPanel(document.getElementById('panel'));
      var originplace = '<?php echo JRequest::getVar('location'); ?>';
//     var request = {
//
//       origin: originplace,
//       destination: originplace,
//       travelMode: google.maps.DirectionsTravelMode.DRIVING
//     };

     directionsService.route(request, function(response, status) {

       if (status == google.maps.DirectionsStatus.OK) {
         directionsDisplay.setDirections(response);
       }
     });
     function calcRoute()
	{
            var originplace = document.getElementById('from_direction').value;
            if(originplace == "")
                {
                    alert("Please enter a location");
                }
            else
                {
            document.getElementById('map1').style.visibility = "visible";
            document.getElementById('map').style.visibility = "hidden";
//              var directionsService = new google.maps.DirectionsService();
//     var directionsDisplay = new google.maps.DirectionsRenderer();
//
//     var map = new google.maps.Map(document.getElementById('map'), {
//       zoom:7,
//       mapTypeId: google.maps.MapTypeId.ROADMAP
//     });

//     directionsDisplay.setMap(map);
//     directionsDisplay.setPanel(document.getElementById('panel'));

            

           var request = {

       origin: originplace,
       destination: '<?php echo JRequest::getVar('location'); ?>',
       travelMode: google.maps.DirectionsTravelMode.DRIVING
     };

		directionsService.route(request, function(response, status) {
			if (status == google.maps.DirectionsStatus.OK) {
				directionsDisplay.setDirections(response);
			}
		});
	}
        }
		google.maps.event.addDomListener(window, 'load', initialize);

   </script>

<script type="text/javascript">
        load();
        GUnload();
        showAddress('<?php echo JRequest::getVar('location'); ?>');

    </script>