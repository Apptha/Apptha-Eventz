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
$cid=JRequest::getInt('cid','');
?>
<form name="newform" action="<?php echo JRoute::_( 'index.php?option=com_appthaeventz&view=addevent&cid='.$cid); ?>" method="post" enctype="multipart/form-data">
    <input type="file" id="photo" name="photo" onchange="return validate();" >
    <input type="hidden" name="task" value="upload" />
    <input type="hidden" name="cid" value="<?php echo $cid; ?>" />
    <br>
    (Supported formats are PNG, JPG and GIF )
</form>

<script type="text/javascript">
 function validate()
        {
           var filecontent =  document.getElementById('photo').value;
           var extension = filecontent.substr(filecontent.lastIndexOf('.') + 1);
           
           if((extension!="jpg")&&(extension != "png")&&(extension != "gif")&&(extension != "jpeg")){
             alert("Please provide a valid Image file");
           return false;
            }
            else
            {
            document.forms["newform"].submit();
            }
        }
</script>
