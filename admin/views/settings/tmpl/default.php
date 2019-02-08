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
$document = JFactory::getDocument();
$app = JFactory::getApplication();


$document->addScript('components/com_appthaeventz/assets/js/1.8.0.js');
$document->addStyleSheet('components/com_appthaeventz/assets/css/jquery-ui-1.8.23.custom.css');

$document->addScript('components/com_appthaeventz/assets/js/jquery-ui-1.8.23.custom.js');

$document->addScript('http://tinymce.moxiecode.com/js/tinymce/jscripts/tiny_mce/jquery.tinymce.js');
$document->addScript('http://tinymce.moxiecode.com/js/tinymce/jscripts/tiny_mce/tiny_mce.js');
JHTML::_('behavior.modal');
JHTML::_('behavior.tooltip');

$fbdetails = $this->fbdetails;
$googledetails = $this->googledetails;
$date_general = $this->getgeneralsettings['1'];
$time_general = $this->getgeneralsettings['2'];
$multipletickets = $this->geteventsettings['0'];
$commenting = $this->geteventsettings['1'];
$smallwidth = $this->geteventsettings['2'];
$smallheight = $this->geteventsettings['3'];
$largewidth = $this->geteventsettings['4'];
$largeheight = $this->geteventsettings['5'];
$paymentsettings = $this->getpaymentsettings;
$emailsettings = $this->getemailsettings;

$getcurrencydetails = $this->getcurrencydetails;


?>
<html lang="en">
    <head>



        <style>
            .ui-tabs-vertical { width: 55em; }
            .ui-tabs-vertical .ui-tabs-nav { padding: 0; float: left; width: 12em; }
            .ui-tabs-vertical .ui-tabs-nav li { clear: left; width: 100%;  }
            .ui-tabs-vertical .ui-tabs-nav li a { display:block; }
            .ui-tabs-vertical .ui-tabs-nav li.ui-tabs-selected { padding-bottom: 0; padding-right: .1em;}
            .ui-tabs-vertical .ui-tabs-panel { padding: 1em; float: right; width: 40em;}
            #smallwidth {width:30px;}
            #smallheight {width:30px;}
            #largewidth {width:30px;}
            #largeheight {width:30px;}

        </style>
    </head>

    <body>
