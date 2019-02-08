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
$document = JFactory::getDocument();
$document->addScript('' . JURI::base() . 'components/com_appthaeventz/assets/js/jquery-1.8.2.min.js');
$document->addScript('' . JURI::base() . 'components/com_appthaeventz/assets/js/izzycolor/izzyColor.js');

?>
<script type="text/javascript">
	var imageUrl="<?php echo JURI::base(); ?>components/com_appthaeventz/assets/js/izzycolor/editor_images/color.png"; // optionally, you can change path for images.
</script>

<script>
function valid()
{
    var name=document.getElementById('name').value;
    if(trim(name)=='')
    {
    document.getElementById('name').style.border ='1px solid #FF0000';;
    return false;
    }

    var catName = document.getElementById('name').value;

           $.post("?option=com_appthaeventz&view=ajaxfind&tmpl=component&task=getCategory",{
                name : catName
            },function(data)
            {alert(data);
               if(data != ''){
                    //alert('Name already exists');
                    document.getElementById('errName').innerHTML ='Name already exists';;
                    return false;
                }else{
                    submitform( pressbutton );
                    return;
                }
            });
}

function trim(s)
{
        var l=0; var r=s.length -1;
        while(l < s.length && s[l] == ' ')
        {	l++; }
        while(r > l && s[r] == ' ')
        {	r-=1;	}
        return s.substring(l, r+1);
}
</script>
<form action="<?php echo JRoute::_( 'index.php?option=com_appthaeventz&view=addevent&cid='.$cid); ?>" method="post" onsubmit="return valid();">
   <fieldset class="adminform">
            <legend>Categories</legend>
            <table class="admintable">
            <tr>
                <td class="key">Name</td>
                <td><input type="text" name="name" id="name" size="32" maxlength="250"  /><span id="errName"></span></td>
            </tr>
            <tr>
                <td class="key">Color</td>
                <td>
                <input type="text" value="#FFFFFF" class="izzyColor" name="color" id="color">
                </td>
            </tr>
            <tr>
                <td class="key">Parent</td>
                <td>
                    <select name="parent_id" id="parent_id" size="10" style="width:180px;">
<option value="0">Select Parent</option>
<?php     if($onedata->category_id): $one_catgeory=explode(",",$onedata->category_id);  endif;
                foreach($this->category as $val): ?>

                        <option <?php if($one_catgeory)if(in_array($val->value,$one_catgeory)): echo 'selected="selected"'; endif;?>  value="<?php echo $val->value; ?>" > <?php echo $val->text; ?> </option>
                 <?php endforeach;
                ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                <input type="submit" value="Save"  name="submit" >
                <input name="task" type="hidden" id="task" value="category" />
                 <input name="published" type="hidden" value="1" />
                 <input type="hidden" name="created_date" id="created_date" value="<?php echo date("Y-m-d H:i:s");?>"/>
                <input type="hidden" value="2"  name="tabs" >
                <input type="hidden" value="<?php echo $cid; ?>"  name="cid" >
                </td>
            </tr>
                </table>
                </fieldset>

</form>

<script language="JavaScript" type="text/javascript">
   

    function trim(s)
    {
            var l=0; var r=s.length -1;
            while(l < s.length && s[l] == ' ')
            {	l++; }
            while(r > l && s[r] == ' ')
            {	r-=1;	}
            return s.substring(l, r+1);
    }


</script>