<?php
include 'includes/config.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST'); 
$arrRequest = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$arrResults = array();
$objRouting = new Route($objDatabase);
$arrResponse = $objRouting->setRouting($arrRequest);
if (true == $arrResponse['status']) {
    $strMethodName = $arrResponse['method'];
    $strClassName = $arrResponse['class'];
    $boolHasParams = $arrResponse['has_params'];
    $objPostData = '';
    if($boolHasParams){
         $objPostData = json_decode($arrResponse['postdata'],1);
    }
    $objApi = new $strClassName($objDatabase);
    $arrResults = $objApi->$strMethodName($objPostData);
    echo json_encode($arrResults);
    //
} else {
    echo json_encode($arrResponse);
}
?>