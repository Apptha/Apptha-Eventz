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
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class AppthaeventzModeladdevent extends JModel {

    function saveevents() {
        $db = JFactory::getDBO();

        //event detail
        $name = JRequest::getvar('name', null, 'POST');

        $start = JRequest::getvar('start', null, 'POST');
        $end = JRequest::getvar('end', null, 'POST');
        $location_id = JRequest::getvar('location_id', '', 'post', 'string');
        $descriptions = JRequest::getvar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
        $description = $db->getEscaped($descriptions);
        $img_name = JRequest::getvar('img_name', null, 'POST');
        //category
        $categorys = JRequest::getvar('category', null, 'POST', 'array');
        if ($categorys): $category = implode(",", $categorys);
        endif;
        //save event details

        $db->setQuery("INSERT INTO #__em_events
                (event_name,start_date,end_date,created_date,published,image_name,description,category_id)
                 VALUES
                ('$name','$start','$end',NOW(),'1','$img_name','$description','$category')");

        $db->query();
        $db->getErrorMsg();
        $lastid = $db->insertid();

        $location = JRequest::getvar('location', null, 'post');
        $address = JRequest::getvar('address', null, 'post');
        $lat = JRequest::getvar('latitude', null, 'post');
        $lng = JRequest::getvar('longitude', null, 'post');

        //save Location

        $db->setQuery("INSERT  INTO #__em_locations
                  (event_id,location_name,address,latitude,longitude)
                   VALUES('$lastid','$location','$address','$lat','$lng') ");
        $db->query();
        echo $db->getErrorMsg();

        //tags
        $tags = JRequest::getvar('tags', null, 'POST');
        if ($tags) :
            $tag = explode(",", $tags);
            foreach ($tag as $t):
                //insert tags
                $db->setQuery("INSERT INTO #__em_tags
                (tag_name,event_id)
                VALUES
                ('$t','$lastid')");
                $db->query();
            endforeach;
        endif;

        //save event control
        $db->setQuery("INSERT INTO #__em_control
                (event_id)
                VALUES
                ('$lastid')");
        $db->query();

        //save event Ticket Layout
        $db->setQuery("INSERT INTO #__em_ticketlayout
                (event_id)
                VALUES
                ('$lastid')");
        $db->query();

        //save event Recurring
        $db->setQuery("INSERT INTO #__em_events_recurring
                (event_id)
                VALUES
                ('$lastid')");
        $db->query();

        //insert contact details
        $db->setQuery("INSERT INTO #__em_contacts
                (event_id)
                VALUES
                ('$lastid')");
        $db->query();

        //insert Meta details
        $db->setQuery("INSERT INTO #__em_metas
                (event_id)
                VALUES
                ('$lastid')");
        $db->query();

        $share = array('rating' => 1, 'facebook' => 1, 'twitter' => 1, 'google' => 1);
        $en_share = json_encode($share);
        $show = array('startdate' => 1, 'enddate' => 1, 'description' => 1, 'location' => 1, 'category' => 1
            , 'tags' => 1, 'files' => 1, 'contact' => 1, 'map' => 1, 'export' => 1, 'invite' => 1, 'postedby' => 1, 'repeated' => 1);
        $en_show = json_encode($show);
        //insert Frontend details
        $db->setQuery("INSERT INTO #__em_events_options
                (event_id,share,showing)
                VALUES
                ('$lastid','$en_share','$en_show') ");
        $db->query();

        //insert contact details
        $db->setQuery("INSERT INTO #__em_others
                (event_id)
                VALUES
                ('$lastid')");
        $db->query();

        return $lastid;
    }

    function updateevents() {

        $db = JFactory::getDBO();
        $cid = JRequest::getvar('cid', null, 'get');
        $tabing = JRequest::getvar('tabs', null, 'post');

        //select to check event details

        $db->setQuery("SELECT * FROM #__em_events
                      WHERE id=$cid");
        $db->query();
        $event_detail = $db->loadObject();
        $event_tags = $event_detail->tags;

        //event detail
        $name = JRequest::getvar('name', null, 'POST');

        $start = JRequest::getvar('start', null, 'POST');
        $end = JRequest::getvar('end', null, 'POST');
        $descriptions = JRequest::getvar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
        $description = $db->getEscaped($descriptions);
        $img_name = JRequest::getvar('img_name', null, 'POST');
        //category
        $categorys = JRequest::getvar('category', null, 'POST', 'array');
        if ($categorys): $category = implode(",", $categorys);
        endif;

        //update event details
        $db->setQuery("UPDATE #__em_events
                SET event_name='$name',start_date='$start',end_date='$end',description='$description',category_id='$category',image_name='$img_name'
                WHERE id=$cid");
        $db->query();
        echo $db->getErrorMsg();

        $location = JRequest::getvar('location', null, 'post');
        $address = JRequest::getvar('address', null, 'post');
        $lat = JRequest::getvar('latitude', null, 'post');
        $lng = JRequest::getvar('longitude', null, 'post');

        $db->setQuery("UPDATE #__em_locations
                  SET location_name='$location',address='$address',latitude='$lat' ,longitude='$lng'
                  WHERE event_id='$cid'");
        $db->query();


        //recurring
        $apply_repeat = JRequest::getvar('apply_repeat', null, 'POST');
        if ($apply_repeat):
            $event_times = JRequest::getvar('event_times', null, 'POST');
            if ($event_times):
                $final_startdate = JRequest::getvar('final_startdate', null, 'POST');
                $recurring_start = explode(",", $final_startdate);

                $final_enddate = JRequest::getvar('final_enddate', null, 'POST');
                $recurring_end = explode(",", $final_enddate);
                //recurring detail
                $repeat_interval = JRequest::getvar('repeat_interval', null, 'POST');
                $repeat_type = JRequest::getvar('repeat_type', null, 'POST');
                $end_repeat = JRequest::getvar('end_repeat', null, 'POST');
                //update recurring details
                $db->setQuery("UPDATE #__em_events_recurring
                SET repeat_interval='$repeat_interval',repeat_type='$repeat_type',end_repeat='$end_repeat',repeat_times='$event_times'
                WHERE event_id=$cid");

                $db->query();

                ///Insert recurring event
                for ($i = 0; $i < $event_times; $i++):

                    //save recurring event details
                    $db->setQuery("INSERT INTO #__em_events
                (event_name,start_date,end_date,created_date,published,parent_id)
                VALUES
                ('$name','$recurring_start[$i]','$recurring_end[$i]',NOW(),'1','$event_detail->id')");

                    $db->query();
                endfor;
            endif;
        endif;

        //tags
        $tags = JRequest::getvar('tags', null, 'POST');
        if ($tags) :
            $db->setQuery("DELETE FROM #__em_tags WHERE event_id='$cid'");
            $db->query();
            $tag = explode(",", $tags);
            foreach ($tag as $t):
                //insert tags
                $db->setQuery("INSERT INTO #__em_tags
                (tag_name,event_id)
                VALUES
                ('$t','$cid')");
                $db->query();
            endforeach;
        endif;


        //event control
        $from = JRequest::getvar('from', null, 'POST');
        $to = JRequest::getvar('to', null, 'POST');
        $overbooking = JRequest::getvar('overbooking', '0', 'POST');
        $overamount = JRequest::getvar('overamount', '0', 'POST');
        $notify_me = JRequest::getvar('notify_me', '0', 'POST');
        $show_guest = JRequest::getvar('show_guest', '0', 'POST');
        $auto_approve = JRequest::getvar('auto_approve', '0', 'POST');

        //update event control
        $db->setQuery("UPDATE #__em_control
                SET from_date='$from',to_date='$to',allow_overbooking='$overbooking',
                overbooking_amount='$overamount',notify_owner='$notify_me',show_guest='$show_guest',auto_approve='$auto_approve'
                WHERE event_id=$cid");
        $db->query();

        $activate_email = JRequest::getvar('activate_email', null, 'POST');
        $ticket_layouts = JRequest::getvar('ticket_layout', '', 'post', 'string', JREQUEST_ALLOWRAW);
        $ticket_layout = $db->getEscaped($ticket_layouts);

        //update event Ticket Layout
        $db->setQuery("UPDATE #__em_ticketlayout
                SET attach_email='$activate_email',ticketlayout='$ticket_layout'
                WHERE event_id = $cid");
        $db->query();

        
        /** To update tickets start **/        
        $ticket_id = JRequest::getvar('ticket_id', '', 'POST', 'ARRAY');
        $query = $db->setQuery("SELECT id FROM #__em_tickets WHERE event_id=$cid");
        $result = $db->loadResultArray();
        $diff_arr = array_diff($result,$ticket_id);
        $diff_arr = implode($diff_arr,',');
        if ($diff_arr != '') {           
            $query = $db->setQuery("DELETE FROM #__em_tickets WHERE id in ($diff_arr)");
            $db->query();
        }
        $ticket_name = JRequest::getvar('ticket_name', '', 'POST', 'ARRAY');
        $ticket_price = JRequest::getvar('ticket_price', '', 'POST', 'ARRAY');
        $ticket_seats = JRequest::getvar('ticket_seats', '', 'POST', 'ARRAY');
        $ticket_description = JRequest::getvar('ticket_description', '', 'POST', 'ARRAY');
        $tick_count = count($ticket_name);
        for ($i = 0; $i < $tick_count; $i++):
            if ($ticket_name[$i] && !$ticket_id[$i]) { //check empty
                $db->setQuery("INSERT INTO #__em_tickets
                (event_id,ticket_name,price,seats,description)
                VALUES
                ('$cid','$ticket_name[$i]','$ticket_price[$i]','$ticket_seats[$i]','$ticket_description[$i]')");
            } else {
                $db->setQuery("UPDATE #__em_tickets
                SET event_id ='$cid',ticket_name='$ticket_name[$i]',price='$ticket_price[$i]',seats='$ticket_seats[$i]',description='$ticket_description[$i]'
                WHERE id= $ticket_id[$i]");
            }
            $db->query();
            $db->getErrorMsg();
        endfor;
        /** To update tickets end **/
        

        //update Contact
        $contact_name = JRequest::getvar('contact_name', '', 'POST');
        $contact_web = JRequest::getvar('contact_web', '', 'POST');
        $contact_phone = JRequest::getvar('contact_phone', '', 'POST');
        $contact_email = JRequest::getvar('contact_email', '', 'POST');
        //update contact details
        $db->setQuery("UPDATE #__em_contacts
                SET contact_name='$contact_name',web='$contact_web',phone='$contact_phone',email='$contact_email'
                WHERE event_id = $cid ");
        $db->query();

        //update Meta
        $meta_title = JRequest::getvar('meta_title', '', 'POST');
        $meta_keyword = JRequest::getvar('meta_keyword', '', 'POST');
        $meta_description = JRequest::getvar('meta_description', '', 'POST');
        if (empty($meta_title)) {
            $meta_title = $name;
        }
        $aliaing = JRequest::getvar('meta_alias', null, 'POST');

        if ($aliaing) {
            $aliaing_r = str_replace('-', ' ', $aliaing);
            $aliaing_prase = preg_replace(array('/\s+/', '/[^A-Za-z0-9\-]/'), array('-', ''), $aliaing_r);
            // lowercase and trim
            $alias = trim(strtolower($aliaing_prase));
        } else {
            $aliaing_r = str_replace('-', ' ', $meta_title);
            $aliaing_prase = preg_replace(array('/\s+/', '/[^A-Za-z0-9\-]/'), array('-', ''), $aliaing_r);
            // lowercase and trim
            $alias = trim(strtolower($aliaing_prase));
        }

        //update Meta details
        $db->setQuery("UPDATE #__em_metas
                SET title='$meta_title',keyword='$meta_keyword',meta_description='$meta_description',alias='$alias'
                WHERE event_id = $cid ");
        $db->query();

        //update  Frontend
        $share = JRequest::getvar('share', '', 'POST', 'ARRAY');
        $en_share = json_encode($share);
        $show = JRequest::getvar('show', '', 'POST', 'ARRAY');
        $en_show = json_encode($show);
        //update Frontend details
        $db->setQuery("UPDATE  #__em_events_options
                SET share='$en_share',showing='$en_show'
                WHERE event_id = $cid ");
        $db->query();


        //save Others
        $speakers = JRequest::getvar('speakers', '', 'POST', 'string', JREQUEST_ALLOWRAW);
        $sponsors = JRequest::getvar('sponsors', '', 'POST', 'string', JREQUEST_ALLOWRAW);
        $who_attend = JRequest::getvar('who_attend', '', 'POST', 'string', JREQUEST_ALLOWRAW);
        $benefit = JRequest::getvar('benefit', '', 'POST', 'string', JREQUEST_ALLOWRAW);
        //update other details
        $db->setQuery("UPDATE #__em_others
                SET speakers='$speakers',sponsors='$sponsors',who_attend='$who_attend',benefit='$benefit'
                WHERE event_id = $cid ");
        $db->query();

        return $cid;
    }

    //get event data
    function oneEventData() {
        $db = JFactory::getDBO();
        $id = JRequest::getvar('cid', null, 'GET');
        if ($id) {

            /*             * **********build query********** */

            $query = "SELECT e.id,e.event_name,e.start_date,e.end_date,e.description,e.category_id,e.tags,e.tags,e.image_name,
                 l.location_name,l.address,l.latitude,l.longitude,
                 c.from_date,c.to_date,c.allow_overbooking,c.overbooking_amount,c.notify_owner,c.show_guest,c.auto_approve,
                 ticket.attach_email,ticket.ticketlayout,
                 recurring.repeat_interval,recurring.repeat_type,recurring.repeat_times,recurring.end_repeat,
                 con.contact_name,con.web,con.phone,con.email,
                 m.title,m.alias,m.keyword,m.meta_description,
                 op.share,op.showing,
                 other.speakers,other.sponsors,other.who_attend,other.benefit
                 From #__em_events as e
                 LEFT JOIN #__em_locations as l
                 ON l.event_id=e.id
                 LEFT JOIN #__em_control as c
                 ON e.id=c.event_id
                 LEFT JOIN #__em_ticketlayout as ticket
                 ON e.id=ticket.event_id
                 LEFT JOIN #__em_events_recurring as recurring
                 ON e.id=recurring.event_id
                 LEFT JOIN #__em_contacts as con
                 ON con.event_id=e.id
                 LEFT JOIN #__em_metas as m
                 ON  m.event_id=e.id
                 LEFT JOIN #__em_events_options as op
                 ON  op.event_id=e.id
                 LEFT JOIN #__em_others as other
                 ON  other.event_id=e.id
                 WHERE e.id = " . $id;

            $db->setQuery($query);
            $result = $db->loadObject();

            echo $db->getErrorMsg();
        } else {
            $result = array();
        }
        return $result;
    }

    ////category

    public function AllCategory() {
        $db = JFactory::getDbo();

        $db->setQuery(
                'SELECT a.id AS value, a.name AS text, COUNT(DISTINCT b.id) AS level' .
                ' FROM #__em_categories AS a' .
                ' LEFT JOIN `#__em_categories` AS b ON a.lft > b.lft AND a.rgt < b.rgt' .
                ' WHERE a.published = 1 ' .
                ' GROUP BY a.id, a.name, a.lft, a.rgt' .
                ' ORDER BY a.lft ASC'
        );
        $options = $db->loadObjectList();
        foreach ($options as &$option) {
            $option->text = str_repeat('- ', $option->level) . $option->text;
        }


        return $options;
    }

    function uploads() {
        $file = JRequest::getVar('photo', '', 'files');
        $ran = rand(10, 1000);
        if ($file['name'] != '') {
            $userImage = JFile::makeSafe($file['name']);
            //image converting code
            $image = JFile::makeSafe($file['name']);
            $uploadedfile = $file['tmp_name'];
            $userImageDetails = pathinfo($image);
            $extension = strtolower($userImageDetails['extension']);
            $events_image = $userImageDetails['basename'];
            if (($extension == "jpg") || ($extension == "jpeg") || ($extension == "png") || ($extension == "gif")) {

                $size = filesize($file['tmp_name']);
                if ($extension == "jpg" || $extension == "jpeg") {
                    $uploadedfile = $file['tmp_name'];
                    $src = imagecreatefromjpeg($uploadedfile);
                } else if ($extension == "png") {
                    $uploadedfile = $file['tmp_name'];
                    $src = imagecreatefrompng($uploadedfile);
                } else if ($extension == "gif") {
                    $uploadedfile = $file['tmp_name'];
                    $src = imagecreatefromgif($uploadedfile);
                }
                list($width, $height) = getimagesize($uploadedfile);
                $newwidth1 = 100;
                $newheight1 = 100;
                $tmp1 = imagecreatetruecolor($newwidth1, $newheight1);
                if ($extension == "png") {

                    $kek1 = imagecolorallocate($tmp1, 255, 255, 255);
                    imagefill($tmp1, 0, 0, $kek1);
                }
                imagecopyresampled($tmp1, $src, 0, 0, 0, 0, $newwidth1, $newheight1, $width, $height);
                $dir_name = JPATH_SITE . DS . "images" . DS . "appthaeventz";
                if (!is_dir($dir_name)) {
                    mkdir($dir_name);
                }
                $file1 = "thumb_event_" . $ran . ".png";
                $filedb = "event_" . $ran . ".png";
                $filename1 = $dir_name . DS . $file1;
                imagejpeg($tmp1, $filename1, 100);
                //main image
                list($width, $height) = getimagesize($uploadedfile);
                $newwidth2 = 500;
                $newheight2 = 500;
                $tmp2 = imagecreatetruecolor($newwidth2, $newheight2);
                if ($extension == "png") {

                    $kek1 = imagecolorallocate($tmp2, 255, 255, 255);
                    imagefill($tmp2, 0, 0, $kek1);
                }
                imagecopyresampled($tmp2, $src, 0, 0, 0, 0, $newwidth2, $newheight2, $width, $height);
                $dir_name = JPATH_SITE . DS . "images" . DS . "appthaeventz";
                if (!is_dir($dir_name)) {
                    mkdir($dir_name);
                }
                $file2 = "event_" . $cid . ".png";
                $filename2 = $dir_name . DS . $file2;
                imagejpeg($tmp2, $filename2, 100);

                imagedestroy($tmp1);
                imagedestroy($tmp2);
                return $filedb;
            }
        }
    }

    function multiuploads() {
        $cid = JRequest::getvar('cid', null, 'GET');
        $file = JRequest::getVar('multiphoto', '', 'files', 'ARRAY');
        for ($i = 0; $i < count($file['name']); $i++) {
            if ($file['name'][$i] != '') {
                $userImage = JFile::makeSafe($file['name'][$i]);
                //image converting code
                $image = JFile::makeSafe($file['name'][$i]);
                $uploadedfile = $file['tmp_name'][$i];
                $userImageDetails = pathinfo($image);
                $extension = strtolower($userImageDetails['extension']);
                $events_image = $userImageDetails['basename'];
                if (($extension == "jpg") || ($extension == "jpeg") || ($extension == "png") || ($extension == "gif")) {

                    $size = filesize($file['tmp_name'][$i]);
                    if ($extension == "jpg" || $extension == "jpeg") {
                        $uploadedfile = $file['tmp_name'][$i];
                        $src = imagecreatefromjpeg($uploadedfile);
                    } else if ($extension == "png") {
                        $uploadedfile = $file['tmp_name'][$i];
                        $src = imagecreatefrompng($uploadedfile);
                    } else if ($extension == "gif") {
                        $uploadedfile = $file['tmp_name'][$i];
                        $src = imagecreatefromgif($uploadedfile);
                    }
                    $ran = rand(10, 1000);
                    list($width, $height) = getimagesize($uploadedfile);
                    $newwidth1 = 100;
                    $newheight1 = 100;
                    $tmp1 = imagecreatetruecolor($newwidth1, $newheight1);
                    if ($extension == "png") {

                        $kek1 = imagecolorallocate($tmp1, 255, 255, 255);
                        imagefill($tmp1, 0, 0, $kek1);
                    }
                    imagecopyresampled($tmp1, $src, 0, 0, 0, 0, $newwidth1, $newheight1, $width, $height);
                    $dir_name = JPATH_SITE . DS . "images" . DS . "appthaeventz";
                    if (!is_dir($dir_name)) {
                        mkdir($dir_name);
                    }
                    $file1 = "thumb_files_event_" . $ran . ".png";
                    $filedb = "files_event_" . $ran . ".png";
                    $filename1 = $dir_name . DS . $file1;
                    imagejpeg($tmp1, $filename1, 100);
                    //main image
                    list($width, $height) = getimagesize($uploadedfile);
                    $newwidth2 = 500;
                    $newheight2 = 500;
                    $tmp2 = imagecreatetruecolor($newwidth2, $newheight2);
                    if ($extension == "png") {

                        $kek1 = imagecolorallocate($tmp2, 255, 255, 255);
                        imagefill($tmp2, 0, 0, $kek1);
                    }
                    imagecopyresampled($tmp2, $src, 0, 0, 0, 0, $newwidth2, $newheight2, $width, $height);
                    $dir_name = JPATH_SITE . DS . "images" . DS . "appthaeventz";
                    if (!is_dir($dir_name)) {
                        mkdir($dir_name);
                    }
                    $file2 = "files_event_" . $ran . ".png";
                    $filename2 = $dir_name . DS . $file2;
                    imagejpeg($tmp2, $filename2, 100);

                    imagedestroy($tmp1);
                    imagedestroy($tmp2);
                    if ($filename1):
                        $db = JFactory::getDBO();

                        $db->setQuery("INSERT INTO #__em_events_images
                (event_id,image_name)
                VALUES
                ('$cid','$filedb')");
                        $db->query();
                        $db->getErrorMsg();

                    endif;
                }
            }
        }
    }

    function oneImage() { //select event image
        $db = JFactory::getDBO();
        $id = JRequest::getvar('cid', null, 'GET');
        if ($id):

            /*             * **********build query********** */

            $query = "SELECT *
                 From #__em_events_images
                 where event_id = " . $id;

            $db->setQuery($query);
            $result = $db->loadObjectList();
            $db->getErrorMsg();
            return $result;
        endif;
    }

    function eventTickets() { //select event tickets
        $db = JFactory::getDBO();
        $id = JRequest::getvar('cid', null, 'GET');
        if ($id):

            /*             * **********build query********** */

            $query = "SELECT *
                 FROM #__em_tickets
                 WHERE event_id= $id";

            $db->setQuery($query);
            $result = $db->loadObjectList();
            $db->getErrorMsg();
            return $result;
        endif;
    }

    function oneLocation() { //select event image
        $db = JFactory::getDBO();
        $id = JRequest::getvar('cid', null, 'GET');
        if ($id):

            /*             * **********build query********** */

            $query = "SELECT *
                 From #__em_locations
                 where event_id = " . $id;

            $db->setQuery($query);
            $result = $db->loadObject();
            $db->getErrorMsg();
            return $result;
        endif;
    }

    function getSetting() {

        $db = JFactory::getDBO();
        /*         * **********build query********** */
        $query = "SELECT *
                 From #__em_eventsettings";

        $db->setQuery($query);
        $result = $db->loadObject();
        $db->getErrorMsg();
        return $result;
    }

    function saveLocation() {
        $db = JFactory::getDBO();
        $cid = JRequest::getvar('cid', null, 'GET');
        $location_name = JRequest::getvar('location_name', null, 'post');
        $address = JRequest::getvar('address', null, 'post');
        $lat = JRequest::getvar('lat', null, 'post');
        $lng = JRequest::getvar('lng', null, 'post');
    }

    function getTag() {
        $db = JFactory::getDBO();

        /*         * **********build query********** */

        $query = "SELECT tag_name as label
                 From #__em_tags GROUP BY tag_name ";
        $db->setQuery($query);
        $result = $db->loadAssocList();
        $db->getErrorMsg();
        echo $json = json_encode($result);
    }

    function getAllTag() {
        $db = JFactory::getDBO();
        $cid = JRequest::getInt('cid', null, 'get');
        /*         * **********build query********** */

        $query = "SELECT *
                 From #__em_tags
                 WHERE event_id=$cid";
        $db->setQuery($query);
        $result = $db->loadAssocList();
        return $result;
    }

    function imageDelete() {

        $db = JFactory::getDBO();
        $cid = JRequest::getInt('cid', null, 'get');
        $id = JRequest::getInt('id', null, 'get');
        $db->setQuery("DELETE FROM #__em_events_images
                   WHERE event_id='$cid'
                   AND image_id='$id'");

        $db->query();
    }

    function recurring() {
        $db = JFactory::getDBO();
        $start = JRequest::getVar('start', null, 'get');
        $end_repeat = JRequest::getVar('end_repeat', null, 'get');
        $repeat_type = JRequest::getVar('repeat_type', null, 'get');
        $repeat_interval = JRequest::getVar('repeat_interval', null, 'get');
        $str_start = strtotime($start);
        $str_end_repeat = strtotime($end_repeat);
        if ($str_start > $str_end_repeat) {
            echo "End repeat Date Cannot be less than Start Date";
        } else {
            $diff = $str_end_repeat - $str_start;
            $days = (floor(($diff / (60 * 60 * 24))) + 2);
            $j = 0;
            for ($i = 0; $i < $days;) {
                $interval = $repeat_interval + $i;
                $starting = date("Y-m-d", strtotime($start . " + $interval $repeat_type"));

                if ($str_end_repeat >= strtotime($starting . " + $repeat_interval $repeat_type")) {
                    $final_starting[] = $starting;
                    $ending = date("Y-m-d", strtotime($starting . " + $repeat_interval $repeat_type"));
                    $final_ending[] = $ending;
                    $j++;
                }

                $i = $i + $repeat_interval;
            }

            if (isset($final_starting))
                $final_startdate = implode(",", $final_starting);
            else
                $final_startdate = "";
            if (isset($final_ending))
                $final_enddate = implode(",", $final_ending);
            else
                $final_enddate = "";
            echo "This event is repeating " . $j . " times";
            echo "<input name='event_times' type='hidden' value='" . $j . "'>";
            echo "<input name='final_startdate' type='hidden' value='" . $final_startdate . "'>";
            echo "<input name='final_enddate' type='hidden' value='" . $final_enddate . "'>";
        }
    }

    function checkCategory() {
        $db = JFactory::getDBO();
        $name = JRequest::getvar('name', null, 'post');

        /*         * **********build query********** */

        $query = "SELECT count(id)
                 From #__em_categories
                 WHERE name='$name'";
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

}