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
$document->addStyleSheet('' . JURI::base() . 'components/com_appthaeventz/assets/css/style.css');
?>

<?php
if ( JRequest::getVar('task') == 'add') {
 ?>

    <form action='index.php?option=com_appthaeventz&view=import' method="POST" name="adminForm" id="adminForm" enctype="multipart/form-data" >
        <fieldset class="adminform">
            <legend>Import CSV </legend>
            <table width="100%">
                <tr><td valign="top"><table class="admintable">

            <tr>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td class="key" style="width:40%">Import from CSV <span style="color:red;">*</span></td>
                <td><input type="file" name="csvimport" id="csvimport" accept="text/csv" size="32" maxlength="250" value="" /></td>
            </tr>

            <tr>
                <td class="key">Event category <span style="color:red;">*</span></td>
                <td>
                    <select name="category_id" id="category_id">
                        <option value="">Select Category</option>
                        <?php foreach($this->categoriesList as $val): ?>
                            <option id="<?php echo $val->value; ?>" value="<?php echo $val->value; ?>" >
                                <?php echo $val->text; ?>
			    </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="key">Event location</td>
                <td>
                    <?php echo $this->locationsList; ?>
                </td>
            </tr>
            <tr>
                <td class="key"></td>
                <td>
                    <button onclick="setTask('csvimport');" type="button" class="import_btn"><img src="components/com_appthaeventz/assets/images/import-icon.png" style=""  class="resize"/><span style="display: block;float: left;font-size: 12px;padding-top: 0px;">Import</span></button>
                </td>
            </tr>

            </table></td> 
            <td valign="top">
                <table align="right">
                    <tr>    <td><img src="components/com_appthaeventz/assets/images/import-csv.png"/></td>
                        <td colspan="2" align="center" valign="top"> <span class="right_scv">Please <a href="<?php echo JURI::base().'components/com_appthaeventz/views/import/tmpl/sample.csv'; ?>" target="_blank">Click here</a> for sample csv file</span>
                </td>
            </tr>
            </table></td></tr>
              
            </table>
            
            
                </fieldset>

         <fieldset class="adminform">
            <legend>Google Calendar</legend>
            <table class="admintable">
            <tr>
                <td class="key" style="width:44%">Google username <span style="color:red;">*</span></td>
                <td><input type="text" name="g_username" id="g_username" size="32" maxlength="250" value="<?php echo (isset($this->googDetails->google_username))?$this->googDetails->google_username:''; ?>"  /></td>
            </tr>
            <tr>
                <td class="key">Google password <span style="color:red;">*</span></td>
                <td><input type="password" name="g_password" id="g_password" size="32" maxlength="250" value="<?php echo (isset($this->googDetails->google_password))?$this->googDetails->google_password:''; ?>" /></td>
            </tr>
           

            <tr>
                <td class="key">Event category <span style="color:red;">*</span></td>
                <td>
                    <select name="g_category_id" id="g_category_id">
                        <option value="">Select Category</option>
                        <?php foreach($this->categoriesList as $val):
                                $selected = ($this->googDetails->google_category == $val->value)?'selected':'';
                        ?>
                            <option id="<?php echo $val->value; ?>" value="<?php echo $val->value; ?>" <?php echo $selected;  ?>>
                                <?php echo $val->text; ?>
			    </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="key">Event location</td>
                <td>
                    <?php echo $this->glocationsList; ?>
                </td>
            </tr>
            <tr>
                <td class="key"></td>
                <td>
                    <button onclick="gcal('googlecal');" type="button" class="import_btn"><img src="components/com_appthaeventz/assets/images/sync.png" class="resize"/><span style="display: block;float: left;font-size: 12px;padding-top: 0px;">Synchronize</span></button>
                </td>
            </tr>

            </table>
                </fieldset>

        <fieldset class="adminform">
            <legend>Facebook Events</legend>
            <table class="admintable">
            <tr>
                    <td colspan="2">
                            <?php
                            $app_id = $this->fbdetails->fb_appid;
                            $redirect = JURI::base().'index.php?option=com_appthaeventz&view=import&task=fbredirect';
                            $dialog_url = "http://www.facebook.com/dialog/oauth?client_id=".$app_id . "&redirect_uri=".urlencode($redirect)."&scope=email,read_stream,publish_stream";
                            ?>
                            <a href="" onclick="return fblogin(); "><img src="components/com_appthaeventz/assets/images/fbconnect.png" style="" /></a>
                    </td>
            </tr>


            <tr>
                <td class="key" style="width:44%">Event category <span style="color:red;">*</span></td>
                <td>
                    <select name="fb_category_id" id="fb_category_id">
                        <option value="">Select Category</option>
                        <?php foreach($this->categoriesList as $val):
                                $selected = ($this->fbdetails->fb_category == $val->value)?'selected':'';
                        ?>
                            <option id="<?php echo $val->value; ?>" value="<?php echo $val->value; ?>" <?php echo $selected;  ?>>
                                <?php echo $val->text; ?>
			    </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="key">Event location</td>
                <td>
                    <?php echo $this->fblocationsList; ?>
                </td>
            </tr>
            <tr>
                <td class="key"></td>
                <td>
                    <button onclick="fbValid('fbevents');" type="button" class="import_btn"><img src="components/com_appthaeventz/assets/images/sync.png" style="" class="resize"/><span style="display: block;float: left;font-size: 12px;padding-top: 0px;">Synchronize</span></button>
                </td>
            </tr>

            </table>
                </fieldset>


            <input type="hidden" name="option" value="<?php echo JRequest::getVar('option');?>"/>
            <input type="hidden" name="id" value=""/>
            <input type="hidden" name="created_date" id="created_date" value="<?php date_default_timezone_set('Asia/Calcutta'); echo date("Y-m-d H:i:s");?>"/>
            <input type="hidden" name="task" id="task" value="<?php echo JRequest::getVar('task');?>"/>
            </form>
<?php
                    }
                   
