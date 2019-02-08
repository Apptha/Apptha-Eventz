<?php
/**
 * This file contains config info for the sample app.
 */

// Adjust this to point to the Authorize.Net PHP SDK
require_once 'anet_php_sdk/AuthorizeNet.php';


        $METHOD_TO_USE = "AIM";
     // $METHOD_TO_USE = "DIRECT_POST";         // Uncomment this line to test DPM

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query = "SELECT api,transaction_key,mode FROM #__em_authorized_settings";
        //execute query
        $db->setQuery($query);
        $result = $db->loadObject();
        $authorizeLogin=isset($result->api)?$result->api:'';
        $authorizeKey=isset($result->transaction_key)?$result->transaction_key:'';
        $authorizeMode=isset($result->mode)?$result->mode:'';
        if($authorizeMode =='0')
            $authorizeMode=false;
        else
            $authorizeMode=true;
        define("AUTHORIZENET_API_LOGIN_ID","$authorizeLogin");    // Add your API LOGIN ID
        define("AUTHORIZENET_TRANSACTION_KEY","$authorizeKey"); // Add your API transaction key
        define("AUTHORIZENET_SANDBOX",$authorizeMode);       // Set to false to test against production
        define("TEST_REQUEST", "TRUE");           // You may want to set to true if testing against production


// You only need to adjust the two variables below if testing DPM
define("AUTHORIZENET_MD5_SETTING","");                // Add your MD5 Setting.
    $site_root = JURI::base(); // Add the URL to your site


//if (AUTHORIZENET_API_LOGIN_ID == "") {
//    die('Enter your merchant credentials in config.php before running the sample app.');
//}