<?php if ((JRequest::getVar('task') == '')) { ?>
            <div class="demo">
                <form action="index.php?option=com_appthaeventz&view=settings" method="post" name="adminForm">
                    <div id="tabs" class="tabs">
                        <ul id="settings_ul_id">
                            <li  id="general"><a class="key" onclick="storetask('general','tabs-1')" href="#tabs-1" title="General">General</a></li>
                            <li  id="events"><a  class="key" onclick="storetask('events','tabs-2')" href="#tabs-2" title="Events">Event</a></li>
                            <li  id="emails"><a class="key" onclick="storetask('emails','tabs-3')" href="#tabs-3" title="Emails">Email</a></li>
                            <li  id="payments"><a class="key" onclick="storetask('payments','tabs-4')" href="#tabs-4" title="Payments">Payment</a></li>
                        </ul>
                        <div id="tabs-1">


                            <h2>License</h2>
                            <table class="adminlist">

                                <tr>
                                    <td width="28%" class="key" ><?php echo JHTML::tooltip('Enter the license code', 'License Code','', 'License Code');?>
 <span style="color:red">*</span> </td>
                            </tr>
                            <tr>
                        </table>
                        <h2>Date And Time</h2>
                        <table class="adminlist">
                            <td class="key" width="28%"><?php echo JHTML::tooltip('Set the default date format', 'Date','', 'Date');?> </td>
                            <td><select id="global_dates" name="global_dates" onchange="change_datetime('global_date',this.value)">
                                         <option <?php if(isset($date_general)){if (($date_general) == "d F Y") { echo 'selected';
    } }?> value="d F Y">30 November 2011</option>
                                     <option <?php if(isset($date_general)){if (($date_general) == "F d, Y") { echo 'selected';
    } }?> value="F d, Y">November 30, 2011</option>
                                     <option <?php if(isset($date_general)){if (($date_general) == "m/d/y") { echo 'selected';
    } }?>  value="m/d/y">11/30/11</option>
                                     <option <?php if(isset($date_general)){if (($date_general) == "d.m.Y") { echo 'selected';
    } }?> value="d.m.Y">30.11.2011</option>
                                     <option <?php if(isset($date_general)){if (($date_general) == "m.d.Y") { echo 'selected';
    } }?> value="m.d.Y">11.30.2011</option>
                                </select></td>
                            </tr>
                            <tr>
                                <td class="key" width="28%"><?php echo JHTML::tooltip('Set the default time format', 'Time','', 'Time');?></td>
                                <td><select id="global_times" name="global_times" onchange="change_datetime('global_time',this.value)">
                                        <option <?php if(isset($time_general)){if (($time_general) == "H:i:s") { echo 'selected';
    } }?> value="H:i:s">13:23:10</option>
                                    <option <?php if(isset($time_general)){if (($time_general) == "H:i") { echo 'selected';
    } }?> value="H:i">13:23</option>
                                    <option <?php if(isset($time_general)){if (($time_general) == "g:i a") { echo 'selected';
    } }?> value="g:i a">1:23 pm</option>
                                    </select></td>
                            </tr>
                        </table>


                        <input type="hidden" name="boxchecked" value="0" />



                    </div>
                    <div id="tabs-2">


                        <h2>Event Registration</h2>
                        <table class="adminlist">
                            <tr>
                                <td width="28%"><?php echo JHTML::tooltip('Allow users to purchase multiple tickets on one subscription', 'Multiple Tickets','', 'Multiple Tickets');?></td>
                                <td><input type="radio" <?php if ($multipletickets == 0) {
        echo 'checked="checked" ';
    } ?> checked="checked" name="multipletickets" value="1">yes
                                    <input name="multipletickets" <?php if ($multipletickets == 0) {
        echo 'checked="checked" ';
    } ?> type="radio" value="0">no</td>
                            </tr>

                        </table>

                        <h2>Facebook Settings</h2>
                        <table class="adminlist">
                            <tr>
                                <td width="28%" ><?php echo JHTML::tooltip('Enter Your FB App ID', 'FB App ID','', 'FB App ID');?></td>
                                <td><input id="fbappid" name="fbappid"  type="text" value="<?php if(isset($fbdetails->fb_appid)){echo $fbdetails->fb_appid;} ?>"><a target="_blank" href="https://developers.facebook.com/apps"><img src="<?php echo str_replace('administrator/', '', JURI::base()) ?>media/system/images/notice-info.png"  height="16px" weight="16px" class="hasTip" title="Click here to create your Facebook App" /></a></td>
                            </tr>
                             <tr>
                                <td width="28%" ><?php echo JHTML::tooltip('Enter Your FB Secret Key', 'FB Secret Key','', 'FB Secret Key');?></td>
                                <td><input id="fbsecretkey" name="fbsecretkey"  type="text" value="<?php if(isset($fbdetails->fb_secret)){echo $fbdetails->fb_secret;} ?>"></td>
                            </tr>

                        </table>
                         <h2>Google Settings</h2>
                        <table class="adminlist">
                            <tr>
                                <td width="28%" ><?php echo JHTML::tooltip('Enter Your Google Api Key', 'Google Api Key','', 'Google Api Key');?> <span style="color:red">*</span></td>
                                <td><input id="googleapi" name="googleapi"  type="text" value="<?php if(isset($googledetails->google_api)){echo $googledetails->google_api;} ?>"><a target="_blank" href="https://code.google.com/apis/console/"><img src="<?php echo str_replace('administrator/', '', JURI::base()) ?>media/system/images/notice-info.png"  height="16px" weight="16px" class="hasTip" title="Click here to create your Google App" /></a></td>
                            </tr>
                            <tr>
                                <td width="28%" ><?php echo JHTML::tooltip('Enter Your Google Client Id', 'Google Client Id','', 'Google Client Id');?> <span style="color:red"></span></td>
                                <td><input id="googleclient" name="googleclient"  type="text" value="<?php if(isset($googledetails->googleclient)){echo $googledetails->googleclient;} ?>"></td>
                            </tr>
                            <tr>
                                <td width="28%" ><?php echo JHTML::tooltip('Enter Your Google Client Secret Key', 'Google Client Secret Key','', 'Google Client Secret Key');?> <span style="color:red"></span></td>
                                <td><input id="googlesecret" name="googlesecret"  type="text" value="<?php if(isset($googledetails->googlesecret)){echo $googledetails->googlesecret;} ?>"></td>
                            </tr>
                        </table>

                        <h2>Others</h2>
                        <table class="adminlist">

                            <tr>
                                <td width="28%" ><?php echo JHTML::tooltip('Select the default commenting system', 'Commenting System','', 'Commenting System');?></td>
                                <td><select name="commentingsystem">
                                        <option <?php if (($commenting) == "None") {
        echo 'selected';
    } ?>>None</option>
                                        <option <?php if (($commenting) == "facebook") {
        echo 'selected';
    } ?>>facebook</option>

                                    </select></td>
                                    <td></td>
                            </tr>


                            <tr>
                        </table>
                    </div>
                    <div id="tabs-3">

                        <table class="adminlist">
                            <thead style="border-spacing: 0; border-spacing: 0;background: #DFDFDF;color: black;">
                                <tr>
                                    <td colspan="2">Details</td>
                                    <td>Email Template</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td ><?php echo JHTML::tooltip('Enter from email ', 'From Email','', 'From Email');?>  </td>
                                    <td><input id="fromemail" name="fromemail" type="text" value="<?php if(isset($emailsettings->fromemail)){echo $emailsettings->fromemail;} ?>"></td>
                                    <td><a id="registration"  onclick="linkname(this.id)" class="modal" rel="{handler: 'iframe'}"
                                           href="<?php echo JURI::base(); ?>index.php?option=com_appthaeventz&view=email&tmpl=component&type=registration">
                                           <?php echo JHTML::tooltip('Enter template format for registration email', 'Registration Email','', 'Registration Email');?></a></td>
                                </tr>
                                <tr>
                                    <td ><?php echo JHTML::tooltip('Enter from name ', 'From Name','', 'From Name');?> </td>
                                    <td><input id="fromname" name="fromname" type="text" value="<?php if(isset($emailsettings->fromname)){ echo $emailsettings->fromname; }?>"></td>
                                    <td><a id="activation"  onclick="linkname(this.id)" class="modal" rel="{handler: 'iframe'}"
                                           href="<?php echo JURI::base(); ?>index.php?option=com_appthaeventz&view=email&tmpl=component&type=activation">
                                            <?php echo JHTML::tooltip('Enter template format for activation email', 'Activation Email','', 'Activation Email');?></a></td>
                                </tr>
                                <tr>
                                    <td ><?php echo JHTML::tooltip('Enter mail address for cc ', 'CC','', 'CC');?>  </td>
                                    <td><input id="cc" name="cc" type="text" value="<?php if(isset($emailsettings->cc)){echo $emailsettings->cc;} ?>"></td>
                                    <td><a id="invite"  onclick="linkname(this.id)" class="modal"
                                           rel="{handler: 'iframe'}" href="<?php echo JURI::base(); ?>index.php?option=com_appthaeventz&view=email&tmpl=component&type=invite">
                                            <?php echo JHTML::tooltip('Enter template format for invite email', 'Invite Email','', 'Invite Email');?></a></td>
                                </tr>
                                <tr>
                                    <td><?php echo JHTML::tooltip('Enter mail address for bcc ', 'BCC','', 'BCC');?></td>
                                    <td><input id="bcc" name="bcc" type="text" value="<?php if(isset($emailsettings->bcc)){echo $emailsettings->bcc;} ?>"></td>
                                     <td height="25px"><a id="denied" onclick="linkname(this.id)" class="modal"
                                                         rel="{handler: 'iframe'}" href="<?php echo JURI::base(); ?>index.php?option=com_appthaeventz&view=email&tmpl=component&type=denied">
                                            <?php echo JHTML::tooltip('Enter template format for denied email', 'Denied subscription Email','', 'Denied subscription Email');?></a></td>

                                </tr>
                                 <tr>
                                    <td colspan="2"></td>
                                     <td height="25px"><a id="approved" onclick="linkname(this.id)" class="modal" rel="{handler: 'iframe'}"
                                                         href="<?php echo JURI::base(); ?>index.php?option=com_appthaeventz&view=email&tmpl=component&type=approved">
                                            <?php echo JHTML::tooltip('Enter template format for approved email', 'Approved Email','', 'Approved Email');?></a></td>

                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                     <td height="25px"><a id="subscription"  onclick="linkname(this.id)" class="modal" rel="{handler: 'iframe'}" href="<?php echo JURI::base(); ?>index.php?option=com_appthaeventz&view=email&tmpl=component&type=subscription">
                                            <?php echo JHTML::tooltip('Enter template format for Subscription notification email', 'Subscription notification Email','', 'Subscription notification Email');?></a></td>

                                </tr>

                            </tbody>
                        </table>

                    </div>
                    <div id="tabs-4">


                        <table class="adminlist">

                            <tr>
                                <td> <?php echo JHTML::tooltip('Select the default payment method.<br/>To select more than one payment method press ctrl and select', 'Default Payment Method','', 'Default Payment Method');?></td>
                                <td><select multiple="multiple" name="paymentmethod[]" style="width:150px;">
                                        <option <?php if(isset($paymentsettings)){if (in_array("1",$paymentsettings)) {
        echo 'selected';
    } }?> value="1">Wire Transfer</option>
                                        <option <?php if(isset($paymentsettings)){if (in_array("2",$paymentsettings)) {
        echo 'selected';
    }} ?> value="2">Paypal</option>
                                        <option <?php if(isset($paymentsettings)){ if (in_array("3",$paymentsettings)) {
        echo 'selected';
    }} ?> value="3">Authorized .Net</option>
                                    </select></td>
                            </tr>

                            <tr>
                                <td ><?php echo JHTML::tooltip('Set the currency name', 'Currency','', 'Currency');?>&nbsp;<span style="color:red">*</span></td>
                                <td><input id="currency" name="currency" type="text" value="<?php if(isset($getcurrencydetails->currency)){ echo $getcurrencydetails->currency; }?>"></td>
                            </tr>
                            <tr>
                                <td ><?php echo JHTML::tooltip('Set the currency symbol', 'Currency Symbol ','', 'Currency Symbol ');?> <span style="color:red">*</span></td>
                                <td><input id="currencysign" name="currencysign" type="text" value="<?php if(isset($getcurrencydetails->currency_sign)){ echo $getcurrencydetails->currency_sign; }?>"></td>
                            </tr>
                        </table>




                    </div>
                </div>
                <input type="hidden" id="tabvalue" name="tab" value="tabs-1" />
                <input type="hidden" id="tab" name="tab" value="general" />
                <input type="hidden" name="boxchecked" value="0" />
                <input type="hidden" id="linkname" name="linkname" value="registration" />
                <input type="hidden" name="task" value="" />


            </form>

        </div><!-- End demo -->



    </body>
</html>
<?php } ?>

