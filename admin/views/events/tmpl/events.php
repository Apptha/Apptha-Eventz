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

           JToolBarHelper::title('Events','events.png');
           JToolBarHelper::customX('exportIcal','export-excel.png','','Export iCal',true);
           JToolBarHelper::customX('exportCsv','export-excel.png','','Export CSV',true);
           JToolBarHelper::addNew();
           JToolBarHelper::editListX();
           JToolBarHelper::publishList();
           JToolBarHelper::unpublishList();
           JToolBarHelper::trash();
$eventdata=$this->eventdata;
 
  $doc = JFactory::getDocument();
$content=" function tableOrdering( order, dir, task )
{
        var form = document.adminForm;

        form.filter_order.value = order;
        form.filter_order_Dir.value = dir;
        document.adminForm.submit( task );
} ";
        $doc->addScriptDeclaration( $content );
$showcategory=$this->showcategory;
$cat = '';

$mainframe = JFactory::getApplication();
$option = 'com_appthaeventz';
       // page navigation
                       
      $limit = $mainframe->getUserStateFromRequest($option . '.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
      $limitstart = $mainframe->getUserStateFromRequest($option . '.limitstart', 'limitstart', 0, 'int');
       $lim = ($limitstart != '') ? 0+$limitstart :0;
                
      
?>


        <form action='index.php?option=com_appthaeventz&view=events' method="post" name="adminForm" id="adminForm">
            <table class="adminform">
                <tr><td width="100%">
                <label class="filter-search-lbl" for="filter_search">Search</label>
        <input type="text" name="filter_name" id="filter_name" value="<?php echo  $this->filter['filter_name']; ?>" />
        <button type="submit" name="srchbutton" id="srchbutton" value="">Search</button>
        <button type="button" onclick="document.id('filter_name').value='';document.id('filter_category').value='0';this.form.submit();">Clear</button>
                    </td>
                    <td align="right">
                        <select id="filter_status" name="filter_status" onchange="this.form.submit();">
                            <option value="0">- Select Status -</option>
                            <option <?php if($this->filter['filter_status']==1): echo 'selected="selected"'; endif;?> value="1">Published</option>
                            <option <?php if($this->filter['filter_status']==2): echo 'selected="selected"'; endif;?> value="2">Unpublished</option>
                            <option <?php if($this->filter['filter_status']==3): echo 'selected="selected"'; endif;?> value="3">Trashed</option>
                        </select>
                    </td>
                    <td align="right">
                        <select  id="filter_category" name="filter_category" onchange="this.form.submit();">
                            <option value="0">- Select Category -</option>
                <?php
                if($this->category):
                foreach($this->category as $val): ?>
               <option <?php if($val->value==$this->filter['filter_category']): echo 'selected="selected"'; endif;?>  value="<?php echo $val->value; ?>" > <?php echo $val->text; ?> </option>
                <?php endforeach;
                 endif;
                ?>
                </select>
                    </td>
                    
                </tr>
            </table>
            <table class="adminlist">
                                    <thead>
                                        <tr class="sortable">
                                            <th>#</th>
                                            <th width="10"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($eventdata['eventsList'])+1; ?>)" /></th>
                                            <th><?php echo JHTML::_('grid.sort',  'Name', 'e.event_name',$this->sortDirection, $this->sortColumn); ?></th>
                                            <th><?php echo JHTML::_('grid.sort',  'Location','e.location_id',$this->sortDirection, $this->sortColumn ); ?></th>
                                            <th><?php echo JHTML::_('grid.sort',  'Owner','con.contact_name',$this->sortDirection, $this->sortColumn ); ?></th>
                                            <th><?php echo 'Categories'; ?></th>
                                            <th><?php echo JHTML::_('grid.sort',  'Ends on','e.end_date',$this->sortDirection, $this->sortColumn ); ?></th>
                                            <th><?php echo 'Tickets'; ?></th>
                                            <th><?php echo JHTML::_('grid.sort',  'Published', 'e.published',$this->sortDirection, $this->sortColumn); ?></th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $i=1;
                                    if($eventdata['eventsList']):
                                    foreach($eventdata['eventsList'] as $event):
                                        $published = JHTML::_('jgrid.published', $event->published, $i);
                                        $checked = JHTML::_('grid.id', $i, $event->id);
                                        ?>
                                        <tr class="row<?php echo($i%2);?>" >
                                            <td> <?php echo $i + $lim; ?></td>
                                            <td><?php echo $checked; ?></td>
                                            <td>
                                                <p style="float: left;">
                                                 <?php if($event->image_name): echo '<img width="70" height="80" src=\'../images/appthaeventz/thumb_'.$event->image_name.'\' alt="photo" />';
                                                 else: ?>
                                                    <img width="55" height="55" src="<?php echo JURI::base();?>components/com_appthaeventz/assets/images/event_default.png" alt="photo" />
                                                 <?php endif; ?>
                                                </p>
                                                <p style="float: left; padding-top: 25px;">
                                                    <a href="<?php echo JRoute::_( 'index.php?option=com_appthaeventz&view=addevent&cid='.$event->id); ?>">
                                           <?php echo $event->event_name; ?></a> <br />
                                           <?php echo date('F d, Y H:i:s',strtotime($event->start_date) ); ?>
                                                </p>
                                            </td>
                                            <td> <?php echo $event->location_name; ?></td>
                                            <td> <?php echo ($event->contact_name != '')?$event->contact_name:'-';  ?></td>
                                           <td> <?php if(($event->category_id != '')): $cat=explode(",",$event->category_id);
                                            endif;
                                            if($cat){ foreach($cat as $c):
                                             if(isset($showcategory[$c]['name'])){ echo $showcategory[$c]['name']."<br>";}
                                            endforeach;}
                                            else{ echo "-"; } ?></td>
                                            
                                            <td> <?php echo date('F d, Y H:i:s',strtotime($event->end_date) ); ?></td>
                                            <td align="center"> <?php
                                                $gettickets = Jview::loadHelper('tickets'); //got an error without this
                                                $gettickets =  ticketsHelper::getTickets($event->id);
                                                if( !empty($gettickets) ){
                                                foreach($gettickets as $row):
                                                    echo $row->ticket_name." x ".$row->seats." seats<br/>";
                                                endforeach;
                                                }else{
                                                    echo "<span style='color:red;font-size:10px'>No tickets found</span>";
                                                }
                                            ?></td>
                                            <td align="center"> <?php echo $published; ?>

                                        </tr>
                                    <?php $i++; endforeach; else: ?>
                                        <tr><td colspan="10" align="center"> No Events </td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr><td colspan="20" align="center">  <?php echo $eventdata['pageNav']->getListFooter(); ?></td></tr>
                                    </tfoot>
                   </table>
            <input type="hidden" value="" name="task" id="task">
            <input type="hidden" name="boxchecked" value="0">
            
            <input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
            <input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>" />

      
        </form>
<script type="text/javascript" >
/*
    document.getElementById("limit").setAttribute("onchange", "submitform('limitbutton');");
     document.getElementById("filter_category").setAttribute("onchange", "submitform('filter_category_button');");
     document.getElementById("srchbutton").setAttribute("onclick", "submitform('srch');");
    function submitform(button){

        if(button == "limitbutton" || button == "srch" || button == "filter_category_button"){
          document.getElementById("task").setAttribute("value", "")
          document.getElementById("adminForm").submit();
        }
    }
*/
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