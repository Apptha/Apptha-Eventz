<?php
/**
 * @name        Event Mangement
 * @version	1.0: default.php
 * @since       Joomla 1.5,1.6,1.7&2.5
 * @package	apptha
 * @subpackage	com_appthaeventz
 * @copyright   Copyright (C) 2012 Contus Support
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// No direct Access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_appthaeventz/css/style.css');

?><script type="text/javascript" src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
<script src="http://maps.google.com/maps/api/js?sensor=false"   type="text/javascript"></script>

<?php
$events = $this->events;
$event_details=$this->event_details;
$event_settings = $this->event_settings;
if (!empty($event_settings)) {
    $settings_share = json_decode($event_settings->share);
    $settings_showing = json_decode($event_settings->showing);
}


$eventsId = JRequest::getVar('events_id', '0');
$checkIp = $this->checkIp;
$ave_rating = $this->displayRating;
$location = $this->location;
$ave_rating = ceil($ave_rating);
$config = JFactory::getConfig();
$siteName = $config->getValue('sitename');
$event_images = $this->event_images;
if (!empty($checkIp)) {
    $review_rating = $checkIp->review_rating;
} else {
    $review_rating = '0';
}
$event_db_id=0;
if(!empty($event_details))
{
    $event_db_id=$event_details->event_id;
}
JHTML::_('behavior.modal');
?>


<script>
    var eve = jQuery.noConflict();
    var event_id='<?php echo $eventsId; ?>'; var event_db_id=0;
    var event_db_id="<?php echo $event_db_id ; ?>"
    var j=1;
    eve(document).ready(function() {
        if(event_id==event_db_id || <?php echo $review_rating; ?>==0 )
            {
        eve(".rating_star").live("click", function(){
            var star_user_click=$(this).id;
            eve(".rating_star").each(function () {
                if($(this).id <= star_user_click)
                {
                    eve('#'+ $(this).id).removeClass("rating_star");
                    eve('#'+ $(this).id).addClass("rating_active");
                }
            });
  
            eve.ajax({
                type: "POST",
                url: "?option=com_appthaeventz&view=eventctrl&tmpl=component&task=myStarRate",
                data: {'event_id':event_id, 'star_user_click':star_user_click},
                cache: false,
                success: function(ratings){

                    eve(".rating_star").each(function () {

                        eve('#'+ $(this).id).removeClass("rating_star");
                        eve('#'+ $(this).id).addClass("rating_star_hide");
        
      
        
                    });
 
                    eve(".rating_star_hide").each(function () {
     
                        if(ratings >=  j) 
                        {
                            eve('#'+ $(this).id).removeClass("rating_star_hide");
                            eve('#'+ $(this).id).addClass("rating_active");
                            j++;
                        }
        
                    });
                }

            });

        });
 

        eve(".rating_star").each(function () {
     
            if($(this).id <= <?php echo $review_rating; ?>)
            {
                eve('#'+ $(this).id).removeClass("rating_star");
                eve('#'+ $(this).id).addClass("rating_active");
            }
            if(<?php echo $review_rating; ?>!=0)
            {
           if($(this).id > <?php echo $review_rating; ?>)
            {
                eve('#'+ $(this).id).removeClass("rating_star");
                eve('#'+ $(this).id).addClass("rating_star_hide");
            }
       }
        });
}
        //average rating calculation
        var i=1;
        eve(".rating_star_hide").each(function () {
     
            if(<?php echo $ave_rating; ?> >=  i) 
            {
                eve('#'+ $(this).id).removeClass("rating_star_hide");
                eve('#'+ $(this).id).addClass("rating_active");
                i++;
            }
        
        });
  
    });
    
 

  jQuery(document).ready(function() {

      eve('#gallery a').lightBox({fixedNavigation:true,imageLoading:"<?php echo JURI::base().'components/com_appthaeventz/images/lightbox-ico-loading.gif' ?>",imageBtnPrev:"<?php echo JURI::base().'components/com_appthaeventz/images/lightbox-btn-prev.gif' ?>",imageBtnNext:"<?php echo JURI::base().'components/com_appthaeventz/images/lightbox-btn-next.gif' ?>",imageBtnClose:"<?php echo JURI::base().'components/com_appthaeventz/images/lightbox-btn-close.gif' ?>",imageBlank:"<?php echo JURI::base().'components/com_appthaeventz/images/lightbox-blank.gif' ?>"});
    }); 
    
     
    
     
    function images()
    { alert('sad');
         eve('#events_images a').lightBox({fixedNavigation:true,imageLoading:"<?php echo JURI::base().'components/com_appthaeventz/images/lightbox-ico-loading.gif' ?>",imageBtnPrev:"<?php echo JURI::base().'components/com_appthaeventz/images/lightbox-btn-prev.gif' ?>",imageBtnNext:"<?php echo JURI::base().'components/com_appthaeventz/images/lightbox-btn-next.gif' ?>",imageBtnClose:"<?php echo JURI::base().'components/com_appthaeventz/images/lightbox-btn-close.gif' ?>",imageBlank:"<?php echo JURI::base().'components/com_appthaeventz/images/lightbox-blank.gif' ?>"});
    }
</script>
<div id="fb-root"></div>
<script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=373067866088958";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="wraper">



    <h3> <?php echo $events->event_name; ?></h3>
   
    <div class="event_view clear">
         <div id="gallery">
           <?php
           if($events->image_name!='')
           {
               $gallery_image=JURI::base() . 'images/appthaeventz/thumb_' .$events->image_name;
           }else
           {
               $gallery_image=JURI::base() . 'components/com_appthaeventz/images/event_default.png';
           }
            
           
           ?>
           <a rel="lightbox" href="<?php echo $gallery_image ?>"  title="" class="events_big_pic">
               <img src="<?php echo  $gallery_image; ?>" border="0" alt="<?php echo $events->event_name; ?>" height="100" width="100" title="<?php echo $events->event_name; ?>" /></a>
        </div>
        <div class="event_view_details">
            <pre class="date_grid">
<i class="calender_pic"></i> <span><?php if (isset($settings_showing->startdate) == 1){?><?php echo JText::_('EVENTS_FROM'); ?><b> <?php $getFormat = json_decode($this->event_Date->json_data_general); if(!empty($getFormat)) $format = $getFormat[1]." ".$getFormat[2]; echo date($format,strtotime($events->start_date)); ?></b> <?php }if (isset($settings_showing->startdate) && isset($settings_showing->enddate)){echo JText::_('EVENTS_TO'); }
if (isset($settings_showing->enddate) == 1) { ?><b> <?php $getFormat = json_decode($this->event_Date->json_data_general); if(!empty($getFormat)) $format = $getFormat[1]." ".$getFormat[2]; echo date($format,strtotime($events->end_date)); ?></b> <?php } ?><a class="save-to-cal" href="<?php echo JURI::base() . 'index.php?option=com_appthaeventz&view=events&task=exportIcalfmt&events_id=' . $eventsId; ?>"><?php echo JText::_('SAVE_TO_CALENDER'); ?></a></span>
            </pre>
            <div class="clear bullet-points">
                <ul class="floatleft">
                    <?php
                if (isset($settings_showing->location) == 1) {
                     $alias_name= JFilterOutput::stringURLSafe($events->location_name);
                    ?>

                                    <li class="at_name snipt"><?php echo JText::_('EVENTS_AT'); ?> <a href="<?php echo JRoute::_('index.php?option=com_appthaeventz&view=locationmap&location='.$events->location_id.':'.$alias_name); ?>"><?php echo $events->location_name; ?></a></li>

                                    <?php
                }
                    if (isset($settings_showing->category) == 1) {
                         $alias_name= JFilterOutput::stringURLSafe($events->name);
                        ?>

                        <li class="category_name snipt"> <?php echo JText::_('EVENTS_CATEGORIES').':'; ?> <a href="<?php echo JRoute::_('index.php?option=com_appthaeventz&view=eventlist&category_id=' . $events->id.':'.$alias_name); ?>"><?php echo $events->name; ?></a></li>
                        <?php }   $getTags = appthaeventzHelper::tagsList($events->id);
                        if (isset($settings_showing->tags) == 1 && !empty ($getTags)) {
                         
                            ?>
                        
                        <li class="tag_name snipt"> <?php echo JText::_('Tags').':'; ?> 

                        <?php
                        

                        foreach ($getTags as $key => $tags) {
                              $tagPath = JRoute::_('index.php?option=com_appthaeventz&view=eventlist&tag_name=' . $tags->tag_name);
                            ?>
                                <a href="<?php echo $tagPath; ?>">
        <?php echo $tags->tag_name; ?>
                                </a>
                                <?php if (count($getTags) != $key + 1) {
                                    echo ",";
                                }
                            } ?>
                        </li>
                                <?php
                            }
                     
                    if (isset($settings_showing->contact) == 1) {
                        ?>

                        <li class="post_name snipt"> <?php 
                      
                        if(isset($events->contact_name) && $events->contact_name!='')
                        {
                            
                        echo JText::_('EVENTS_CONTACT_NAME').": ";
                        echo $events->contact_name;
                        }
                        ?>
                        </li>
                        <li class="e_mail snipt">
                        <?php
                        if(isset($events->email) && $events->email!='')
                        {
                        echo JText::_('EVENTS_CONTACT_EMAIL').": ";
                        echo $events->email;
                        }?>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                    <?php
                    if (isset($settings_share->rating) == 1) {
                        ?>


                    <ul class="floatright rating_grid">
                        <li class="text-child">Average Rating: </li>
                        <li class="rating_star_hide" id="6"></li>
                        <li class="rating_star_hide" id="7"></li>
                        <li class="rating_star_hide" id="8"></li>
                        <li class="rating_star_hide" id="9"></li>                                                  
                        <li class="rating_star_hide" id="10" ></li>
                    </ul>

                    <div class="clear"></div>
                </div>
                <div class="clear bottom_grid">
                    <ul class="floatleft rating_grid ">
                        <li class="text-child">Rating: </li>
                        <li  class="rating_star"  id="1"></li>
                        <li  class="rating_star"  id="2"></li>
                        <li  class="rating_star"  id="3" ></li>
                        <li  class="rating_star"  id="4"></li>
                        <li  class="rating_star"  id="5"></li>
                    </ul>
                    
    <?php
} if (isset($settings_share->facebook) == 1) {
    ?><div class="social_cotainer">

                    <!-- Facebook like -->
                    <div class="fb-like floatleft" data-href="<?php echo JURI::current(); ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>
                    <?php
                } if (isset($settings_share->twitter) == 1) {
                    ?>
                    <!-- Tweet Button -->
                    <div class="twitter_share">
                        <a href="http://twitter.com/share" class="twitter-share-button floatleft" data-count="none" data-url="<?php echo JURI::current(); ?>" data-via="<?php echo $siteName; ?>" data-text="<?php echo $events->event_name; ?>"><?php echo JText::_('EVENT_MANAGEMENT_TWEET'); ?></a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
                    </div>
                    <?php
                }if (isset($settings_share->google) == 1) {
                    ?>
                        <!-- Google Plus -->

                        <!-- Place this tag where you want the +1 button to render. -->
                        <div class="google_recommend">
                            <!-- Place this tag where you want the +1 button to render. -->
<div class="g-plusone" data-size="medium"></div>
                        </div>
<!-- Place this tag after the last +1 button tag. -->
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
    <?php } ?>
                        </div>
                </div>
            
                    <div class="clear actions">
                        <?php if(date('Y-m-d h:i',strtotime($events->to_date)) != date('Y-m-d h:i')){ ?>
                        <a rel="{handler: 'iframe', size: {x: 750, y: 400}}"  href="<?php echo JRoute::_('index.php?option=com_appthaeventz&view=join&tmpl=component&event_id='. $eventsId); ?>" class="modal control_buttons"><i class="snipt tick">Join</i></a>
                        <?php } ?>
                        <a href="<?php echo JRoute::_('index.php?option=com_appthaeventz&view=invite&event_id='.$eventsId); ?>" class="control_buttons"><i class="snipt add">Invite</i></a>
                   <!--     <a href="" id="total_images" class="control_buttons"><i class="snipt doc"><?php echo JText::_('EVENTS_IMAGES'); ?></i></a> -->
                    </div>
                <div class="clear"></div>
        </div>

        <div class="clear"></div>
      
       
        <span class="disc_content"><?php
if (isset($settings_showing->description) == 1) {

    echo $events->description;
}
?></span><?php

if (isset($settings_showing->map) == 1) {
    ?>
            <div id="map" style="width: 560px; height: 200px; border:1px solid grey;"></div>
                <?php
            }
            ?>
        <br/>

        <div class="clear"></div>
    </div>
    <div class="fb-comments" data-href="<?php echo JURI::current(); ?>" data-num-posts="2" data-width="470"></div>
    <div class="clear"></div>
    
    
</div>

<script type="text/javascript">
var locations = [
    ['<?php echo $location->location_name; ?>', <?php echo $location->latitude; ?>, <?php echo $location->longitude; ?>]
];

var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 10,
    center: new google.maps.LatLng(<?php echo $location->latitude; ?>,<?php echo $location->longitude; ?>),
    mapTypeId: google.maps.MapTypeId.ROADMAP
});

var infowindow = new google.maps.InfoWindow();

var marker, i;

for (i = 0; i < locations.length; i++) {  
    marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map
    });

    google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
            infowindow.setContent(locations[i][0]);
            infowindow.open(map, marker);
        }
    })(marker, i));
}


</script>
<div id="events_images" >
    
</div>