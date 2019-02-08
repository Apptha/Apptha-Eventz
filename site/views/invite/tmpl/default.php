
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
$gmailclientid = $this->gmailclient;
?>
<?php
session_start();
// store session data
$_SESSION['event_id'] = JRequest::getVar('event_id');

?>
<div class="invite_form">
<?php $event_id = JRequest::getVar('event_id'); ?>
<?php $redirect = JURI::base()."index.php?option=com_appthaeventz&view=gmailinvite";
         $redirect = urlencode($redirect); ?>
   <!-- <a href='https://accounts.google.com/o/oauth2/auth?client_id=300381478182.apps.googleusercontent.com&redirect_uri=<?php echo $redirect; ?>&scope=https://www.google.com/m8/feeds/&response_type=code'><input type="button" value="Gmail Connect"/></a> -->
     <h2><?php echo JText::_('EVENTS_INVITE_FRIENDS_FROM_EMAIL'); ?></h2>
    <form name="manualinviteForm" method="post" action="<?php echo JRoute::_('index.php?option=com_appthaeventz&view=invite&event_id='.$event_id) ?>">
    <ul style="float:left;margin-top:10px;">
           
            <li>

             <label><?php echo JText::_('EVENTS_EMAIL_ADDRESS_1'); ?></label>   <input type="text" size="30" id="invitefriend1" name="invitefriend1" value=""  />

            </li>
            <li>

              <label><?php echo JText::_('EVENTS_EMAIL_ADDRESS_2'); ?></label>  <input type="text" size="30" id="invitefriend2" name="invitefriend2" value="" />

            </li>
            <li>

              <label><?php echo JText::_('EVENTS_EMAIL_ADDRESS_3'); ?></label>  <input type="text" size="30" id="invitefriend3" name="invitefriend3" value=""    />
            </li>
            <li>

             <label><?php echo JText::_('EVENTS_EMAIL_ADDRESS_4'); ?></label>    <input type="text" size="30" id="invitefriend4" name="invitefriend4" value=""  />
            </li>

             <li>
                 <input type="hidden" name="event_id" value="<?php echo JRequest::getVar('event_id'); ?>">
                <button style="margin-top:5px;" type="submit" onclick="return sendemail()"><?php echo JText::_('EVENTS_SEND_INVITATION'); ?></button>
            </li>
           
    </ul>
    </form>
     <div style="clear: both;">
    <h2><?php echo JText::_('EVENTS_INVITE_FRIENDS_FROM_SOCIAL'); ?></h2>
    <div style="margin-left:17px;">
    <a class="gmail_invite_btn" href="https://accounts.google.com/o/oauth2/auth?client_id=<?php echo $gmailclientid; ?>&redirect_uri=<?php echo $redirect; ?>&scope=https://www.google.com/m8/feeds/&response_type=code" >
        <i class="snipt gmail_icon"><?php echo JText::_('EVENTS_FROM_GMAIL'); ?></i></a>
        <a class="fb_invite_btn" href=<?php echo JRoute::_('index.php?option=com_appthaeventz&view=facebookinvite&events_id='.$event_id) ?>>
        <i class="snipt fb_icon"><?php echo JText::_('EVENTS_FROM_FACEBOOK'); ?></i></a>
    </div>
     </div>
     </div>
<script type="text/javascript">
 function sendemail()
{

var address1 = document.getElementById("invitefriend1").value;
var address2 = document.getElementById("invitefriend2").value;
var address3 = document.getElementById("invitefriend3").value;
 var address4 = document.getElementById("invitefriend4").value;
 if(address1 != ""||address2 != ""||address3 != ""||address4 != "")
     {


var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        
        if(reg.test(address1) == false && address1!=="") {
            alert("Invalid Email Address 1");
            return false;
        }
        
        if(reg.test(address2) == false && address2!=="") {
            alert("Invalid Email Address 2");
            return false;
        }


        
        if(reg.test(address3) == false && address3!=="") {
            alert("Invalid Email Address 3");
            return false;
        }

       
        if(reg.test(address4) == false && address4!=="") {
            alert("Invalid Email Address 4");
            return false;
        }
     }
     else{
         alert("Please Enter Any One Email Address");
         return false;
     }
}
</script>