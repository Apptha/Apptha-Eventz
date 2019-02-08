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
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
$document->addScript('' . JURI::base() . 'components/com_appthaeventz/assets/js/jquery-1.8.2.min.js');
$document->addScript('' . JURI::base() . 'components/com_appthaeventz/assets/js/izzycolor/izzyColor.js');
JHTML::_('behavior.tooltip');
?>
<script type="text/javascript">
	var imageUrl="<?php echo JURI::base(); ?>components/com_appthaeventz/assets/js/izzycolor/editor_images/color.png"; // optionally, you can change path for images.
</script>
<?php
if (JRequest::getVar('task') == 'edit') {
 ?>

    <form action='index.php?option=com_appthaeventz&view=categories' method="POST" name="adminForm" id="adminForm" enctype="multipart/form-data" >
        <fieldset class="adminform">
            <legend>Categories</legend>
            <table class="admintable">
            <tr>
                <td class="key" style="width:30%"><?php echo JHTML::tooltip('Enter name for the category', 'Name',
	            '', 'Name');?> <span style="color:red;">*</span></td>
                <td><input type="text" name="name" id="name" size="32" maxlength="250" value="<?php echo (isset($this->editCategory->name))?$this->editCategory->name:''; ?>" onblur="nameExist(this.value,'<?php echo $this->editCategory->id; ?>');"/></td>
            </tr>
            <tr>
                <td class="key"><?php echo JHTML::tooltip('Enter color for the category', 'Color',
	            '', 'Color');?></td>
                <td>
                    <input type="text" value="<?php echo (isset($this->editCategory->color))?$this->editCategory->color:''; ?>" class="izzyColor" name="color" id="color" readonly="1">
                </td>
            </tr>
            <tr>
                <td class="key"><?php echo JHTML::tooltip('Select parent for the category', 'Parent',
	            '', 'Parent');?></td>
                <td>
                    <select id="parent_id" name="parent_id" size="10" style="width:180px;">
                            <option id="" value="0">Select parent</option>
                            <?php

                            foreach ($this->categoryList as $val)
                            {
                                    $selected = '';
                                    $disabled = '';
                                    if ($this->editCategory->parent_id == $val->value)
                                    {
                                            $selected = 'selected="selected"';
                                    }


                                    for($i=$this->editCategory->lft;$i<=$this->editCategory->rgt;$i++){

                                        if($val->lft == $i )
                                                $disabled = 'disabled';
                                        
                                    }

                                    ?>

                            <option id="<?php echo $val->value; ?>" value="<?php echo $val->value; ?>" <?php echo $selected; ?> <?php echo $disabled; ?>>
                                    <?php echo $val->text; ?>
                            </option>
                            <?php   } ?>
                    </select>
                   
                </td>
            </tr>
            <tr>
                <td class="key"><?php echo JHTML::tooltip('Enter description for the category', 'Description',
	            '', 'Description');?></td>
                <td><textarea name="description" id="description" rows="5" cols="10"><?php echo (isset($this->editCategory->description))?$this->editCategory->description:''; ?></textarea></td>
            </tr>

            <tr>
                <td class="key hasTip" title="Enable your category"><?php echo JHTML::tooltip('Select published for the category', 'Status',
	            '', 'Status');?></td>
                <?php  if(isset($this->editCategory->published)) {
                if($this->editCategory->published == "1")
                {
                    $y="checked";
                    $n="";
                }
                else
                {
                    $y="";
                    $n="checked";
                }
                }
                ?>
                <td>
                <table><tr>
                        <td></td>
                        <td>
                        <input type="radio" name="published" id="published" value="1" <?php if(!empty($y)) { echo $y; } ?> checked="checked" style="margin-top:0px;" />Yes</td>
                        <td><input type="radio" name="published" id="published" value="0" <?php if(!empty($n)) { echo $n; } ?> style="margin-top:0px;" />No </td>
                    </tr>


                </table>
				</td>
            </tr>


            </table>
                </fieldset>


            <input type="hidden" name="option" value="<?php echo JRequest::getVar('option');?>"/>
            <input type="hidden" name="id" id="id" value="<?php if(!empty($this->editCategory->id)) { echo $this->editCategory->id;} ?>"/>
            <input type="hidden" name="updated_date" id="updated_date" value="<?php date_default_timezone_set('Asia/Calcutta');echo date("Y-m-d H:i:s");?>"/>
            <input type="hidden" name="task" value="<?php echo JRequest::getVar('task');?>"/>
            </form>
<?php
                    }
                   
?>
                
<script language="JavaScript" type="text/javascript">
   <?php if(version_compare(JVERSION,'1.6.0','ge'))
                    { ?>Joomla.submitbutton = function(pressbutton) {<?php } else { ?>
                        function submitbutton(pressbutton) {<?php } ?>
        if (pressbutton == "apply" || pressbutton == "save")
        {


             if (trim(document.getElementById('name').value) == "")
            {
                alert( "<?php echo JText::_('Please enter Name', true); ?>" )
                return;
            }
            //Ajax method to check the name already exists
            var catName = document.getElementById('name').value;
            var catId = document.getElementById('id').value;

           $.post("?option=com_appthaeventz&view=ajaxfind&tmpl=component&task=getEditCategory",{
                name : catName,
                id : catId
            },function(data)
            {
               if(data != ''){
                    alert('Name already exists');
                    return false;
                }else{
                    submitform( pressbutton );
                    return;
                }
            });

            
        }else if ( pressbutton == "cancel"){
            submitform( pressbutton );
            return;
        }else{
            submitform( pressbutton );
            return;
        }
        

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