?>
   <div id="fb-root"></div>

<script language="JavaScript" type="text/javascript">
    window.fbAsyncInit = function() {
                FB.init({appId: '<?php echo $app_id; ?>', status: true, cookie: true, xfbml: true});
 
            };
            (function() {
                var e = document.createElement('script');
                e.type = 'text/javascript';
                e.src = document.location.protocol +
                    '//connect.facebook.net/en_US/all.js';
                e.async = true;
                document.getElementById('fb-root').appendChild(e);
            }());
            function fblogin(){
        FB.login(function(response) {
           
        
        FB.getLoginStatus(function(response) {
           
         if (response.status === 'connected') {
           
           login();
           } 
         });
        }, {scope:'email'});
        return false;
     }
     function login(){
                FB.api('/me', function(response) {
                 alert("Successfully Connected to Facebook")  ;
                 
                });
            }
   <?php if(version_compare(JVERSION,'1.6.0','ge'))
                    { ?>Joomla.submitbutton = function(pressbutton) {<?php } else { ?>
                        function submitbutton(pressbutton) {<?php } ?>
        if ( pressbutton == "apply")
        {
            var username = document.getElementById('g_username').value;
            var password = document.getElementById('g_password').value;
            var category = document.getElementById('g_category_id').value;

            if(trim(username) == ''){
                alert("Please enter the Google username");
                return false;
            }
            if(trim(password) == ''){
                alert("Please enter the Google password");
                return false;
            }

            <?php if( empty($this->categoriesList) ){ ?>
                alert("Please enter categories to proceed");
                return false;
            <?php }
            if( !empty($this->categoriesList) ){ ?>
                if(category == ''){
                alert("Please select Event category");
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

    function validimport(){
        document.adminForm.action = "index.php?option=com_appthaeventz&view=import&task=csvimport";
        document.adminForm.submit();
    }

    function setTask(task)
    {
        var category = document.getElementById('category_id').value;
        var ext = document.getElementById('csvimport').value;
        var extension = getFileExtension(ext);
        

        <?php
            if( empty($this->categoriesList) ){ ?>
                alert("Please enter categories to proceed import");
                return false;
        <?php }else{ ?>
            if(ext == '' ){
                alert("Please upload csv file");
                return false;
            }
            if(ext != '' && extension != 'csv'){
                alert("Please upload valid file");
                return false;
            }

         <?php }   if( !empty($this->categoriesList) ){ ?>
                if(category == ''){
                alert("Please select Event category");
                return false;
                }
        <?php } ?>
            document.getElementById('task').value = task;
            document.adminForm.submit();
       
    }

    function getFileExtension(filename) {
        var ext = /^.+\.([^.]+)$/.exec(filename);
        return ext == null ? "" : ext[1];
    }

    function gcal(task){
        var username = document.getElementById('g_username').value;
            var password = document.getElementById('g_password').value;
            var category = document.getElementById('g_category_id').value;

            if(trim(username) == ''){
                alert("Please enter the Google username");
                return false;
            }
            if(trim(password) == ''){
                alert("Please enter the Google password");
                return false;
            }

            <?php if( empty($this->categoriesList) ){ ?>
                alert("Please enter categories to proceed import");
                return false;
            <?php }
            if( !empty($this->categoriesList) ){ ?>
                if(category == ''){
                alert("Please select Event category");
                return false;
                }
            <?php } ?>
            

            document.getElementById('task').value = task;
            document.adminForm.submit();
    }

    function fbValid(task){
            var category = document.getElementById('fb_category_id').value;

             <?php if( empty($this->categoriesList) ){ ?>
                alert("Please enter categories to proceed import");
                return false;
            <?php }
            if( !empty($this->categoriesList) ){ ?>
                if(category == ''){
                alert("Please select Event category");
                return false;
                }
            <?php } ?>

            document.getElementById('task').value = task;
            document.adminForm.submit();
    }
    
</script>