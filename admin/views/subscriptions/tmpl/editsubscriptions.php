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
if (JRequest::getVar('task') == 'edit') {
 ?>

    <form action='index.php?option=com_appthaeventz&view=subscriptions' method="POST" name="adminForm" id="adminForm" enctype="multipart/form-data" >
        <fieldset class="adminform">
            <legend>Subscriber Information</legend>
            <table class="admintable">
               
            <tr>
                <td class="key" style="width:50%"><?php echo JHTML::tooltip('Enter name for the subscriber', 'Name',
	            '', 'Name');?> <span style="color:red;">*</span> </td>
                <td><input type="text" name="name" id="name" size="32" maxlength="250" value="<?php echo (isset($this->editSubscriptions->name))?$this->editSubscriptions->name:''; ?>" /></td>
            </tr>
            <tr>
                <td class="key"><?php echo JHTML::tooltip('Enter email for the subscriber', 'Email',
	            '', 'Email');?> <span style="color:red;">*</span> </td>
                <td><input type="text" name="email" id="email" size="32" maxlength="250" value="<?php echo (isset($this->editSubscriptions->email))?$this->editSubscriptions->email:''; ?>" /></td>
            </tr>

            <tr>
                <td class="key"><?php echo JHTML::tooltip('Select status for the subscriber', 'Subscription status',
	            '', 'Subscription status');?> </td>
                <td><?php
                    $arrStatus = array('1'=>'Pending','2'=>'Approved','3'=>'Denied')
                    ?>
                    <select name="status" id="status" >
                        <?php foreach($arrStatus as $key=>$value): 
                            $selected = '';
                            if($this->editSubscriptions->status == $key)
                                  $selected = 'selected';
                        ?>
                        <option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td class="key"></td>
                <td>
                    <?php
                    if($this->editSubscriptions->event_name == ''){
                        $event_sname = $this->subscriptionList->event_name;
                    }else{
                        $event_sname = $this->editSubscriptions->event_name;
                    }
                    $resendLink = "index.php?option=com_appthaeventz&view=subscriptions&task=resend&user=".$this->editSubscriptions->name."&eventname=".$event_sname."&mail=".$this->editSubscriptions->email."&sid=".$this->editSubscriptions->id;
                    ?>
                    <a href="<?php echo $resendLink; ?>">Resend activation e-mail</a></td>
            </tr>

            </table>
                </fieldset>


           <fieldset class="adminform">
            <legend>Subscription Details</legend>
            <table class="admintable">
                <tr>
                <td class="key" style="width:32%"><?php echo JHTML::tooltip('Subscribed on Date of the subscriber', 'Subscribed on',
	            '', 'Subscribed on');?> </td>
                <td><?php echo date('F d,Y H:i:s',strtotime($this->editSubscriptions->subscribed_on) ); ?></td>
            </tr>
            <?php if($this->editSubscriptions->event_name == ''){
                    $EventName = $this->subscriptionList->event_name;
                    $StartDate = $this->subscriptionList->start_date;
                    $EndDate = $this->subscriptionList->end_date;
                    $price = 0;
                    $id = $this->subscriptionList->id;
                    $ticketName = 'Free Entrance';
                }else{
                    $EventName = $this->editSubscriptions->event_name;
                    $StartDate = $this->editSubscriptions->start_date;
                    $EndDate = $this->editSubscriptions->end_date;
                    $price = $this->editSubscriptions->price;
                    $id = $this->editSubscriptions->eventid;
                    $ticketName = $this->editSubscriptions->ticket_name;
                }
                    ?>



            <tr>
                <td class="key"><?php echo JHTML::tooltip('Event for the subscriber', 'Event',
	            '', 'Event');?> </td>
                <td><?php 
                    $from = date('F d,Y H:i:s',strtotime($StartDate) );
                    $to = date('F d,Y H:i:s',strtotime($EndDate) );
                    $eventLink = JURI::base().'index.php?option=com_appthaeventz&view=addevent&cid='.$id;
                echo '<a href="'.$eventLink.'">'.$EventName."</a> (".$from." - ".$to.")"; ?> </td>
            </tr>




            <tr>
                <td class="key"><?php echo JHTML::tooltip('Tickets for the subscriber', 'Tickets',
	            '', 'Tickets');?> </td>
                <td><?php echo $this->editSubscriptions->no_tickets." x ".$ticketName."(".$price.")"; ?> </td>
            </tr>
            <?php //} ?>
            <tr>
                <td class="key"><?php echo JHTML::tooltip('Total for the subscriber', 'Total Amount',
	            '', 'Total Amount');?> </td>
                <td><?php echo $this->editSubscriptions->no_tickets*$price; ?> </td>
            </tr>

           

            </table>
                </fieldset>


            <input type="hidden" name="option" value="<?php echo JRequest::getVar('option');?>"/>
            <input type="hidden" name="id" id="id" value="<?php if(!empty($this->editSubscriptions->id)) { echo $this->editSubscriptions->id;} ?>"/>
            <input type="hidden" name="subscribed_on" id="subscribed_on" value="<?php echo date("Y-m-d H:i:s");?>"/>
            <input type="hidden" name="task" value="<?php echo JRequest::getVar('task');?>"/>
            </form>
<?php
                    }
                   
?>
                
<script language="JavaScript" type="text/javascript">
   <?php if(version_compare(JVERSION,'1.6.0','ge'))
                    { ?>Joomla.submitbutton = function(pressbutton) {<?php } else { ?>
                        function submitbutton(pressbutton) {<?php } ?>
        if (pressbutton == "apply" || pressbutton == "applyclose")
        {

            var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
            var namePattern = /^[A-Za-z\s]{3,25}$/;

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