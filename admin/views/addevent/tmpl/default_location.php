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


// no direct access
defined('_JEXEC') or die('Restricted access');
$cid=JRequest::getInt('cid','','get');
$location=$this->eventlocation;

?>
  <script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=true&libraries=places"></script>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $this->setting->google_api; ?>" type="text/javascript"></script>
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
        document.getElementById("lat").value = '<?php if(isset($location->latitude)){echo $location->latitude;} ?>';
        document.getElementById("lng").value ='<?php if(isset($location->latitude)){ echo $location->longitude ;} ?>' ;

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

 

<form action="<?php echo JRoute::_( 'index.php?option=com_appthaeventz&view=addevent&cid='.$cid); ?>" method="post" onsubmit="showAddress(this.address.value); return false">
    <table>
        <tr>
            <td><label for="location_name">Location Name</label></td>
            <td><input id="location_name"  type="text"  name="location_name" value="<?php if(isset($location->location_name)){echo $location->location_name;} ?>" ></td>
    </tr>
<form method="post" onsubmit="showAddress(this.address.value); return false">
    <tr>
        <td><label for="address">Address</label></td>
        <td><textarea name="address"><?php  if(isset($location->address)){ echo $location->address; } ?></textarea></td>
    </tr>
    <tr>
        <input type="hidden" name="lat" id="lat" value="<?php  if(isset($location->latitude)) echo $location->latitude; ?>" />
        <input type="hidden" name="lng" id="lng" value="<?php  if(isset($location->longitude)) echo $location->longitude;?>" />
        <td> <input type="submit" value="Search!" /></td>
        <td><input type="submit" value="Save" onclick="this.disabled=true;this.value='processing';this.form.submit();" /></td>
</tr>
</table>
    <p>
  <div align="center" id="map" style="width: 450px; height: 275px;overflow: auto;"><br/></div>
   </p>

    <input type="hidden" name="task" value="location" />
    <input type="hidden" name="cid" value="<?php echo $cid; ?>" />

</form>
</form>

        
  <script type="text/javascript">
//<![CDATA[
if (typeof _gstat != "undefined") _gstat.audience('','pagesperso-orange.fr');
//]]>
</script>

  <script type="text/javascript">
        load();
        GUnload();
        
    </script>