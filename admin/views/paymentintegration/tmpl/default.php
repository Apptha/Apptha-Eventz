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
jimport('joomla.html.pane');
$status = $this->getstatus;
JHTML::_('behavior.tooltip');

?>
<style>
    .key{padding:10px;}
    .payment_integ{font-size: 12px;}
    .toggle-editor{display:none;}
</style>
<?php if (JRequest::getVar('task') == '') { ?>
    <form action="index.php?option=com_appthaeventz&view=paymentintegration" method="post" name="adminForm">
        <table class="adminlist">

            <thead>
                <tr>
                    <th width="1%">
                        <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                    </th>

                    <th class="title" title="Name" width="15%">
<?php echo 'Payment Gateway'; ?>
                    </th>
                    <th width="1%" title="Published">
<?php echo 'Published'; ?>
                    </th>

                </tr>
            </thead>
<?php if(isset($this->paymentmethods)){
    foreach ($this->paymentmethods as $i => $paymentmethod) :
        $link = JRoute::_('index.php?option=com_appthaeventz&view=paymentintegration&task=edit&cid=' . $paymentmethod->id);

        $published = JHtml::_('grid.published', $paymentmethod->status, $i);
        $checked = JHTML::_('grid.id', $i, $paymentmethod->id); ?>
        <tbody>
            <tr>
                <td class="center"><?php echo $checked; ?></td>
                <td><a href="<?php echo $link; ?>" title="<?php echo $paymentmethod->payment_method; ?>"><?php echo $paymentmethod->payment_method; ?></a></td>
                <td class="center"><?php echo $published; ?></td>
            </tr>
<?php endforeach; }?>
        </tbody>
    </table>

    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />


</form>
<?php } ?>
<?php
    $getwiretransfer = $this->getwiretransfer;
    $cid = JRequest::getVar('cid');
    $cid = $cid[0];
    if ((JRequest::getVar('task') == 'edit') && ($cid == '1')) {
 ?>

        <form action="index.php?option=com_appthaeventz&view=paymentintegration" method="post" class="payment_integ" name="adminForm">
            <fieldset>
                <legend>Wire Transfer</legend>
                <table>
                   
                    <tr>
                        <td valign="top"><?php echo JHTML::tooltip('Enter Details', 'Details','', 'Details');?></td>
                        <td colspan="2"> <?php
        $editor = & JFactory::getEditor();
        echo  $editor->display('content', $getwiretransfer->details, '250', '250', '60', '20', false);
?></td>
            </tr><tr>
             <td></td>
                <td></td></tr>
            <tr >
                <td style="line-height:28px;"><?php echo JHTML::tooltip('Published', 'Published','', 'Published');?></td>
               
                <td>
                    <input style="float: none; vertical-align: middle;" name="published" <?php if ($status[0] == 1) {
                        echo 'checked="checked" ';
                    } ?> type="radio" value="1">yes
                    <input style="float: none; vertical-align: middle;" name="published" <?php if ($status[0] == 0) {
                        echo 'checked="checked" ';
                    } ?> type="radio" value="0">no

                </td>

                <td>
                </td>
            </tr>
        </table>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="cid" value="1" />
    </fieldset>
</form>
<?php
                }
                $getpaypal = $this->getpaypal;
                $cid = JRequest::getVar('cid');
                $cid = $cid[0];
                if ((JRequest::getVar('task') == 'edit') && ($cid == '2')) {
?>
                    <form action="index.php?option=com_appthaeventz&view=paymentintegration" method="post" class="payment_integ" name="adminForm">
                        <fieldset>
                            <legend>Paypal</legend>
                            <table >
                                <tr class="key">
                                    <td  width="33%"><?php echo JHTML::tooltip('Enter your paypal email address', 'Paypal Email','', 'Paypal Email');?> <span style="color:red">*</span></td>
                                    <td><input type="text" id="paypalemail" name="email" size="32" maxlength="250" value="<?php echo $getpaypal->email ?>" ></td>
                                </tr>
                                <tr>
                                    <td style="line-height:28px;"><?php echo JHTML::tooltip('Select the mode of paypal', 'Paypal Mode','', 'Paypal Mode');?></td>
                                    <td>
                                        <input style="vertical-align: middle; float: none;" name="paypalmode[]" <?php if ($getpaypal->mode == 1) {
                        echo 'checked="checked" ';
                    } ?> type="radio" value="1">sandbox

                                        <input style="vertical-align: middle; float: none; margin-left: 10px" name="paypalmode[]" <?php if ($getpaypal->mode == 0) {
                        echo 'checked="checked" ';
                    } ?> type="radio" value="0">live
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo JHTML::tooltip('Published', 'Published','', 'Published');?></td>
                                    <td>
                                        <input style="vertical-align: middle; float: none; " name="published[]" <?php if ($status[1] == 1) {
                        echo 'checked="checked" ';
                    } ?> type="radio" value="1">yes
                                        <input style="vertical-align: middle; float: none; margin-left: 37px" name="published[]" <?php if ($status[1] == 0) {
                        echo 'checked="checked" ';
                    } ?> type="radio" value="0">no

                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                            <input type="hidden" name="task" value="" />
                            <input type="hidden" name="cid" value="2" />

                        </fieldset>
                    </form>
<?php
                }
