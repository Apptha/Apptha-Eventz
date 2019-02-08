
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
//$facebookinvite;
$fbappid = $this->facebookinvite;

$event_id = JRequest::getvar('events_id');
             

?>    <script type="text/javascript" src="http://connect.facebook.net/en_US/all.js"></script>
 <script type="text/javascript">
        FB.init({ appId: <?php echo $fbappid; ?>,  	    status: true,  	    cookie: true, 	    xfbml: true, 	    oauth: true});

     </script>
 <script type="text/javascript">
     <?php   $event_id = JRequest::getvar('events_id');
             $geteventdetails = $this->geteventdetails;
     ?>
           function newInvite(){
          var receiverUserIds = FB.ui({ 
          method: 'send',
          name: 'Hello,\n\
                 This is an invitation to the event <?php echo $geteventdetails->event_name; ?> that is starting on <?php echo $geteventdetails->start_date; ?>',
          link: '<?php echo JURI::base(); ?>index.php/component/appthaeventz/events/<?php echo $event_id;?>'
          
                 });
           
            }
            
       
       
        </script>
    
   


        <ul class="fb_invite_frnds">
            <li>  <a class="invite_fb_frnds" href="#" onclick="newInvite(); return false;"  >
      <i class="snipt fb_icon"  ><button style="padding:5px;background-color:#eee;border:1px solid grey;" type="button"><?php echo JText::_('EVENTS_INVITE_FROM_FACEBOOK'); ?></button></i
        </a>
            </li>
        </ul>

   
