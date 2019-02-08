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
$document->addScript('' . JURI::base() . 'components/com_appthaeventz/assets/js/colorpicker/jscolor.js');
               
                            $categoriesList = $this->categoriesList;
                            $lists = $this->categoriesList['lists'];
                            
?>
    <form action='index.php?option=com_appthaeventz&view=categories' method="POST" name="adminForm">
                
                    <table class="adminform">
                        <tr>
                            <td width="100%">
                            <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('Search').'&nbsp; '; ?></label>
                            <input type="text" name="filter_search" id="filter_search" value="<?php echo JRequest::getVar('filter_search'); ?>" />
                            <button type="submit" onclick="return valid();" ><?php echo JText::_('Submit'); ?></button>
                            <button type="button" onclick="document.getElementById('filter_search').value='';this.form.submit();" ><?php echo JText::_('Reset'); ?></button>
                            </td>
                        </tr>
                    </table>
		

               <table class="adminlist">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th width="10">
                                    <?php if( !empty($categoriesList['categoriesList']) ){ ?>
                                    <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($categoriesList['categoriesList']); ?>)" />
                                    <?php } ?>
                                </th>
                                <th width="10"><?php echo 'Color'; ?></th>
                                <th><?php echo 'Category Name'; ?></th>
                                <th><?php echo 'Status'; ?></th>
                            </tr>
                        </thead>
                        <tbody>
<?php

                        $limitstart = $categoriesList['limitstart'];
                        $limit = $categoriesList['limit'];
                        $lim = ($limitstart != '')?0+$limitstart:0;
                        
                            $k = 0;
                            $n = count($categoriesList['categoriesList']);
                            if( count($categoriesList['categoriesList']) > 0  ){
                            foreach ($categoriesList['categoriesList'] as $i =>$row)
                             {
                                $published = JHTML::_('grid.published', $row->published, $i);
                                $link = JRoute::_('index.php?option=com_appthaeventz&view=categories&task=edit&cid[]=' . $row->id);
                                $checked = JHTML::_('grid.id', $i, $row->id);
                                
?>
                                <tr class="<?php echo "row$k"; ?>">
                                    <td align="center" style="width:50px;"><?php echo $i + 1 + $lim; ?></td>
                                    <td><?php echo $checked; ?></td>
                                    <td> <div style="background:<?php echo $row->color;?>;width:20px;height:20px;-moz-border-radius: 3px;border-radius: 3px; "></div></td>
                                    <td>
					<span class="editlinktip hasTip" title="<?php echo $row->name; ?>">
					<?php
                                            if($row->level > 1){
                                                for($j=0;$j<($row->level-1);$j++){
                                                    $res[] = '.';

                                                }
                                                $val = implode('',$res);
                                                echo '<a href="'.$link.'">'.str_repeat('<sup>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$val.'|_</sup>',1).''.$row->name.'</a>';
                                            }else{
                                            echo '<a href="'.$link.'">'.str_repeat('<sup>&nbsp;&nbsp;&nbsp;|_</sup>',$row->level).''.$row->name.'</a>'; 
                                            }
                                             ?>
					</span>
					
                                    </td>
                                  
                                    <td align="center" style="width:70px;"><?php echo $published; ?></td>
                                   
                                </tr>
<?php
                                $k = 1 - $k;
                            }
                          }else{ ?>
                              <tr>
                                <td align="center" colspan="6">
                                    <?php echo "No Records found"; ?>
                                </td></tr>
                        <?php  } ?>
                            <tr>
                                <td colspan="7">
<?php echo $this->categoriesList['pageNav']->getListFooter(); ?>
                                </td></tr>
                        </tbody>
                    </table>

            <input type="hidden" name="filter_order" value="<?php echo @$lists['order']; ?>" />
            <input type="hidden" name="filter_order_Dir" value="<?php echo @$lists['order_Dir']; ?>" />
            <input type="hidden" name="task" value=""/>
            <input type="hidden" name="boxchecked" value="0"/>
            <input type="hidden" name="hidemainmenu" value="0"/>
            <input type="hidden" name="parent_id" value="-1"/>

        </form>

<script language="JavaScript" type="text/javascript">
   <?php if(version_compare(JVERSION,'1.6.0','ge'))
                    { ?>Joomla.submitbutton = function(pressbutton) {<?php } else { ?>
                        function submitbutton(pressbutton) {<?php } ?>
        if (pressbutton == "save" || pressbutton == "apply")
        {


            if (document.getElementById('title').value == "")
            {
                alert( "<?php echo JText::_('You must provide a Title', true); ?>" )
                return;
            }

            var input = document.getElementById("link").value;
            if( input != ''){
                var regexp = /^(ht|f)tps?:\/\/[a-z0-9-\.]+\.[a-z0-9-\.]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/;
                if( !regexp.test(input) ){
                     alert("<?php echo JText::_('You must supply a valid Link', true); ?>");
                     return;
                }
            }

            submitform( pressbutton );
            return;
        }
        submitform( pressbutton );
        return;

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

    function valid(){
        if(trim(document.getElementById('filter_search').value) == ''){
            alert("Please enter Keyword to be searched");
            return false;
        }else{
            return true;
        }
    }
</script>