//$getpaypal = $this->getpaypal;
                $getauthorized = $this->getauthorized;
                $cid = JRequest::getVar('cid');
                $cid = $cid[0];
                if ((JRequest::getVar('task') == 'edit') && ($cid == '3')) {
?>
                    <form action="index.php?option=com_appthaeventz&view=paymentintegration" method="post"  class="payment_integ" name="adminForm">
                        <fieldset>
                            <legend>Authorized .Net</legend>
                            <table>
                                <tr>
                                    <td width="50%"><?php echo JHTML::tooltip('Enter your API login id', 'API login id ','', 'API login id ');?><span style="color:red">*</span></td>
                                    <td colspan="2"><input type="text" id="login_id" name="login_id" value="<?php echo $getauthorized->api ?>" ></td>
                                </tr>
                                <tr>
                                    <td><?php echo JHTML::tooltip('Transaction Key', 'Transaction Key ','', 'Transaction Key  ');?><span style="color:red">*</span></td>
                                    <td colspan="2"><input type="text" id="transaction_key" name="transaction_key" value="<?php echo $getauthorized->transaction_key ?>" ></td>
                                </tr>
                                <tr>
                                    <td><?php echo JHTML::tooltip('Authorized .Net Email', 'Authorized .Net Email','', 'Authorized .Net Email');?> <span style="color:red">*</span></td>
                                    <td colspan="2"><input type="text" id="authemail" name="email" value="<?php echo $getauthorized->email ?>" ></td>
                                </tr>
                                <tr>
                                    <td style="line-height:38px;" ><?php echo JHTML::tooltip('Authorized.net mode', 'Authorized.net mode','', 'Authorized.net mode');?></td>
                                    <td ><input style="vertical-align: middle; margin-top: 0" name="authorizedmode[]" <?php if ($getauthorized->mode == 1) {
                        echo 'checked="checked" ';
                    } ?> type="radio" value="1">sandbox</td>
                                    <td><input style="vertical-align: middle; margin-top: 0" name="authorizedmode[]" <?php if ($getauthorized->mode == 0) {
                        echo 'checked="checked" ';
                    } ?> type="radio" value="0">live</td>
                                </tr>
                                <tr>
                                    <td><?php echo JHTML::tooltip('Published', 'Published','', 'Published');?></td>
                                    <td><input style="vertical-align: middle; margin-top: 0" name="published[]" <?php if ($status[2] == 1) {
                        echo 'checked="checked" ';
                    } ?> type="radio" value="1">yes</td>
                                    <td><input style="vertical-align: middle; margin-top: 0" name="published[]" <?php if ($status[2] == 0) {
                        echo 'checked="checked" ';
                    } ?> type="radio" value="0">no</td>
                                </tr>
                            </table>
                            <input type="hidden" name="task" value="" />
                            <input type="hidden" name="cid" value="3" />

                        </fieldset>
                    </form>
<?php } ?>
<script language="JavaScript" type="text/javascript">
<?php if (version_compare(JVERSION, '1.6.0', 'ge')) { ?>Joomla.submitbutton = function(pressbutton) {


<?php } else { ?>
               function submitbutton(pressbutton) {<?php } ?>
                var cid = '<?php echo JRequest::getVar('cid'); ?>';

             
                if( cid == '2' )
               {

                   if ((pressbutton == "save")||(pressbutton == "apply"))
                   {
                        var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;

                        if (trim(document.getElementById('paypalemail').value) == "")
                        {
                            alert( "<?php echo JText::_('You must provide a Paypal Email', true); ?>" )
                            return;
                        }else if(!pattern.test(document.getElementById('paypalemail').value)){
                            alert( "<?php echo JText::_('You must provide a valid Paypal Email', true); ?>" )
                            return;
                        }


                       submitform( pressbutton );
                       return;
                   }
               }
               else if( cid == '3' )
               {

                   if ((pressbutton == "save")||(pressbutton == "apply"))
                   {
                      if (document.getElementById('login_id').value == "")
                       {
                           alert( "<?php echo JText::_('authorized login id cannot be blank', true); ?>" )
                           return;
                       }
                       

                         var transaction_key = document.getElementById('transaction_key').value;

                         var letters = /^[0-9a-zA-Z]+$/;
                         if(!trim(document.getElementById('login_id').value).match(letters))
                        {
                            alert("Invalid API login id");
                            return;
                        }
                        if(!trim(document.getElementById('transaction_key').value).match(letters))
                        {
                            alert("Invalid Transaction key");
                            return;
                        }
                     
                       var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;

                        if (trim(document.getElementById('authemail').value) == "")
                        {
                            alert( "<?php echo JText::_('You must provide a Authorized.Net Email', true); ?>" )
                            return;
                        }else if(!pattern.test(document.getElementById('authemail').value)){
                            alert( "<?php echo JText::_('You must provide a valid Authorized.Net Email', true); ?>" )
                            return;
                        }


                       submitform( pressbutton );
                       return;
                   }
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
