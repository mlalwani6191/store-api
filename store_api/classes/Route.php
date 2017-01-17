<?php
class Route extends Utils{
    public $strMethod;
    public function setRouting($arrRequest){
        $arrResponse = $this->validateRequest($arrRequest);
        return $arrResponse;
    }
    public function validateRequest($arrRequest) {
        $arrResponse = array();
        $arrClassConfig = $this->getConfig();
        $boolIsValidMethod = false;
        if (!isset($arrRequest[0])) {
            $arrResponse['status'] = false;
            $arrResponse['msg'] = $this->getError('NO_CLASS');
            return $arrResponse;
        }
        if (!isset($arrRequest[1])) {
            $arrResponse['status'] = false;
            $arrResponse['msg'] = $this->getError('NO_METHOD');
            return $arrResponse;
        }
        
        $strClassName = ucfirst(strtolower($arrRequest[0]));
        $strMethodName = $arrRequest[1];
        if(!file_exists("classes/".$strClassName.".php")){
           $arrResponse['status'] = false;
           $arrResponse['msg'] = $this->getError('NO_CLASS_EXIST');
           return $arrResponse; 
        }
        
        foreach($arrClassConfig[$strClassName]['methods'] as $arrConfigItems){
            if($arrConfigItems['name'] == $strMethodName ){
                $boolIsValidMethod = true;
                $arrResponse['status'] = TRUE;
                $arrResponse['method'] = $strMethodName;
                $arrResponse['class'] = $strClassName;
                $arrResponse['has_params'] = $arrConfigItems['has_params'];
                $arrResponse['input_type'] = $arrConfigItems['input_type'];
                $this->strMethod = $strMethodName;
                break;
            }
        }
        if(!$boolIsValidMethod){
           $arrResponse['staus'] = false;
           $arrResponse['msg'] = $this->getError('INVALID_METHOD');
           return $arrResponse;  
        }
        if($arrResponse['has_params']){
            $jsonPostData = file_get_contents("php://input");
            if('MULTI' == $arrResponse['input_type']){
                if($this->is_multi($jsonPostData)){
                    if (!$this->isValidJson($jsonPostData)) {
                        $jsonRequest = iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode($jsonRequest));
                        $arrResponse['postdata'] = $jsonRequest;
                    } else {
                        $arrResponse['postdata'] = $jsonPostData;
                    }
            }else{
               $arrResponse['status'] = false;
               $arrResponse['msg'] = $this->getError('INVALID_DATA_POSTED'); 
            }
            }else{
                // Product Update Method 
                if('updateProduct' == $this->strMethod ){

                    if($this->is_multi($jsonPostData)){
                      $arrResponse['status'] = false;
                      $arrResponse['msg'] = $this->getError('INVALID_DATA_POSTED');
                    }
                }
                $arrResponse['postdata'] = $jsonPostData;  
            }
          
        }
        return $arrResponse;
    }
    function is_multi($jsonPostData) {
        switch ($this->strMethod) {
            case 'updateProduct':
                $arrDecodedData = json_decode($jsonPostData, 1);
                if (isset($arrDecodedData['data'])) {
                    $arrPostData = $arrDecodedData['data'];
                } else {
                    return true;
                }
                break;
            default:
                $arrPostData = json_decode($jsonPostData, 1);
                break;
        }
        
        $rv = array_filter($arrPostData, 'is_array');
        if (count($rv) > 0){
            return true;
        }
        return false;
    }

}
?>