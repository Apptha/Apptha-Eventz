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
JHTML::_('behavior.tooltip');
?>
<?php
if ( JRequest::getVar('task') == 'add') {
 ?>

    <form action='index.php?option=com_appthaeventz&view=subscriptions' method="POST" name="adminForm" id="adminForm" enctype="multipart/form-data" >
        <fieldset class="adminform">
            <legend>Subscriber Information</legend>
            <table class="admintable">
               
            <tr>
                <td class="key" style="width:50%"><?php echo JHTML::tooltip('Enter name for the subscriber', 'Name',
	            '', 'Name');?> <span style="color:red;">*</span></td>
                <td><input type="text" name="name" id="name" size="32" maxlength="250" value="" /></td>
            </tr>
            <tr>
                <td class="key"><?php echo JHTML::tooltip('Enter email for the subscriber', 'Email',
	            '', 'Email');?> <span style="color:red;">*</span></td>
                <td><input type="text" name="email" id="email" size="32" maxlength="250" value="" /></td>
            </tr>
            
            <tr>
                <td class="key"><?php echo JHTML::tooltip('Select status for the subscriber', 'Subscription status',
	            '', 'Subscription status');?></td>
                <td>
                    <select name="status" id="status" >
                        <option id="" value="1">Pending</option>
                        <option id="" value="2">Approved</option>
                    </select>
                </td>
            </tr>

            
            
            </table>
                </fieldset>


           <fieldset class="adminform">
            <legend>Subscription Details</legend>
            <table class="admintable">
                <tr>
                <td class="key" style="width:42%"><?php echo JHTML::tooltip('Subscribed on Date for the subscriber', 'Subscribed on',
	            '', 'Subscribed on');?></td>
                <td><?php echo date('F d,Y H:i:s') ?></td>
            </tr>
            <tr>
                <td class="key"><?php echo JHTML::tooltip('Select ticket for the subscriber', 'Select ticket',
	            '', 'Select ticket');?> <span style="color:red;">*</span></td>
                <td>
                    <select name="tickets_id" id="tickets_id" >
                        <option value="">Select Ticket </option>

                        <?php
                            foreach($this->subscriptionList as $label=>$date):
                                foreach($date as $start=>$price):
                                            $eventDate = $start;

                                    $Event = $label." (".$eventDate.")";
                        ?>
                        <optgroup label="<?php echo $Event; ?>">
                                <?php
                                    foreach($price as $cost=>$event):
                                        $ticketPrice = ($cost != '' )?"($cost)":"";
                                        foreach($event as $eid=>$ticket):
                                            $eventID = 'free-'.$eid;
                                        foreach($ticket as $id=>$name):
                                            $name = ($name != '' )?$name:'Free entrance';
                                            $id = ($id != '')?$id:$eventID;
                                ?>
                                        <option value="<?php echo $id ?>"><?php echo $name.$ticketPrice; ?></option>
                                <?php
                                        endforeach;
                                        endforeach;
                                    endforeach;
                                endforeach;
                                ?>
                        </optgroup>
                        <?php endforeach;  ?>

                    </select>
               </td>
            </tr>
            <tr>
                <td></td> <td> <b style="padding-left:60px">X</b> </td>
            </tr>

            <tr>
                <td class="key"><?php echo JHTML::tooltip('Enter No. of tickets for the subscriber', 'No. of tickets',
	            '', 'No. of tickets');?></td>
                <td>
                   <input type="text" name="no_tickets" id="no_tickets" maxlength="250" value="1" />
                </td>
            </tr>

           

            </table>
                </fieldset>


            <input type="hidden" name="option" value="<?php echo JRequest::getVar('option');?>"/>
            <input type="hidden" name="id" value=""/>
            <input type="hidden" name="subscribed_on" id="subscribed_on" value="<?php echo date("Y-m-d H:i:s");?>"/>
            <input type="hidden" name="task" value="<?php echo JRequest::getVar('task');?>"/>
            </form>
<?php
                    }
                   
?>
                         
<script language="JavaScript" type="text/javascript">

    function chk_duplicate(subName)
    {
        $.post("?option=com_appthaeventz&view=ajaxfind&tmpl=component&task=getSubscriber",{
            name : subName
        },function(data)
        {
            if(data != ''){
                alert('Name already exists');
                return true;
            }
            
        });
    }
   <?php if(version_compare(JVERSION,'1.6.0','ge'))
                    { ?>Joomla.submitbutton = function(pressbutton) {<?php } else { ?>
                        function submitbutton(pressbutton) {<?php } ?>
        if ( pressbutton == "save" || pressbutton == "saveclose")
        {

            var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
            var namePattern = /^[A-Za-z\s]{3,300}$/;

            if (trim(document.getElementById('name').value) == "")
            {
                alert( "<?php echo JText::_('Please enter the Name', true); ?>" )
                return;
            }else if( trim(document.getElementById('name').value) != "" && !namePattern.test(document.getElementById('name').value)){
                alert( "<?php echo JText::_('Please enter valid Name', true); ?>" )
                return;
            }

               
            if (trim(document.getElementById('email').value) == "")
            {
                alert( "<?php echo JText::_('Please enter the Email', true); ?>" )
                return;
            }else if(!pattern.test(document.getElementById('email').value)){
                alert( "<?php echo JText::_('Please provide a valid Email', true); ?>" )
                return;
            }

            <?php if(empty($this->subscriptionList)){ ?>
                 alert( "<?php echo JText::_('Please add Events to subscribe', true); ?>" )
                 return;
            <?php } ?>


            <?php if(!empty($this->subscriptionList)){ ?>
            if (document.getElementById('tickets_id').value == "")
            {
                alert( "<?php echo JText::_('Please select the Ticket', true); ?>" )
                return;
            }
            var tickPattern = /^[0-9]$/;
            if (document.getElementById('no_tickets').value != "" && isNaN(document.getElementById('no_tickets').value) )
            {
                alert( "<?php echo JText::_('Please provide valid No. of tickets', true); ?>" )
                return;
            }
            //Allow only available tickets
            if (document.getElementById('no_tickets').value != "" ){
                    var tktVal = document.getElementById('no_tickets').value;
                    var tktId = document.getElementById('tickets_id').value;
                    if(isNaN(tktId)){
                        tktId = 0;
                    }
                    //alert(tktId);
                   $.post("?option=com_appthaeventz&view=ajaxfind&tmpl=component&task=getTicketCount",{
                        id : tktId
                    },function(data)
                    {
                       if(data != ''){
                          if(data == 0){
                               alert('No tickets available.');
                               return false;
                           }else if( parseInt(document.getElementById('no_tickets').value) > data ){
                               alert('Only '+data+ ' tickets available.Please enter the correct amount of tickets' );
                               return false;
                           }else{
                               submitform( pressbutton );
                               return;
                           } 
                       }else{
                               submitform( pressbutton );
                               return;
                       } 
                    });
                    return false;
            }
            
            <?php } ?>

            

            
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
</script>