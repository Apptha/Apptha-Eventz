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

//Tool bar
           JToolBarHelper::title('Apptha Eventz: Add Events','events.png');
           JToolBarHelper::apply();
           JToolBarHelper::save();
           JToolBarHelper::cancel();
$document = JFactory::getDocument();

$document->addScript('components/com_appthaeventz/assets/js/1.8.0.js');
$document->addStyleSheet('components/com_appthaeventz/assets/css/jquery-ui-1.8.23.custom.css');
$document->addScript('components/com_appthaeventz/assets/js/jquery-ui-1.8.23.custom.js');
$document->addScript('components/com_appthaeventz/assets/js/jquery.tagsinput.js');
$document->addStyleSheet('components/com_appthaeventz/assets/css/events.css');

// Assign data
$onedata=$this->onedata;


$eventticket = $this->eventticket;

//event id
$cid = JRequest::getInt('cid','','get');
//tab id
$tabs = JRequest::getInt('tabs','','get');

?>

<script type="text/javascript">

 
	$(function() {
   <?php if(empty($cid)): ?>
                $( "#tabs" ).tabs({ disabled: [2,3,4,5,6,7,8,9,10] }).addClass( "ui-tabs-vertical ui-helper-clearfix" );
 <?php else: ?>
 		$( "#tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
		$( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
   <?php endif; ?>
	});
       
         //add row
        var i = 1 ;

        function add_row()
        {

            $('#more_table').append('<tr id="remove_'+i+'"><td style="padding-left:10px;"><input class="tickets_1" id="tickets_id_'+i+'" type="text" name="ticket_name[]" /></td><td style="padding-left:10px;"><input class="tickets_2" id="tickets_price_id_'+i+'" type="text" name="ticket_price[]" /></td><td style="padding-left:10px;"><input class="tickets_3" id="tickets_seats_id_'+i+'" type="text" name="ticket_seats[]" /></td><td style="padding-left:10px;"><textarea  id="tickets_description_id_'+i+'" name="ticket_description[]"></textarea></td>  <td style="padding-left:10px;"> <a href="javascript:void(0);" onclick="remove_file('+i+')" > <img src="components/com_appthaeventz/assets/images/delete.png" alt="delete" /> </a> </td></tr>');
          i++;
        }


        
        function add_tab(tabing)
        {
            var tabing;
            document.getElementById('tabing').value=tabing;
        }
        $(document).ready(function () {

$("#tabs-6").mouseup(function() {

        var start=$('#start').val();
        var end_repeat=$('#end_repeat').val();
        var repeat_interval=$('#repeat').val();
       $r=1;
       if(start=='')
         {
             $('.result').html('Please Select Start Date');
            $r=0;
            return false;

         }
        if(end_repeat=='')
         {
             $('.result').html('Please Select End Repeat');
            $r=0;
            return false;
         }
         if(repeat_interval=='')
         {
             $('.result').html('Please Select Interval');
             $r=0;
             return false;
         }

         var repeat_type = $("#repeat_type").val();
            $.post('index.php?option=com_appthaeventz&view=addevent&format=ajax&task=recurring&start='+start+'&end_repeat='+end_repeat+'&repeat_type='+repeat_type+'&repeat_interval='+repeat_interval, function(data) {
            $('.result').html(data);
             });
        });

        

        });


//remove file

   function remove_file(id)
           {
//alert(id);
         $('#remove_'+id).remove();
         }


 </script>
      <form  id="adminForm" name="adminForm" method="post">

          <div id="tabs">
              <div class="floatleft">
              <div class="add_photo">
                 
                
                <?php if(isset($onedata->image_name) && ($onedata->image_name != "")): ?>
                <img width="100" height="100" src="<?php echo '../images/appthaeventz/thumb_'.$onedata->image_name; ?>" alt="photo" />
                <input type="hidden" name="img_name" id="img_name" value="<?php echo $onedata->image_name; ?>" />
                <?php else: ?>
                 <img width="100" height="100" src="<?php echo JURI::base();?>components/com_appthaeventz/assets/images/event_default.png" alt="photo" />
                 <input type="hidden" name="img_name"  id="img_name" value="<?php echo isset($onedata->image_name)?$onedata->image_name:''; ?>" />
                 <?php endif; ?>
                 <br>
                  <a rel="{handler: 'iframe', size: {x: 400, y: 100}}" href="index.php?option=com_appthaeventz&view=addevent&layout=upload&tmpl=component&cid=<?php echo $cid; ?>" class="modal"> <?php if(isset($onedata->image_name)){echo 'Edit Photo';}else{echo 'Add Photo';} ?> </a>
              </div>

	     <ul id="general_ul_id">
                <li <?php echo ($tabs==1)? 'class="ui-tabs-selected "' : ''; ?> ><a href="#tabs-1" onclick="add_tab(1);">Event Details</a></li>
		<li  <?php echo ($tabs==2)? 'class="ui-tabs-selected "' : ''; ?> ><a href="#tabs-2" onclick="add_tab(2);">Category and Tags</a></li>
                <li  <?php echo ($tabs==3)? 'class="ui-tabs-selected "' : ''; ?>><a href="#tabs-3" onclick="add_tab(3);">Event Control</a></li>
		<li  <?php echo ($tabs==4)? 'class="ui-tabs-selected "' : ''; ?>><a href="#tabs-4" onclick="add_tab(4);">Tickets Layout</a></li>
                <li  <?php echo ($tabs==5)? 'class="ui-tabs-selected "' : ''; ?>><a href="#tabs-5" onclick="add_tab(5);">Tickets</a></li>
                <li  <?php echo ($tabs==6)? 'class="ui-tabs-selected "' : ''; ?>><a href="#tabs-6" onclick="add_tab(6);">Recurring</a></li>
                <li  <?php echo ($tabs==7)? 'class="ui-tabs-selected "' : ''; ?>><a href="#tabs-7" onclick="add_tab(7);">Images</a></li>
                <li  <?php echo ($tabs==8)? 'class="ui-tabs-selected "' : ''; ?>><a href="#tabs-8" onclick="add_tab(8);">Contact Details</a></li>
                <li  <?php echo ($tabs==9)? 'class="ui-tabs-selected "' : ''; ?>><a href="#tabs-9" onclick="add_tab(9);">Meta Info</a></li>
                <li  <?php echo ($tabs==10)? 'class="ui-tabs-selected "' : ''; ?>><a href="#tabs-10" onclick="add_tab(10);">Front End</a></li>
                <li  <?php echo ($tabs==11)? 'class="ui-tabs-selected "' : ''; ?> ><a href="#tabs-11" onclick="add_tab(11);">Others</a></li>


	</ul>
                  </div>
             
        <!--Tabs 1-->
	<div id="tabs-1">
            <fieldset>
                <legend>Event Details</legend>
                <table>
                <tr>
                <td width="100"> <label for="name">Event name <span style="color:red">*</span> </label></td>
               <td> <input id="name"  type="text" name="name" value="<?php if(isset($onedata->event_name)){ echo $onedata->event_name;}  ?>" > </td>
                </tr>
              
                </table>
                
                <table>
                <tr>
                 <td width="100">  <label for="start">Start date <span style="color:red">*</span> </label> </td>
                 <td>  
                <?php

                $start = (  (isset($onedata->start_date)) &&  ($onedata->start_date != '0000-00-00 00:00:00') )? $onedata->start_date:'';

                echo JHTML::_('calendar',$start, 'start', 'start', $format = '%Y-%m-%d %H:%M:%I', array( 'class'=>'inputbox','size'=>'25',  'maxlength'=>'19')); ?> </td>
                <td><label for="end">End date <span style="color:red">*</span></label></td>
                <td> <?php 
                $end=(isset($onedata->end_date)&&($onedata->end_date != '0000-00-00 00:00:00'))? $onedata->end_date:'' ;
                echo JHTML::_('calendar',$end, 'end', 'end', $format = '%Y-%m-%d %H:%M:%I', array( 'class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?> </td>
                </tr>
                </table>
                <table>
                <tr>
                <td width="100"><label for="location">Location <span style="color:red">*</span></label></td>
                <td>
                <input id="location"  type="text" readonly  name="location" value="<?php if(isset($onedata->location_name)){echo $onedata->location_name;} ?>" >
                <input  type="hidden" value="<?php if(isset($onedata->address)) echo $onedata->address; ?>"  name="address" id="address" />
                <input  type="hidden" value="<?php if(isset($onedata->latitude)) echo $onedata->latitude; ?>"  name="latitude" id="latitude" />
                <input  type="hidden" value="<?php if(isset($onedata->longitude)) echo $onedata->longitude; ?>"  name="longitude" id="longitude" />
                
                <a rel="{handler: 'iframe', size: {x: 750, y: 450}}" href="index.php?option=com_appthaeventz&view=addevent&layout=location&tmpl=component&function=ad&cid=<?php echo $cid; ?>" class="modal"><img src="components/com_appthaeventz/assets/images/mapping.png" width="24" height="24" alt="map" /></a>
                </td>
                </tr>
                <tr>
                <td width="100"><label for="description">Description</label></td>
                <td>
                <?php
                $editor = JFactory::getEditor();
                $description = (isset($onedata->description))?$onedata->description:"";
                echo $editor->display('description',$description, '700', '300', '60', '20', false );  //to display editor
                ?>
                </td></tr>
               </table>
            </fieldset>
        </div>
        <!--Tabs 2-->
	<div id="tabs-2">
	
        <fieldset>
                <legend>Categories</legend>
                <p>
                    <label for="category">Category <span style="color:red">*</span>  &nbsp;&nbsp;</label>&nbsp;&nbsp;
                <select style="width:249px;" name="category[]" size="10" multiple="multiple" id="category">
                <?php
                if(!empty ($onedata))
                {
                if($onedata->category_id): $one_catgeory=explode(",",$onedata->category_id);  endif;
                }
                foreach($this->category as $val): ?>
                
                        <option <?php if(!empty($one_catgeory) )if(in_array($val->value,$one_catgeory)): echo 'selected="selected"'; endif;?>  value="<?php echo $val->value; ?>" > <?php echo $val->text; ?> </option>
                 <?php endforeach;
                ?>
                </select>
              <a rel="{handler: 'iframe', size: {x: 650, y: 320}}" href="index.php?option=com_appthaeventz&view=addevent&layout=category&tmpl=component&cid=<?php echo $cid; ?>" class="modal">Add Category </a>
                </p>
         </fieldset>      
           <link rel="stylesheet" type="text/css" href="http://xoxco.com/projects/code/tagsinput/jquery.tagsinput.css" />
<!--      <script type="text/javascript" src="http://xoxco.com/projects/code/tagsinput/jquery.tagsinput.js"></script>-->
      <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js'></script>
	
      <script type="text/javascript">

		$(function() {

			$('#tags_3').tagsInput({
				width: 'auto',
                                autocomplete_url:'index.php?option=com_appthaeventz&view=addevent&format=ajax&task=gettag'
				
			});

		});

	</script>
            <fieldset>
                <legend>Tags</legend>
                <p>
                <label>Tags</label>
                
 <input id='tags_3' type='text' name="tags" class='tags' value="<?php if(!empty($this->alltags)){ foreach($this->alltags as $v): echo $v['tag_name'].","; endforeach;  } ?>" />
                <br>
                (Add tags separated by comma)
                </p>
               </fieldset>
	</div>
        <!--Tabs 3-->
	<div id="tabs-3">
            <fieldset>
                <legend>Event Control</legend>
                <table>
                 <tr>
                  <td  width="250">
                 <label for="from">Ticket booking Start date </label></td>
                  <td>
                 <?php 
                  $from=($onedata->from_date == '0000-00-00 00:00:00')? '' :$onedata->from_date ;
                 echo JHTML::_('calendar',$from, 'from', 'from', $format = '%Y-%m-%d %H:%M:%I', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?>
                  </td>
                  </tr>
                  <tr>
                      <td width="250"><label for="to">Ticket booking End date </label></td>
                <td>
                 <?php
                  $to=($onedata->to_date == '0000-00-00 00:00:00')? '' :$onedata->to_date ;
                echo JHTML::_('calendar',$to, 'to', 'to', $format = '%Y-%m-%d %H:%M:%I', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?>
                </td></tr>
                </table>
                <table>
                    <tr>
                        <td width="250"><label for="overbooking">Allow Overbooking Tickets </label></td>
                        <td><input id="overbooking"  type="checkbox"  name="overbooking" <?php echo ($onedata->allow_overbooking == 1)? 'checked="checked"' : ''; ?> value="1" >
                        </td>
                    </tr>

                    <tr>
                        <td> <label for="overamount">Overbooking Ticket count </label> </td>
                        <td> <input id="overamount"  type="text"  name="overamount" value="<?php echo $onedata->overbooking_amount; ?>" > </td>
                    </tr>
                
                    <tr>
                     <td> <label for="notify_me">Notify the owner on new subscriptions  </label> </td>
                     <td> <input id="notify_me"  type="checkbox"  name="notify_me" value="1" <?php echo ($onedata->notify_owner == 1)? 'checked="checked"' : ''; ?> > </td>
                    </tr>
                    <tr>
                     <td> <label for="show_guest">Show registered guests on the event page </label> </td>
                     <td> <input id="show_guest"  type="checkbox"  name="show_guest" value="1" <?php echo ($onedata->show_guest == 1)? 'checked="checked"' : ''; ?> ></td>
                    </tr>
                    <tr>
                        <td><label for="auto_approve">Automatically approve free registrations </label></td>
                        <td><input id="auto_approve"  type="checkbox"  name="auto_approve" value="1" <?php echo ($onedata->auto_approve == 1)? 'checked="checked"' : ''; ?> ></td>
                     </tr>
                </table>
            </fieldset>

        </div>
        <!--Tabs 4-->
        <div id="tabs-4">

           <fieldset>
                <legend> Ticket Layout </legend>
                <table>
                    <tr>
                <td>
                            <label for="ticket_layout">Ticket Layout </label>
                </td>
                    <td>
                 <?php
                $editor = JFactory::getEditor();
                echo $editor->display('ticket_layout',$onedata->ticketlayout, '700', '300', '60', '20', false );  //to display editor
                ?>
                </td>
                </tr>
                    <tr>
                        <td width="200">
                            <label for="activate_email">Attach to the activation e-mail  </label>
                        </td>
                        <td>
                           <input id="activate_email"  type="checkbox"  name="activate_email" value="1" <?php echo ($onedata->attach_email == 1)? 'checked="checked"' : ''; ?> >
                        </td>
                    </tr>
                </table>
            </fieldset>

        </div>
        <!--Tabs 5-->
          <div id="tabs-5">

            <fieldset>
                <legend> Tickets </legend>
                <div style="float: right; width: 400px;"><a onclick="add_row();" href="javascript:void(0);"> <img src="components/com_appthaeventz/assets/images/plus.png" alt="plus" /> </a></div>
                <span class="clear"></span>
                <table border="1" width="600" id="more_table">

                    <tr>
                        <th> Ticket Name </th>
                        <th> Price </th>
                        <th> No of Seats </th>
                        <th> Description </th>
                        <th width="50"> Action </th>

                    </tr>
                    <?php if($eventticket):
                          foreach ($eventticket as $key => $ticket): ?>
                    <input type="hidden" name="ticket_id[]" value="<?php echo $ticket->id; ?>">
                    <tr id="remove_<?php echo $key+1000; ?>" align="center">
                        <td style="padding-left:10px;"> <input id="tickets_id_<?php echo $key+1000; ?>" class="tickets_1" type="text" name="ticket_name[]" value="<?php echo $ticket->ticket_name ; ?>" /></td>
                        <td style="padding-left:10px;"> <input id="tickets_price_id_<?php echo $key+1000; ?>" class="tickets_2"type="text" name="ticket_price[]" value="<?php echo $ticket->price ; ?>" /> </td>
                        <td style="padding-left:10px;"> <input id="tickets_seats_id_<?php echo $key+1000; ?>" class="tickets_3" type="text" name="ticket_seats[]" value="<?php echo $ticket->seats ; ?>" /> </td>
                        <td style="padding-left:10px;"> <textarea name="ticket_description[]"><?php echo $ticket->description ; ?></textarea> </td>
                        <td style="padding-left:10px;"> <a style="text-align:center;"  href="javascript:void(0);" onclick="remove_file(<?php echo $key+1000; ?>)" ><img src="components/com_appthaeventz/assets/images/delete.png" alt="delete" /></a> </td>
                    </tr>

                           <?php endforeach;

                          endif;
                     ?>

                </table>

            </fieldset>

        </div>

        <!--Tabs 6-->
         <div id="tabs-6">

                        <fieldset>
                <legend> Recurring </legend>
                <p> <h3 class="result"> This event is repeating <?php echo $onedata->repeat_times; ?> times </h3></p>
                <table>
                    <tr>
                <td width="200"><label for="repeat">Repeat Every </label>
                </td>
                <td>

                <input id="repeat"  type="text"  name="repeat_interval" value="<?php echo ($onedata->repeat_interval)? $onedata->repeat_interval : 1 ; ?>" >
                <select name="repeat_type" id="repeat_type">
                    <option <?php  echo ($onedata->repeat_type=='day')? 'checked="checked"' : ''; ?> value="day">Days</option>
                    <option <?php echo ($onedata->repeat_type=='week')? 'checked="checked"' : ''; ?>value="week">Weeks</option>
                    <option <?php echo ($onedata->repeat_type=='month')? 'checked="checked"' : ''; ?>value="month">Months</option>
                    <option <?php echo ($onedata->repeat_type=='year')? 'checked="checked"' : ''; ?> value="year">Years</option>
                </select>
                </td>
                    </tr>
                <tr>
                <td><label for="end_repeat">End repeat</label></td>
                <td>
                    <?php
                 $from_repeat=($onedata->end_repeat == '0000-00-00')? '' :$onedata->end_repeat ;
                echo JHTML::_('calendar',$from_repeat, 'end_repeat', 'end_repeat', $format = '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?>
                </td>
                </tr>
                <tr>
                <td>
                <label for="apply_repeat">Apply this Repeat Process  </label>
                </td>
                <td>
                <input id="apply_repeat"  type="checkbox"  name="apply_repeat" value="1" >
                </td>
                </tr>
                </table>

            </fieldset>

            

        </div>

        <!--Tabs 7-->
         <div id="tabs-7">

            <fieldset>
                <legend> Images </legend>
                <p>
                <a rel="{handler: 'iframe', size: {x: 650, y: 450}}" href="index.php?option=com_appthaeventz&view=addevent&layout=multiupload&tmpl=component&cid=<?php echo $cid; ?>" class="modal">Upload</a>
                </p>
            </fieldset>

        </div>
        <!--Tabs 8 Contact -->
         <div id="tabs-8">

            <fieldset>
                <legend> Contact Details</legend>
                <table>
                 <tr>
                     <td width="100"><label for="contact_name"> Name </label></td>
                     <td><input id="contact_name"  type="text"  name="contact_name" value="<?php echo $onedata->contact_name; ?>"  /></td>
                </tr>
                <tr>
                    <td><label for="contact_web">Website</label></td>
                    <td><input id="contact_web"  type="text"  name="contact_web" value="<?php echo $onedata->web; ?>" /></td>
                </tr>
                <tr>
                    <td><label for="contact_phone"> Phone</label></td>
                    <td><input id="contact_phone"  type="text"  name="contact_phone" value="<?php echo $onedata->phone; ?>"  /></td>
                </tr>
                <tr>
                    <td><label for="contact_email"> Email</label></td>
                    <td><input id="contact_email"  type="text"  name="contact_email" value="<?php echo $onedata->email; ?>"   /></td>
                </tr>
                </table>
            </fieldset>

        </div>

        <!--Tabs 9 Meta -->
         <div id="tabs-9">

            <fieldset>
                <legend> Meta Info </legend>
                <table>
                    <tr>    
                        <td><label for="meta_title"> Page Title</label></td>
                        <td><input id="meta_title"  type="text"  name="meta_title" value="<?php echo $onedata->title; ?>"  /></td>
                    </tr>
                    <tr>
                        <td><label for="meta_alias"> Page Alias</label></td>
                        <td><input id="meta_alias"  type="text"  name="meta_alias" value="<?php echo $onedata->alias; ?>"  /></td>
                    </tr>
                    <tr>
                        <td><label for="meta_keyword"> Meta Keyword</label></td>
                        <td>  <input id="meta_keyword"  type="text"  name="meta_keyword" value="<?php echo $onedata->keyword; ?>" /> </td>
                    </tr>
                    <tr>
                    <td><label for="meta_description">Meta Description </label></td>
                    <td><textarea id="meta_description"  name="meta_description" cols="50" rows="5"  > <?php echo $onedata->meta_description; ?> </textarea></td>
                    </tr>
                </table>

            </fieldset>

        </div>
            <!--Tabs 10 Meta -->
         <div id="tabs-10">
<?php
$share=json_decode($onedata->share);
$showing=json_decode($onedata->showing);

?>
            <fieldset>
                <legend> Sharing Options </legend>
                <table>
                <tr>
                    <td width="200"><label for="rating">Enable event rating </label></td>
                    <td><input id="rating"  type="checkbox"   value="1" name="share[rating]" <?php echo (empty($share->rating))? '' : 'checked="checked"'; ?> /> </td>
                </tr>
                <tr>
                <td>
                <label for="facebook">Enable Facebook like button </label>
                </td>
                <td>
                <input id="facebook"  type="checkbox"  value="1" name="share[facebook]" <?php echo (empty($share->facebook))? '' : 'checked="checked"'; ?>  />
                </td>
                </tr>
                <tr>
                    <td><label for="twitter">Enable Twitter share button </label></td>
                    <td><input id="twitter"  type="checkbox"   value="1" name="share[twitter]" <?php echo (empty($share->twitter))? '' : 'checked="checked"'; ?>  /></td>
                </tr>
                <tr>
                    <td><label for="google">Enable Google+ share button</label></td>
                    <td><input id="google"  type="checkbox" value="1" name="share[google]" <?php echo (empty($share->google))? '' : 'checked="checked"'; ?> /></td>
                </tr>
                </table>

            </fieldset>

            <fieldset>
                <legend> Event Options </legend>
                <table>
                  <tr>
                      <td width="200"><label for="startdate">Show start date </label></td>
                      <td><input id="startdate"  type="checkbox" <?php echo (empty($showing->startdate))? '' : 'checked="checked"'; ?> value="1" name="show[startdate]"  /></td>
                  </tr>
                  <tr>
                      <td><label for="enddate">Show end date </label></td>
                      <td><input id="enddate"  type="checkbox" <?php echo (empty($showing->enddate))? '' : 'checked="checked"'; ?>  value="1" name="show[enddate]"  /></td>
                </tr>
                <tr>
                    <td> <label for="description">Show description</label> </td>
                    <td> <input id="description"  type="checkbox" <?php echo (empty($showing->description))? '' : 'checked="checked"'; ?>  value="1" name="show[description]"  /> </td>
                </tr>
                <tr>
                    <td><label for="location">Show location</label></td>
                    <td><input id="location"  type="checkbox"  <?php echo (empty($showing->location))? '' : 'checked="checked"'; ?>   value="1" name="show[location]"  /></td>
                </tr>
                <tr>
                    <td><label for="category">Show categories</label></td>
                    <td><input id="category"  type="checkbox" <?php echo (empty($showing->category))? '' : 'checked="checked"'; ?>  value="1" name="show[category]"  /></td>
                </tr>
                <tr>
                    <td><label for="tags">Show tags</label></td>
                    <td><input id="tags"  type="checkbox"  <?php echo (empty($showing->tags))? '' : 'checked="checked"'; ?> value="1" name="show[tags]"  /></td>
                </tr>
                <tr>
                    <td><label for="files">Show Images</label></td>
                    <td><input id="files"  type="checkbox" <?php echo (empty($showing->files))? '' : 'checked="checked"'; ?>  value="1" name="show[files]"  /></td>
                </tr>
                <tr>
                    <td><label for="contact">Show contact information</label></td>
                    <td><input id="contact"  type="checkbox" <?php echo (empty($showing->contact))? '' : 'checked="checked"'; ?>  value="1" name="show[contact]"  /></td>
                </tr>
                <tr>
                    <td><label for="map">Show map</label></td>
                    <td><input id="map"  type="checkbox" <?php echo (empty($showing->map))? '' : 'checked="checked"'; ?>  value="1" name="show[map]"  /></td>
                </tr>
                <tr>
                    <td><label for="export">Show export event</label></td>
                    <td><input id="export"  type="checkbox" <?php echo (empty($showing->export))? '' : 'checked="checked"'; ?>  value="1" name="show[export]"  /></td>
                </tr>
                <tr>
                    <td><label for="invite">Show invite</label></td>
                    <td><input id="invite"  type="checkbox" <?php echo (empty($showing->invite))? '' : 'checked="checked"'; ?>  value="1" name="show[invite]"  /> </td>
                </tr>
                <tr>
                    <td><label for="postedby">Show posted by</label></td>
                    <td> <input id="postedby"  type="checkbox" <?php echo (empty($showing->postedby))? '' : 'checked="checked"'; ?>  value="1" name="show[postedby]"  /></td>
                </tr>
                <tr>
                <td><label for="repeated">Show repeated events</label></td>
                <td><input id="repeated"  type="checkbox" <?php echo (empty($showing->repeated))? '' : 'checked="checked"'; ?>  value="1" name="show[repeated]"  /></td>
                </tr>
                </table>
            </fieldset>

        </div>
             <!--Tabs 11 Others -->
         <div id="tabs-11">

            <fieldset>
                <legend> Others </legend>
                <table>
                <tr>
                <td><label for="speakers">Speakers</label></td>
                <td>
                <?php
                $editor = JFactory::getEditor();
                echo $editor->display('speakers',$onedata->speakers, '500', '200', '60', '20', false );  //to display editor
                ?>
                </td>
                </tr>
                <tr>
                    <td><label for="sponsors">Sponsors </label></td>
                    <td>
                <?php
                $editor = JFactory::getEditor();
                echo $editor->display('sponsors',$onedata->sponsors, '500', '200', '60', '20', false );  //to display editor
                ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="who_attend">Who should attend</label></td>
                    <td>
                <?php
                $editor = JFactory::getEditor();
                echo $editor->display('who_attend',$onedata->who_attend, '500', '200', '60', '20', false );  //to display editor
                ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="speakers">Benefit</label></td>
                <td>
                <?php
                $editor = JFactory::getEditor();
                echo $editor->display('benefit',$onedata->benefit, '500', '200', '60', '20', false );  //to display editor
                ?>
                </td>
                </tr>
                </table>


            </fieldset>

        </div>
</div>
      <input type="hidden" value="" name="task" id="task">
       <input type="hidden" value="<?php echo ($tabs)? $tabs : 1; ?>" name="tabs" id="tabing">
</form>
<script language="JavaScript" type="text/javascript">


    //validations for the fields inside each tab.
<?php if (version_compare(JVERSION, '1.6.0', 'ge')) { ?>
                      Joomla.submitbutton = function(pressbutton) {
<?php } else { ?>
                    function submitbutton(pressbutton) {
<?php } ?>


                    if (((pressbutton == "apply")||(pressbutton == "save")))
                    {

                    

                    var end_date_event_control = document.getElementById('to').value;
                    var end_date_event_control = end_date_event_control.split(" ");
                    var end_date_event_control = end_date_event_control[0];

                    

                         if(document.getElementById('name').value == "")
                         {
                            alert("Event name should not be empty");
                            document.getElementById('name').focus();
                            return false;
                         }
                         
                            var x= new Date();
                            var year= x.getFullYear()
                            var m= x.getMonth()+1;
                            var d= x.getDate();
                            var newdate = year+"-"+m+"-"+d;
                            var end_date = document.getElementById('end').value;
                            var end_date = end_date.split(" ");
                            var end_date = end_date[0];

                            var start_date = document.getElementById('start').value;
                            var start_date = start_date.split(" ");
                            var start_date = start_date[0];
                    
                          if(start_date=='')
                          {
                           alert("Start date should not be empty");
                            document.getElementById('start').focus();
                            document.getElementById('start').value='';
                            return false;
                          }
                          if(end_date=='')
                          {
                           alert("End date should not be empty");
                            document.getElementById('end').focus();
                            document.getElementById('end').value='';
                            return false;
                          }
                          else
                           {
                           if(end_date < start_date)
                           {
                            alert("End date should be greater than Start date");
                            return false;
                           }
                           }
                      if(document.getElementById('location').value == "")
                        {
                            alert("Location should not be empty");
                            document.getElementById('location').focus();
                            return false;
                        }
                         if(document.getElementById('category').value == "")
                        {
                            alert("Category should not be empty");
                            document.getElementById('category').focus();
                            return false;
                        }
                    
                    <?php if($cid) { ?>

                        


                           var end_date_event_control = document.getElementById('to').value;
                           var end_date_event_control = end_date_event_control.split(" ");
                           var end_date_event_control = end_date_event_control[0];

                           var start_date_event_control = document.getElementById('from').value;
                           var start_date_event_control = start_date_event_control.split(" ");
                           var start_date_event_control = start_date_event_control[0];

                          /* if(start_date_event_control !='')
                           {
                           if(start_date_event_control < newdate)
                           {
                            alert("Please enter a valid Start date for Event Control");
                            return false;
                           }
                           }*/
                           
                          if(end_date_event_control !='' && start_date_event_control !='' )
                           {
                           if(end_date_event_control <= start_date_event_control)
                           {
                            alert("End date should be greater than Start date for Event Control");
                            return false;
                           }
                           }

                     if(document.getElementById('overbooking').checked)
                      {
                     
                    var overamount =document.getElementById('overamount').value;
                    if((overamount == '')||( overamount == '0') ||( overamount < '0'))
                        {
                            alert("Invalid OverBooking Count");
                            document.getElementById('overamount').focus();
                            return;
                        }
                        else if( (overamount) != '' && isNaN(overamount) )
                        {
                            alert("Invalid OverBooking Amount");
                            document.getElementById('overamount').focus();
                            return;
                        }
                      }

                    var contact_web = document.getElementById("contact_web").value;
                    var contact_phone = document.getElementById("contact_phone").value;

                         var pattern1 = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

                            if ((contact_web != "")&&(!pattern1.test(contact_web))) {
                                alert( 'Invalid Website Address');
                                    return;
                            }

                            var letters = /^[\0-9]+$/;
                            if((trim(contact_phone) != "")&&(!trim(contact_phone).match(letters,"")))
                            {
                               alert("Invalid Phone Number");
                            return false;
                            }

                        var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;

                        if((trim(document.getElementById('contact_email').value) != "") && (!pattern.test(trim(document.getElementById('contact_email').value))))
                        {
                            alert( 'You must provide a valid contact Email' )
                            return;
                        }
                       
                      <?php } ?>

                      
                        



           var ret =  true;

           $(function(){
           var obj =  $('#more_table').find('.tickets_1');
           var flag=0;
           var letters = /^[0-9a-zA-Z]+$/;
         
           $.each(obj,function(i,event){
           var somevalue = event.value;
           if((somevalue == "")){
           $('#'+event.id).css('border','1px red solid');
           flag++;
         
           }
           });         
if(flag > 0 ){
    alert("Please Enter a valid ticket name");
    ret = false ;
}
         
           
           if(ret)
           {

           //alert(ret);
           var obj =  $('#more_table').find('.tickets_2');
          var flag=0;

           var letters1 = /^[0-9]+$/;          
           $.each(obj,function(i,event){
           var somevalue = event.value;

           if((somevalue == "")&&(!trim(somevalue).match(letters1))){


           $('#'+event.id).css('border','1px red solid');
           flag++;
         
          
           }
           
           }
   
   );
if(flag > 0 ){
    alert("Please Enter a valid ticket price");
    ret = false ;
}
           }
        if(ret)
           {
           var flag=0;
           var obj =  $('#more_table').find('.tickets_3');
           var letters1 = /^[0-9]+$/;
          
           $.each(obj,function(i,event){
           var somevalue = event.value;
           if((somevalue == "")&&(!trim(somevalue).match(letters1))){
           $('#'+event.id).css('border','1px red solid');
           flag++;
           

           }
           });
          if(flag > 0 ){
    alert("Please Enter a valid No. of seats");
    ret = false ;
}
           }
           
    });
    if(!ret)
    {
    return false;
    }}


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