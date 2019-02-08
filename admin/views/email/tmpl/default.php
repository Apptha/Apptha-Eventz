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
JHTML::_('behavior.modal');

?>
<style type="text/css">
#cancel{position:absolute;left:200px;}
#savebutton{position:absolute;left:130px;}
.toggle-editor{display:none;}
html{overflow:hidden;}

</style>
<?php
$type = JRequest::getVar('type');

$popupsettings = $this->getemailpopupsettings;


if($popupsettings)  {

if (array_key_exists($type, $popupsettings)) {
     
    //if type exitsts get subject and content for the particular type and load fields with that data.
    $popupsettings = $popupsettings[$type];  
  
if($popupsettings){
    $popupsettings = json_decode($popupsettings,true);
     $subject = $popupsettings['0'];
      $popupsettings['1'] = str_replace('doublequotes','"',$popupsettings['1']);
     $content = $popupsettings['1'];
     

}
else{
  $subject = "";
  $content = "";
}
}
}
else{
  $subject = "";
  $content = "";
}
?>
<legend><?php if($type == "remainder"){echo "Reminder";}else{ echo ucfirst($type);}  ?> Email Template Settings</legend><br/>
    <form action="index.php?option=com_appthaeventz&view=settings" method="post" name="adminForm" id="adminForm">

        <table>
            <tr>
                <td width="20%">Subject</td>
                <td><input style="width:300px;" id="subject" name="subject" type="text" value="<?php if(isset($subject)){echo $subject; }?>"></td>
            </tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr>
                
                <td  colspan="2"><?php
$editor = JFactory::getEditor();
echo $editor->display('content', $content, '550', '280', '60', '20', false);
?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><button id="savebutton" type="button" onclick="saveit()">Save</button></td>
                <td><button id="cancel" type="button" onclick="window.parent.SqueezeBox.close();">Cancel</button></td>
            </tr>
        </table>
        <input type="hidden" id="task" name="task" value="">
        <input type="hidden" id="type" name="type" value="<?php echo $type; ?>">
    </form>

<script type="text/javascript">
    function saveit()
    {
        document.getElementById('task').value = 'saveit';
        var subject = document.getElementById('subject').value;
        if(trim(subject) == '')
        {
            alert("Subject should not be empty");
            return;
        }
        //submit the form.
        document.getElementById("adminForm").submit();
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