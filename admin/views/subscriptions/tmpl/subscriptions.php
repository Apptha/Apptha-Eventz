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

               
                            $subscriptionsList = $this->subscriptionsList;
                            $lists = $this->subscriptionsList['lists'];
                            
?>
                            <form action='index.php?option=com_appthaeventz&view=subscriptions' method="POST" name="adminForm">
                                
		
        <table class="adminform">
            <tr>
                <td width="100%">
            <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('Search').'&nbsp; '; ?></label>
            <input type="text" name="filter_search" id="filter_search" value="<?php echo JRequest::getVar('filter_search'); ?>" />
            <button type="submit" onclick="return valid();" ><?php echo JText::_('Submit'); ?></button>
            <button type="button" onclick="document.getElementById('filter_search').value='';document.getElementById('filter_status').value='';document.getElementById('filter_events').value='';this.form.submit();" ><?php echo JText::_('Reset'); ?></button>
            </td>

		
            <?php //if( !empty($subscriptionsList['subscriptionsList']) && JRequest::getVar('filter_search') == '' ){ ?>
       
            <td>
             <select id="filter_status" name="filter_status" class="inputbox" onchange="this.form.submit()">
                 <?php
                   $statusFilter=JRequest::getVar('filter_status');
                   $arrStatus = array(''=>'Select status','1'=>'Pending','2'=>'Approved','3'=>'Denied');

                  foreach($arrStatus as $key=>$value):
                      if( $statusFilter == $key){
                            $check = "selected = 'selected'";
                 ?>
                  <option value="<?php echo $key; ?>" <?php echo $check; ?> ><?php echo $value; ?></option>
                  <?php }else{ ?>
                  <option value="<?php echo $key; ?>"  ><?php echo $value; ?></option>
                 <?php } endforeach; ?>
            </select>
            </td>
         

        
             <td>
             <?php echo $lists['events']; ?>
             </td>
         
            <?php //} ?>
          </tr>
       </table>


            <table class="adminlist">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th width="10"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($subscriptionsList['subscriptionsList']); ?>)" /></th>
                                           
                                            <th><?php echo JHTML::_('grid.sort',  'Subscriber', 'name', $subscriptionsList['order_Dir'], $subscriptionsList['order'] ); ?></th>
                                            <th><?php echo JHTML::_('grid.sort',  'Event', 'event_name', $subscriptionsList['order_Dir'], $subscriptionsList['order'] ); ?></th>
                                            <th><?php echo 'Tickets'; ?></th>
                                            <th><?php echo 'Payment Method'; ?></th>
                                            
                                            <th><?php echo JHTML::_('grid.sort',  'Status', 'status', $subscriptionsList['order_Dir'], $subscriptionsList['order'] ); ?></th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
<?php
                            $limitstart = $subscriptionsList['limitstart'];
                            $limit = $subscriptionsList['limit'];
                            $lim = ($limitstart != '')?0+$limitstart:0;
                            $k = 0;
                            $i = 0;
                            if( count($subscriptionsList['subscriptionsList']) > 0  ){
                            foreach ($subscriptionsList['subscriptionsList'] as $row)
                             {
                                
                                $link = JRoute::_('index.php?option=com_eventmanagement&view=subscriptions&task=edit&cid[]=' . $row->id);
                                $checked = JHTML::_('grid.id', $i, $row->id);
                                $createdDate = $row->subscribed_on;
                                $newDate = date('F d, Y H:i:s',strtotime($createdDate) );
                                $from = date('F d, Y H:i:s',strtotime($row->start_date) );
                                $to = date('F d, Y H:i:s',strtotime($row->end_date) );


?>                              
                                <tr class="<?php echo "row$k"; ?>">
                                    <td align="center" style="width:50px;"><?php echo $i + 1 + $lim; ?></td>
                                    <td><?php echo $checked; ?></td>
                                    
                                    <td>
                                       
					
                                        <?php echo '<a href="'.$link.'">'.$this->escape($row->name).'</a><br/><b>Subscribed on </b>: '.$newDate.'<br/><a href="'.$link.'">'.$row->email.'</a> '; ?>
                                    </td>
                                  
                                    <td align="center" style="width:100px;">
                                        <?php
                                        $eventLink = JURI::base().'index.php?option=com_eventmanagement&view=addevent&eid='.$row->eventid;
                                        echo '<a href="'.$eventLink.'">'.$row->event_name.'</a><br/>('.$from.' - '.$to.')'; ?>

                                    </td>
                                    <td align="center" style="width:70px;"><?php 
                                   
                                    if($row->tickets_id != '0'){
                                        $gettickets = Jview::loadHelper('tickets'); //got an error without this
                                        $gettickets =  ticketsHelper::getTicketDetails($row->tickets_id);
                                        echo ($gettickets->price != '0')?($row->no_tickets.' x '.$gettickets->ticket_name.'('.$gettickets->price.')'):'';
                                    }else{
                                        echo ($row->no_tickets != '0')?($row->no_tickets.' x Free Entrance'):'Free Entrance';
                                    }
                                   
                                    ?></td>
                                    <td align="center" style="width:90px;">
                                        <?php
                                            $payname = array('0'=>'-','1'=>'Wire Transfer','2'=>'Paypal','3'=>'Authorized.net');
                                            echo $payname[$row->payment_id];
                                        ?>
                                    </td>
                                    <td align="center" style="width:90px;">
                                        <?php
                                        $arrStatus = array('0'=>'-','1'=>'Pending','2'=>'Approved','3'=>'Denied');
                                        if($row->status == '1')
                                            $color = 'style="color:blue"';
                                        else if($row->status == '2')
                                            $color = 'style="color:green"';
                                        else if($row->status == '3')
                                            $color = 'style="color:red"';
                                        else
                                            $color = '';
                                        ?>
                                        <span <?php echo $color; ?>><?php echo $arrStatus[$row->status]; ?></span>
                                    </td>
                                </tr>
<?php
                                $k = 1 - $k;
                                $i++;

                            }
                          }else{ ?>
                              <tr>
                                <td align="center" colspan="7">
<?php echo "No Records found"; ?>
                                </td></tr>
                        <?php  } ?>
                            <tr>
                                <td colspan="7">
<?php echo $this->subscriptionsList['pageNav']->getListFooter(); ?>
                                </td></tr>
                        </tbody>
                    </table>
<!--                   
                               
<!--                    <input type="hidden" name="option" value="<?php echo $option; ?>" />-->
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