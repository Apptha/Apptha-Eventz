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
$document->addScript('' . JURI::base() . 'components/com_appthaeventz/js/jquery-1.8.1.min.js');
$eventID = JRequest::getVar('event_id');
JHTML::_('behavior.modal');
//Tickets Retaining
$subscribeID = JRequest::getVar('sub_id');
$ticketID = JRequest::getVar('ticket_id');
$getTicketPay = Jview::loadHelper('appthaeventz'); //got an error without this
$getTicketPay =  appthaeventzHelper::ticketPayment($ticketID,$subscribeID);
?> 



<div class="wraper">
            <h3>Join</h3>
            <form name="joinForm" method="post" action="" onsubmit="return payValid();" target="_parent" >
                <div class="join_form">
                    <ul>
                        <li><label><?php echo JText::_('EVENTS_JOIN_NAME'); ?>:</label> <input type="text" name="cust_name" id="cust_name" value="<?php if(!empty($getTicketPay)){ echo $getTicketPay->name;  } ?>" class="join_input_txt"/></li>
                        <li><label><?php echo JText::_('EVENTS_JOIN_EMAIL'); ?>:</label> <input type="text" name="x_email" id="x_email" value="<?php if(!empty($getTicketPay)){ echo $getTicketPay->email;  } ?>" class="join_input_txt"/></li>
                        <?php if(count($this->ticketList) > 0){  ?>
                        <li><label><?php echo JText::_('EVENTS_JOIN_SELECT_TICKET'); ?>:</label>
                            
                            <select id="cust_ticket" name="cust_ticket" size="1" class="secodary_select" onchange="getAmount1();" style="margin-left:0px;">
                                <option value="0">Select Ticket</option>
                                <?php 
                                    if(!empty($this->ticketList)){
                                    foreach($this->ticketList as $t):  
                                        $tckt = (!empty($getTicketPay))?$getTicketPay->id:'';
                                        $selected = ($tckt == $t->id)?'selected':'';
                                 ?>
                                <option value="<?php echo $t->id; ?>" <?php echo $selected; ?> ><?php echo $t->ticket_name." - ".$t->price." ".$this->paymentList[0]->currency_sign; ?></option>
                                <?php endforeach; } ?>
                            </select>
                            <span id="ticketselectResult">
                                <select name="cust_ticketno" id="cust_ticketno" class="" onchange="getAmount();">
                                        <option value="0"><?php echo "No seats available"; ?></option>
                                </select>
                            </span>
                        </li>
                        <li><label><?php echo JText::_('EVENTS_JOIN_PAYMENT'); ?>:</label>
                            <select id="cust_payment" name="cust_payment" onchange="rs_get_ticket(this.value);" class="payment_select">
                                <option value="">Select Payment</option>
                                <?php foreach($this->paymentList as $p):  ?>
                                <option value="<?php echo $p->id; ?>"><?php echo $p->payment_method; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                        <li id="auth" style="display:none;">
                           <ul style="margin-left:-15px">
                                <li><label><?php echo JText::_('EVENTS_JOIN_CREDIT_CARD_NUMBER'); ?>:</label> <input type="text" name="x_card_num" id="x_card_num" value="" class="join_input_txt"/></li>
                                <li><label><?php echo JText::_('EVENTS_JOIN_EXPIRY_DATE'); ?>:</label> <input type="text" name="x_exp_date" id="x_exp_date" value="" class="join_input_txt"/><span> (mm/yy)</span></li>
                                <li><label><?php echo JText::_('EVENTS_JOIN_CARD_CVV_CODE'); ?>:</label> <input type="text" name="x_card_code" id="x_card_code" value="" class="join_input_txt"/></li>
                                <li><label><?php echo JText::_('EVENTS_JOIN_AMOUNT'); ?>:</label> <input type="text" name="x_amount" id="x_amount" value="" class="join_input_txt" readonly="1"/></li>
                           </ul>
                        </li>
                        <?php } ?>
                        <li>
                            <label>&nbsp;</label>
                            <button type="submit" onclick="return payValid();">Save</button>
                            <button type="submit" onclick="window.parent.SqueezeBox.close();">Cancel</button>
                            <input type="hidden" name="paytype" id="paytype" value=""/>
                        </li>
                    </ul>
                    <div class="clear"></div>
                </div>


             <div class="clear"></div>
            </form>
        </div>

