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
           JToolBarHelper::title('Add Events');
           JToolBarHelper::apply();
           JToolBarHelper::save();
           JToolBarHelper::cancel();
$document = JFactory::getDocument();

$document->addScript('components/com_appthaeventz/assets/js/1.8.0.js');
$document->addStyleSheet('components/com_appthaeventz/assets/css/jquery-ui-1.8.23.custom.css');
$document->addScript('components/com_appthaeventz/assets/js/jquery-ui-1.8.23.custom.js');
$document->addStyleSheet('components/com_appthaeventz/assets/css/events.css');

        jimport( 'joomla.html.html' );
        JHtml::_('behavior.tooltip');
        JHTML::_('behavior.modal');
//load libraries for validation
        JHtml::_ ( 'behavior.formvalidation' );
        JHtml::_('behavior.calendar');
           
// Assign data
         $onedata=$this->onedata;
print_r($onedata);
         ?>
<script type="text/javascript">
	$(function() {
		$( "#tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
		$( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
	});

        //add row
        function add_row()
        {
            $('#more_table').append('<tr><td><input type="text" name="ticket_name[]" /></td><td><input type="text" name="ticket_price[]" /></td><td><input type="text" name="ticket_seats[]" /></td><td><textarea name="ticket_description[]"></textarea></td></tr>');

        }
 </script>
	
      <form  id="adminForm" name="adminForm" method="post">

          <div id="tabs">
              <div class="floatleft">
              <div class="add_photo">
                <a rel="{handler: 'iframe', size: {x: 400, y: 300}}" href="index.php?option=com_appthaeventz&view=addevent&tmpl=raw" class="modal">Add Photo</a>
            </div>

	     <ul>
                <li><a href="#tabs-1">Event Details</a></li>
		<li><a href="#tabs-2">Category and Tags</a></li>
                <li><a href="#tabs-3">Event Control</a></li>
		<li><a href="#tabs-4">Tickets Layout</a></li>
                <li><a href="#tabs-5">Tickets</a></li>
                <li><a href="#tabs-6">Recurring</a></li>
                <li><a href="#tabs-7">Images</a></li>
                <li><a href="#tabs-8">Contact</a></li>
                <li><a href="#tabs-9">Meta Info</a></li>
                <li><a href="#tabs-10">Front End</a></li>
                <li><a href="#tabs-11">Others</a></li>


	</ul>
                  </div>
        <!--Tabs 1-->
	<div id="tabs-1">
            <fieldset>
                <legend>Event Information</legend>
                <p>
                <label for="name">Event name:</label>
                <input id="name"  type="text" name="name" value="<?php echo $onedata->event_name; ?>" >
                </p>
                <br clear="all" >
                <table>
                <tr>
                 <td>  <label for="start">Starting:</label> </td>
                 <td>  <?php echo JHTML::_('calendar',$onedata->start_date, 'start', 'start', $format = '%Y-%m-%d %H:%M:%I', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?> </td>
                <td><label for="end">Ending:</label></td>
                <td> <?php echo JHTML::_('calendar',$onedata->end_date, 'end', 'end', $format = '%Y-%m-%d %H:%M:%I', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?> </td>
                </tr>
                </table>

                <p>
                <label for="location">Location:</label>
                <input id="location"  type="text"  name="location" value="<?php echo $onedata->location; ?>" >
                </p>
                <p>
                <label for="description">Description:</label>
                <br>
                <?php
                $editor = JFactory::getEditor();
                echo $editor->display('description',$onedata->description, '700', '300', '60', '20', false );  //to display editor
                ?>
                </p>
            </fieldset>
        </div>
        <!--Tabs 2-->
	<div id="tabs-2">
	 <fieldset>
                <legend>Categories</legend>
                <p>
                <label for="category">Category:</label>
                <select name="category[]" size="10" multiple="multiple>
                <?php
                if($onedata->category_id): $one_catgeory=explode(",",$onedata->category_id);  endif;
                foreach($this->category as $val): ?>
                
                        <option <?php if($one_catgeory)if(in_array($val->value,$one_catgeory)): echo 'selected="selected"'; endif;?>  value="<?php echo $val->value; ?>" > <?php echo $val->text; ?> </option>
                 <?php endforeach;
                ?>
                </select>
                </p>
         </fieldset>      
                
            <fieldset>
                <legend>Tags</legend>
                <p>
                <label for="tags">Tags:</label>
                <input id="tags"  type="text"  name="tags" value="<?php echo $onedata->tags; ?>" >
                </p>
             </fieldset>
	</div>
        <!--Tabs 3-->
	<div id="tabs-3">
            <fieldset>
                <legend>Event Control</legend>
                <p> <label for="from">Starting:</label>
                 <?php echo JHTML::_('calendar',$onedata->from_date, 'from', 'from', $format = '%Y-%m-%d %H:%M:%I', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?>
                </p>
                <p>
                <label for="to">Ending:</label>
                <?php echo JHTML::_('calendar',$onedata->to_date, 'to', 'to', $format = '%Y-%m-%d %H:%M:%I', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?>
                </p>
                <p>
                <label for="overbooking">Allow Overbooking :</label>
                <input id="location"  type="checkbox"  name="overbooking" <?php if($onedata->allow_overbooking==1): echo ""; endif; ?> value="1" >
                </p>
                <p>
                <label for="overamount">Overbooking Amount :</label>
                <input id="overamount"  type="text"  name="overamount" value="" >
                </p>
                <p>
                <label for="notify_me">Notify the owner on new subscriptions :</label>
                <input id="notify_me"  type="checkbox"  name="notify_me" value="1" >
                </p>
                <p>
                <label for="show_guest">Show registered guests on the event page :</label>
                <input id="show_guest"  type="checkbox"  name="show_guest" value="1" >
                </p>
                <p>
                <label for="auto_approve">Automatically approve free registrations :</label>
                <input id="auto_approve"  type="checkbox"  name="auto_approve" value="1" >
                </p>
            </fieldset>

        </div>
        <!--Tabs 4-->
        <div id="tabs-4">

            <fieldset>
                <legend> Ticket Layout </legend>
                <p>
                <label for="activate_email">Attach to the Activation E-mail? :</label>
                <input id="activate_email"  type="checkbox"  name="activate_email" value="1" >
                </p>
                <p>
                <label for="ticket_layout">Ticket Layout :</label>
                <br>
                 <?php
                $editor = JFactory::getEditor();
                echo $editor->display('ticket_layout',$onedata->ticket_layout, '700', '300', '60', '20', false );  //to display editor
                ?>
                </p>
                
            </fieldset>

        </div>
        <!--Tabs 5-->
          <div id="tabs-5">

            <fieldset>
                <legend> Tickets </legend>
                <div class="right"><a onclick="add_row();" href="javascript:void(0);">Add </a></div>
                <span class="clear"></span>
                <table border="1" width="600" id="more_table">

                    <tr>
                        <th> Ticket Name </th>
                        <th> Price </th>
                        <th> No of Seats </th>
                        <th> Description </th>

                    </tr>

                </table>

            </fieldset>

        </div>

        <!--Tabs 6-->
         <div id="tabs-6">

            <fieldset>
                <legend> Ticket Layout </legend>
                <p>
                <label for="activate_email">Attach to the Activation E-mail? :</label>
                <input id="activate_email"  type="checkbox"  name="activate_email" value="1" >
                </p>
                <p>
                <label for="ticket_layout">Ticket Layout :</label>
                <br>
                 <?php
                $editor = JFactory::getEditor();
                echo $editor->display('ticket_layout',$onedata->ticket_layout, '700', '300', '60', '20', false );  //to display editor
                ?>
                </p>

            </fieldset>

        </div>

        <!--Tabs 7-->
         <div id="tabs-7">

            <fieldset>
                <legend> Images </legend>
                <input type="file" name="images[]" />
            </fieldset>

        </div>
        <!--Tabs 8 Contact -->
         <div id="tabs-8">

            <fieldset>
                <legend> Contact </legend>
                <p>
                <label for="contact_name"> Name:</label>
                <input id="contact_name"  type="text"  name="contact_name"  />
                </p>
                <p>
                <label for="contact_web"> Name:</label>
                <input id="contact_web"  type="text"  name="contact_web"  />
                </p>
                <p>
                <label for="contact_phone"> Phone:</label>
                <input id="contact_phone"  type="text"  name="contact_phone"  />
                </p>
                <p>
                <label for="contact_email"> Email:</label>
                <input id="contact_email"  type="text"  name="contact_email"  />
                </p>
            </fieldset>

        </div>

        <!--Tabs 9 Meta -->
         <div id="tabs-9">

            <fieldset>
                <legend> Meta tags </legend>
                <p>
                <label for="meta_title"> Page Title:</label>
                <input id="meta_title"  type="text"  name="meta_title"  />
                </p>
                <p>
                <label for="meta_keyword"> Meta Keyword:</label>
                <input id="meta_keyword"  type="text"  name="meta_keyword"  />
                </p>
                <p>
                <label for="meta_description">Meta Description :</label>
                <textarea id="meta_description"  name="meta_description" cols="50" rows="5"  > </textarea>
                </p>
                
            </fieldset>

        </div>
            <!--Tabs 10 Meta -->
         <div id="tabs-10">

            <fieldset>
                <legend> Sharing Options </legend>
                <p>
                <label for="rating">Enable event rating </label>
                <input id="rating"  type="checkbox" checked="checked"  value="1" name="share[rating]"  />
                </p>
                <p>
                <label for="facebook">Enable Facebook like button </label>
                <input id="facebook"  type="checkbox" checked="checked"  value="1" name="share[facebook]"  />
                </p>
                <p>
                <label for="twitter">Enable Twitter share button </label>
                <input id="twitter"  type="checkbox" checked="checked"  value="1" name="share[twitter]"  />
                </p>
                <p>
                <label for="google">Enable Google+ share button</label>
                <input id="google"  type="checkbox" checked="checked"  value="1" name="share[google]"  />
                </p>

            </fieldset>

            <fieldset>
                <legend> Event Options </legend>
                <p>
                <label for="startdate">Show start date </label>
                <input id="startdate"  type="checkbox" checked="checked"  value="1" name="show[startdate]"  />
                </p>
                <p>
                <label for="enddate">Show end date </label>
                <input id="enddate"  type="checkbox" checked="checked"  value="1" name="show[enddate]"  />
                </p>
                <p>
                <label for="description">Show description</label>
                <input id="description"  type="checkbox" checked="checked"  value="1" name="show[description]"  />
                </p>
                <p>
                <label for="location">Show location</label>
                <input id="location"  type="checkbox" checked="checked"  value="1" name="show[location]"  />
                </p>
                <p>
                <label for="category">Show categories</label>
                <input id="category"  type="checkbox" checked="checked"  value="1" name="show[category]"  />
                </p>
                <p>
                <label for="tags">Show tags</label>
                <input id="tags"  type="checkbox" checked="checked"  value="1" name="show[tags]"  />
                </p>
                <p>
                <label for="files">Show files</label>
                <input id="files"  type="checkbox" checked="checked"  value="1" name="show[files]"  />
                </p>
                <p>
                <label for="contact">Show contact information</label>
                <input id="contact"  type="checkbox" checked="checked"  value="1" name="show[contact]"  />
                </p>
                <p>
                <label for="map">Show map</label>
                <input id="map"  type="checkbox" checked="checked"  value="1" name="show[map]"  />
                </p>
                <p>
                <label for="export">Show export event</label>
                <input id="export"  type="checkbox" checked="checked"  value="1" name="show[export]"  />
                </p>
                <p>
                <label for="invite">Show invite</label>
                <input id="invite"  type="checkbox" checked="checked"  value="1" name="show[invite]"  />
                </p>
                <p>
                <label for="postedby">Show posted by</label>
                <input id="postedby"  type="checkbox" checked="checked"  value="1" name="show[postedby]"  />
                </p>
                <p>
                <label for="repeated">Show repeated events</label>
                <input id="repeated"  type="checkbox" checked="checked"  value="1" name="show[repeated]"  />
                </p>
            </fieldset>

        </div>
             <!--Tabs 11 Others -->
         <div id="tabs-11">

            <fieldset>
                <legend> Others </legend>
                <p>
                <label for="speakers">Speakers:</label>
                <?php
                $editor = JFactory::getEditor();
                echo $editor->display('speakers',$onedata->speakers, '500', '200', '60', '20', false );  //to display editor
                ?>
                </p>
                <p>
                <label for="sponsors">Sponsors :</label>
                <?php
                $editor = JFactory::getEditor();
                echo $editor->display('sponsors',$onedata->sponsors, '500', '200', '60', '20', false );  //to display editor
                ?>
                </p>
                <p>
                <label for="who_attend">Who should attend:</label>
                <?php
                $editor = JFactory::getEditor();
                echo $editor->display('who_attend',$onedata->who_attend, '500', '200', '60', '20', false );  //to display editor
                ?>
                </p>
                <p>
                <label for="speakers">Benefit:</label>
                <?php
                $editor = JFactory::getEditor();
                echo $editor->display('benefit',$onedata->benefit, '500', '200', '60', '20', false );  //to display editor
                ?>
                </p>

            </fieldset>

        </div>
</div>
      <input type="hidden" value="" name="task" id="task">
</form>
