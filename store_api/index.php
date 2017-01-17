<?php
include 'includes/config.php';
function postDataToApi($strMethod, $arrPostData = array()) {
    if (empty($strMethod)) {
        die('Please Pass API Method');
    }
    $strPostUrl = API_POST_URL;
    if (!empty($strMethod)) {
        $strPostUrl.= $strMethod;
    }
    $objCurl = curl_init($strPostUrl);
    $cookieFile = COOKIE_FILE_NAME;
    # Setup request to send json via POST.
    $jsonPostData = json_encode($arrPostData);
    curl_setopt($objCurl, CURLOPT_POSTFIELDS, $jsonPostData);
    curl_setopt($objCurl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    # Return response instead of printing.
    curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($objCurl,CURLOPT_COOKIEJAR, $cookieFile);  //tell cUrl where to write cookie data
    curl_setopt($objCurl,CURLOPT_COOKIEFILE, $cookieFile); //tell cUrl where to read cookie data from
    # Send request.
    $jsonResult = curl_exec($objCurl);
    curl_close($objCurl);
    # Print response.
    echo "<pre>$jsonResult</pre>";
}

$strMethod = 'products/getProducts';
postDataToApi($strMethod);
?>                                                        
                                                                                   
                                                                                         