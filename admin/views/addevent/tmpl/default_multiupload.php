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
$images=$this->oneimage;
//print_r($images);
$document = JFactory::getDocument();

$document->addScript('components/com_appthaeventz/assets/js/1.8.0.js');
?>
<script type="text/javascript">
 //add row
        function add_row()
        {
            $('#more_table').append('<tr><td><input type="file" name="multiphoto[]"  /></td></tr>');

        }
</script>
<style type="text/css">
    .file_img
    {
        width:550px;
}
.file_img li
{
  float: left;
  list-style: none;
  margin-left: 5px;
  margin-bottom: 5px;
}
</style>
<fieldset>
    <legend>Multi Uploads</legend>
<form  action="<?php echo JRoute::_( 'index.php?option=com_appthaeventz&view=addevent&layout=multiupload&tmpl=component&cid='.$cid); ?>" method="post" enctype="multipart/form-data">
    <a href="javascript:void(0);" onclick="add_row();" >Add</a>
    <table border="0" id="more_table">
     <tr><td>
    <input type="file" name="multiphoto[]" >
     </td></tr>
     </table>
    <input type="hidden" name="task" value="multiupload" />
    <input type="hidden" name="cid" value="<?php echo $cid; ?>" />
    (Supported formats are PNG, JPG and GIF )
    <br>
    <input type="submit" name="submit" value="Save" />
     
</form>
</fieldset>
<ul class="file_img">
    <?php foreach($images as $val): ?>
    <li><img src="<?php echo '../images/appthaeventz/thumb_'.$val->image_name; ?>" />
    <a onclick="return confirm('Are you sure want to Delete?');"  href="index.php?option=com_appthaeventz&view=addevent&tmpl=component&task=imagedelete&cid=<?php echo $cid ?>&id=<?php echo $val->image_id; ?>" > Delete </a>
    </li>
    
    <?php endforeach; ?>
</ul>