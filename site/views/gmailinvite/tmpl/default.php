
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
$gmailclient = $this->gmailclient;
?>
<style type="text/css">
.invite_wrap{overflow: hidden;}
</style>
<div class="invite_form">
<?php $redirect = Juri::base()."index.php?option=com_appthaeventz&view=gmailinvite";
         $redirect = urlencode($redirect); ?>
   <!-- <a href='https://accounts.google.com/o/oauth2/auth?client_id=300381478182.apps.googleusercontent.com&redirect_uri=<?php echo $redirect; ?>&scope=https://www.google.com/m8/feeds/&response_type=code'><input type="button" value="Gmail Connect"/></a> -->
    <div class="invite_wrap"> <h2><?php echo JText::_('EVENTS_INVITE_FRIENDS_FROM_GMAIL'); ?></h2> </div>


<?php
//##---> Create URL
//		$url = "https://login.yahoo.com/config/login?";
//		$query_string = ".tries=2&.src=ym&.md5=&.hash=&.js=&.last=&promo=&.intl=us&.bypass=&.partner=&.u=4eo6isd23l8r3&.v=0&.challenge=gsMsEcoZP7km3N3NeI4mXkGB7zMV&.yplus=&.emailCode=&pkg=&stepid=&.ev=&hasMsgr=1&.chkP=Y&.done=http%3A%2F%2Fmail.yahoo.com&login=newdjdiearzte123@yahoo.com&passwd=newdjdiearzte_123";
//		$url_login = $url . $query_string;
//		##---> End Create URL
//
//		##---> Execute Curl For Login
//		$ch = curl_init();
//		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//		curl_setopt($ch, CURLOPT_URL, $url_login);
//		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
//		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
//		curl_setopt($ch, CURLOPT_HEADER , 1);
//		ob_start();
//		$response = curl_exec ($ch);
//		ob_end_clean();
//		curl_close ($ch);
//		unset($ch);
//		##---> End Execute Curl For Login
//
//		##---> Call Address Book Page Through Curl
//		$url_addressbook = "http://address.yahoo.com/yab/us";
//		$ch = curl_init();
//		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
//		curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
//		curl_setopt($ch, CURLOPT_HEADER , 1);
//		curl_setopt($ch, CURLOPT_URL, $url_addressbook);
//		$result = curl_exec ($ch);
//		curl_close ($ch);
//		unset($ch);
//		##---> End Call Address Book Page Through Curl
//
//		##---> Manuplate String
//		$result = preg_replace("([\r\n\t])", " ", $result);
//		$result = strip_tags($result);
//		$arr_result = explode(" ", $result);
//
//		$arr_filter = array();
//		for($i=0; $i<sizeof($arr_result); $i++)
//		{
//			if(strpos($arr_result[$i], '@') > 0 && strpos($arr_result[$i], '.') > 0)
//			{
//				if(!in_array($arr_result[$i], $arr_filter, TRUE))
//					echo $arr_filter[] = $arr_result[$i];
//			}
//		}
//		##---> End Manuplate String
//
//		##---> Return Result Array
//		return $arr_filter;
//                ##---> End Return Result Array
?>
       
<?php

//setting parameters
$authcode= $_GET["code"];
$clientid = $gmailclient->googleclient;
$clientsecret=$gmailclient->googlesecret;
$redirecturi=JURI::base().'index.php?option=com_appthaeventz&view=gmailinvite';
$fields=array(
    'code'=>  urlencode($authcode),
    'client_id'=>  urlencode($clientid),
    'client_secret'=>  urlencode($clientsecret),
    'redirect_uri'=>  urlencode($redirecturi),
    'grant_type'=>  urlencode('authorization_code')
);
//url-ify the data for the POST
$fields_string='';
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
$fields_string=rtrim($fields_string,'&');
//open connection
$ch = curl_init();
//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL,'https://accounts.google.com/o/oauth2/token');
curl_setopt($ch,CURLOPT_POST,5);
curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
// Set so curl_exec returns the result instead of outputting it.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//to trust any ssl certificates
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//execute post
$result = curl_exec($ch);
//close connection
curl_close($ch);
//extracting access_token from response string
$response=  json_decode($result);

$accesstoken= $response->access_token;
//passing accesstoken to obtain contact details
$xmlresponse=  file_get_contents('https://www.google.com/m8/feeds/contacts/default/full?oauth_token='.$accesstoken);
//reading xml using SimpleXML
$xml=  new SimpleXMLElement($xmlresponse);
$xml->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
$result = $xml->xpath('//gd:email');
?>
       <form name="inviteForm" method="post" action="<?php echo JRoute::_('index.php?option=com_appthaeventz&view=invite&event_id='.$_SESSION['event_id']) ?>"  >
<div class="invite_textarea_txt single_grid" ><?php

?><input type="hidden" name="event_id" value="<?php echo $_SESSION['event_id'];?>" ><?php
foreach ($result as $title) {
 echo "<input type='checkbox' name='emailaddress[]' value=".$title->attributes()->address.">"."&nbsp;&nbsp;".$title->attributes()->address . "<br>";
}
?>
</div>
           <button style="margin-top: 390px; margin-left: 20px;" type="submit" ><?php echo JText::_('EVENTS_SEND_INVITATION'); ?></button>
       </form>