<script language="JavaScript" type="text/javascript">
    //validations for the fields inside each tab.
<?php if (version_compare(JVERSION, '1.6.0', 'ge')) { ?>
                      Joomla.submitbutton = function(pressbutton) {
<?php } else { ?>
                    function submitbutton(pressbutton) {
<?php } ?>
                    var url = document.URL;
                    var url3 =url.split("#");
                    var cid = url3['1'];


                    if((cid == "tabs-4" ))
                    {

                        document.getElementById("tab").value = "payments";

                    }
                    else if(cid == "tabs-3" )
                    {
                        document.getElementById("tab").value = "emails";
                    }
                    else if(cid == "tabs-2" )
                    {
                        document.getElementById("tab").value = "events";
                    }
                    else if(cid == "tabs-1" )
                    {
                        document.getElementById("tab").value = "general";
                    }

                    var tab = document.getElementById("tab").value;

                    if (((pressbutton == "apply")||(pressbutton == "save"))&&(tab == "general"))
                    {
                        var letters = /^[0-9a-zA-Z]+$/;
                        var license = document.getElementById("license").value;
                        if(trim(license) == '')
                        {
                            alert("License Code should not be empty");
                            return;
                        }


                        submitform( pressbutton );
                        return;
                    }
                    if (((pressbutton == "apply")||(pressbutton == "save"))&&(tab == "events"))
                    {

                        var googleapi = document.getElementById("googleapi").value;
                        var fbappid = document.getElementById("fbappid").value;
                        var fbsecretkey = document.getElementById("fbsecretkey").value;



                         var letters = /^[a-zA-Z0-9]+$/;

                         if((trim(fbappid) != '')&&(!trim(fbappid).match(letters)))
                        {
                            alert("Invalid FB App ID");
                            return;
                        }
                       if((trim(fbsecretkey) != '')&&(!trim(fbsecretkey).match(letters)))
                        {
                            alert("Invalid FB Secret Key");
                            return;
                        }
                          if(trim(googleapi) == '')
                        {
                            alert("Please enter Google api to access Google maps");
                            return;
                        }

                        submitform( pressbutton );
                        return;
                    }
                    if (((pressbutton == "apply")||(pressbutton == "save"))&&(tab == "emails"))
                    {
                        var fromemail = document.getElementById("fromemail").value;
                        var cc = document.getElementById("cc").value;
                        var bcc = document.getElementById("bcc").value;


                        var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;

                         if((trim(document.getElementById('fromemail').value) != "")&&(!pattern.test(document.getElementById('fromemail').value))){
                            alert( "<?php echo JText::_('You must provide a valid From Email', true); ?>" )
                            return;
                        }



                        if((trim(document.getElementById('cc').value) != "")&&(!pattern.test(document.getElementById('cc').value))){
                            alert( "<?php echo JText::_('You must provide a valid CC Email', true); ?>" )
                            return;
                        }
                         var letters = /^[0-9a-zA-Z]+$/;
                         var fromname = document.getElementById("fromname").value;
                         if((trim(fromname)!= '')&&(!trim(fromname).match(letters)))
                        {
                            alert("Invalid From Name");
                            return;
                        }

                        if (trim(document.getElementById('bcc').value) != "")
                        {

                            if(!pattern.test(document.getElementById('bcc').value)){
                            alert( "<?php echo JText::_('You must provide a valid BCC Email', true); ?>" )
                            return;
                        }}

                        submitform( pressbutton );
                        return;
                    }
                    if (((pressbutton == "apply")||(pressbutton == "save"))&&(tab == "payments"))
                    {
                        var currency = document.getElementById("currency").value;
                        var currencysign = document.getElementById("currencysign").value;

                        var letters = /^[a-zA-Z]+$/;


                        if(trim(currency) == '')
                        {
                            alert("currency should not be empty");
                            return;
                        } else if(!currency.match(letters))
                        {
                            alert("Invalid currency");
                            return;
                        }
                        if(trim(currencysign) == '')
                        {
                            alert("Currency Sign should not be empty");
                            return;
                        }

                        submitform( pressbutton );
                        return;
                    }

                    submitform( pressbutton );
                    return;
                }
</script>
 <script>
                $(function() {
                    $( "#tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
                    $( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
                });
</script>
<script type="text/javascript">
        function change_datetime(theid,thevalue)
        {
            document.getElementById(theid).value = thevalue;
        }
        function storetask(value,tabvalue)
        {


            if(value == "general")
            {
                window.history.pushState("string", "Title","index.php?option=com_appthaeventz&view=settings#tabs-1");
            }
            else if(value == "events")
            {
                window.history.pushState("string", "Title","index.php?option=com_appthaeventz&view=settings#tabs-2");
            }
            else  if(value == "emails")
            {
                window.history.pushState("string", "Title","index.php?option=com_appthaeventz&view=settings#tabs-3");
            }
            else   if(value == "payments")
            {
                window.history.pushState("string", "Title","index.php?option=com_appthaeventz&view=settings#tabs-4");
            }

            document.getElementById('tab').value = value;
        }
        function linkname(id)
        {


            document.getElementById('linkname').value = id;
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