<script type="text/javascript">
    function payValid(){

        var name = document.getElementById('cust_name').value;
        var email = document.getElementById('x_email').value;        
        var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;

        if (name.trim() == ''){
            alert("<?php echo JText::_('EVENTS_JOIN_ENTER_NAME') ?>");
            return false;
        }
        if (email.trim() == ''){
            alert("<?php echo JText::_('EVENTS_JOIN_ENTER_EMAIL') ?>");
            return false;
        }else if (email.trim() != '' && !pattern.test(email)){
            alert("<?php echo JText::_('EVENTS_JOIN_ENTER_VALID_EMAIL') ?>");
            return false;
        }
        <?php if(count($this->ticketList)){  ?>
            var ticket = document.getElementById('cust_ticket').value;
            if (ticket.trim() == ''){
                alert("<?php echo JText::_('EVENTS_JOIN_SELECT_TICKET') ?>");
                return false;
            }

            var payment = document.getElementById('cust_payment').value;
            if (payment.trim() == ''){
                alert("<?php echo JText::_('EVENTS_JOIN_SELECT_PAYMENT') ?>");
                return false;
            }


            var type = document.getElementById('paytype').value;

            if(type == '3'){
                     var strCredit = document.getElementById('x_card_num').value;
                     var strExpiry = document.getElementById('x_exp_date').value;
                     var strCode = document.getElementById('x_card_code').value;
                     var intAmount = document.getElementById('x_amount').value;

                    var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;

                    if(strCredit.trim() == ''){
                        alert("<?php echo JText::_('EVENTS_PLEASE_ENTER_THE_CREDIT_CARD_NUMBER') ?>");
                        document.getElementById('x_card_num').focus();
                        return false;
                    }else if(strCredit.trim() != '' ){
                        var filterNum = /^[0-9]+$/;
                        if (!filterNum.test(strCredit)) {
                            alert("<?php echo JText::_('EVENTS_PLEASE_ENTER_VALID_CREDIT_CARD_NUMBER') ?>");
                            document.getElementById('x_card_num').focus();
                            return false;
                        }
                    }
                    if(strExpiry.trim() == ''){
                        alert("<?php echo JText::_('EVENTS_PLEASE_ENTER_THE_EXPIRY_DATE') ?>");
                        document.getElementById('x_exp_date').focus();
                        return false;
                    }
                    if(strCode.trim() == ''){
                        alert("<?php echo JText::_('EVENTS_PLEASE_ENTER_THE_CARD_CVV_CODE') ?>");
                        document.getElementById('x_card_code').focus();
                        return false;
                    }else if(strCode.trim() != '' ){
                        var filterNum = /^[0-9]+$/;
                        if (!filterNum.test(strCode)) {
                            alert("<?php echo JText::_('EVENTS_PLEASE_ENTER_VALID_CARD_CVV_CODE') ?>");
                            document.getElementById('x_card_code').focus();
                            return false;
                        }
                    }
                    if(intAmount.trim() == ''){
                        alert("<?php echo JText::_('EVENTS_PLEASE_ENTER_THE_AMOUNT') ?>");
                        document.getElementById('x_amount').focus();
                        return false;
                    }else if(intAmount.trim() != '' ){
                        var filterNum = /^[0-9]+$/;
                        if (!filterNum.test(intAmount)) {
                            alert("<?php echo JText::_('EVENTS_PLEASE_ENTER_VALID_AMOUNT') ?>");
                            document.getElementById('x_amount').focus();
                            return false;
                        }
                    }
            }

            if(type == '1')
                document.joinForm.action = '<?php echo JRoute::_("index.php?option=com_appthaeventz&view=join&task=joinWiretransfer"); ?>';
            else if(type == '2')
                document.joinForm.action = '<?php echo JRoute::_("index.php?option=com_appthaeventz&view=join&task=joinPaypal"); ?>';
            else if(type == '3')
                document.joinForm.action = '<?php echo JRoute::_("index.php?option=com_appthaeventz&view=join&task=joinAuth"); ?>';
        <?php }else{ ?>
                document.joinForm.action = '<?php echo JRoute::_("index.php?option=com_appthaeventz&view=join&event_id=$eventID&task=joinEvent"); ?>';
        <?php } ?>

    }

    function rs_get_ticket(val){
        document.getElementById('paytype').value = val;
        var type = document.getElementById('paytype').value;
        if(val == '3'){
            document.getElementById('auth').style.display = 'block';
            var intTickets = document.getElementById('cust_ticketno').value;
            var el = document.getElementById('cust_ticket');
            var text = el.options[el.selectedIndex].text;
            var n = text.split(" - ",2);
            var x = n[1];
            var z = x.split(" ");
            var price = z[0];

            var result = intTickets*price;
            document.getElementById('x_amount').value = result;
        }else{
            document.getElementById('auth').style.display = 'none';
        }
    }

    function getAmount(){
        var intTickets = document.getElementById('cust_ticketno').value;
        var el = document.getElementById('cust_ticket');
        var text = el.options[el.selectedIndex].text;
        var n = text.split(" - ",2);
        var x = n[1];
        var z = x.split(" ");
        var price = z[0];

        var result = intTickets*price;
        document.getElementById('x_amount').value = result;

    }
    //Calling the function to load the tickets dynamically
    getAmount1();

    function getAmount1(){
        var intTickets = document.getElementById('cust_ticketno').value;
        var el = document.getElementById('cust_ticket');
        var tid = el.value;
        if(tid != 0){
        var text = el.options[el.selectedIndex].text;
        var n = text.split(" - ",2);
        var x = n[1];
        var z = x.split(" ");
        var price = z[0];

        var result = intTickets*price;
        document.getElementById('x_amount').value = result;
        }
        
        if(tid == '0'){
            document.getElementById('ticketselectResult').innerHTML = "<select name='cust_ticketno' id='cust_ticketno' onchange='getAmount();'><option value='0'>No seats available</option></select>";
        }else{
            document.getElementById('ticketselectResult').innerHTML = "loading";
           $.post("?option=com_appthaeventz&view=ajaxfind&tmpl=component&task=getSeats",{
                name : tid
            },function(data)
            {
                document.getElementById('ticketselectResult').innerHTML = data;
            });
        }    
    }

</script>