</div>
<?php
//$session = YahooSession::requireSession(dj0yJmk9MmFwVE5Fd3NmOHh0JmQ9WVdrOVlUTnFWRVp3TjJjbWNHbzlORFEzT0RJMU9UWXkmcz1jb25zdW1lcnNlY3JldCZ4PTU4,e7e9dc8329c067a38f1ac01070ad8a543cf6bf75,a3jTFp7g);
//$query = sprintf("select * from social.contacts where guid=me;");
//$response = $session->query($query);
//
//
///**** printing the contact emails starts ****/
//if(isset($response)){
//
//   foreach($response->query->results->contact as $id){
//
//       foreach($id->fields as $subid){
//
//               if( $subid->type == 'email' )
//               echo $subid->value."<br />";
//       }
//   }
//}

//?>
<?php
//$url = "https://login.yahoo.com/config/login?";
//		$query_string = ".tries=2&.src=ym&.md5=&.hash=&.js=&.last=&promo=&.intl=us&.bypass=&.partner=&.u=4eo6isd23l8r3&.v=0&.challenge=gsMsEcoZP7km3N3NeI4mXkGB7zMV&.yplus=&.emailCode=&pkg=&stepid=&.ev=&hasMsgr=1&.chkP=Y&.done=http%3A%2F%2Fmail.yahoo.com&login=djdiearzte@yahoo.com&passwd=djdiearzte_123";
//		$url_login = $url . $query_string;
//		##---> End Create URL
//
//		##---> Execute Curl For Login
//		$ch = curl_init();
//		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//		curl_setopt($ch, CURLOPT_URL, $url_login);
//		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
//		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
//		curl_setopt($ch, CURLOPT_HEADER , 1);
//		ob_start();
//		$response = curl_exec ($ch);                
//		ob_end_clean();
//		curl_close ($ch);
//		unset($ch);
//		##---> End Execute Curl For Login
//
//		##---> Call Address Book Page Through Curl
//		$url_addressbook = "http://address.yahoo.com/yab/us";
//		$ch = curl_init();
//		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
//		curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
//		curl_setopt($ch, CURLOPT_HEADER , 1);
//		curl_setopt($ch, CURLOPT_URL, $url_addressbook);
//		$result = curl_exec ($ch);
//               // print_r($result);
//		curl_close ($ch);
//		unset($ch);
//		##---> End Call Address Book Page Through Curl
//
//		##---> Manuplate String
//		$result = preg_replace("([\r\n\t])", " ", $result);
//		$result = strip_tags($result);
//		$arr_result = explode(" ", $result);
//
//		$arr_filter = array();
//		for($i=0; $i<sizeof($arr_result); $i++)
//		{
//			if(strpos($arr_result[$i], '@') > 0 && strpos($arr_result[$i], '.') > 0)
//			{
//				if(!in_array($arr_result[$i], $arr_filter, TRUE))
//                                
//                               //$arr_result[$i] =  explode(",",  $arr_result[$i]);
//                              //print_r($arr_result[$i]);
//                              //print_r(explode(":",  $arr_result[$i]['1']));
//                              //$arr_result[$i] = explode(":",  $arr_result[$i]['1']);
//                             // echo $email = $arr_result[$i]['1']."<br/>";
//				echo $arr_filter[] = $arr_result[$i];
//                                 
//
//			
//                        }
//		}
//		##---> End Manuplate String
//
//		##---> Return Result Array
//		//return $arr_filter;
//		##---> End Return Result Array
//?>
<?php
//function yahoo_login($email_id, $password)
//	{
//		##---> Create URL
//		$url = "https://login.yahoo.com/config/login?";
//		$query_string = ".tries=2&.src=ym&.md5=&.hash=&.js=&.last=&promo=&.intl=us&.bypass=&.partner=&.u=4eo6isd23l8r3&.v=0&.challenge=gsMsEcoZP7km3N3NeI4mXkGB7zMV&.yplus=&.emailCode=&pkg=&stepid=&.ev=&hasMsgr=1&.chkP=Y&.done=http%3A%2F%2Fmail.yahoo.com&login=$email_id&passwd=$password";
//		$url_login = $url . $query_string;
//		##---> End Create URL
//	
//		##---> Execute Curl For Login
//		$ch = curl_init();
//		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//		curl_setopt($ch, CURLOPT_URL, $url_login);
//		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
//		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
//		curl_setopt($ch, CURLOPT_HEADER , 1);
//		ob_start();
//		$response = curl_exec ($ch);
//		ob_end_clean();
//		curl_close ($ch);
//		unset($ch);
//		##---> End Execute Curl For Login
//	
//		##---> Call Address Book Page Through Curl
//		$url_addressbook = "http://address.yahoo.com/yab/us";
//		$ch = curl_init();
//		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
//		curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
//		curl_setopt($ch, CURLOPT_HEADER , 1);
//		curl_setopt($ch, CURLOPT_URL, $url_addressbook);
//		$result = curl_exec ($ch);
//		curl_close ($ch);
//		unset($ch);
//		##---> End Call Address Book Page Through Curl
//	
//		##---> Manuplate String
//		$result = preg_replace("([\r\n\t])", " ", $result);
//		$result = strip_tags($result);
//		$arr_result = explode(" ", $result);
//	
//		$arr_filter = array();
//		for($i=0; $i<sizeof($arr_result); $i++)
//		{
//			if(strpos($arr_result[$i], '@') > 0 && strpos($arr_result[$i], '.') > 0)
//			{
//				if(!in_array($arr_result[$i], $arr_filter, TRUE))
//					$arr_filter[] = $arr_result[$i];
//			}
//		}
//		##---> End Manuplate String
//		
//		##---> Return Result Array
//		return $arr_filter;
//		##---> End Return Result Array
//	}
//
//        $test =  yahoo_login('djdiearzte123@yahoo.com',' ');
//        echo'<pre>';print_r($test);
?>
