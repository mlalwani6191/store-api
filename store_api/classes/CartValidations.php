<?php

class CartValidations extends Utils  {
    public $arrPostData;
    public $arrBadData = array();
    public $arrErrorCodes = array();
    public $arrResponse = array();
    public $strHandle;
    public $boolIsValidType = true;
    public $boolIsValid = true;
    public $strPricePattern = '/^\d+(?:\.\d{2})?$/';

    public function validateCartData($arrCartData , $strHandle) {
       $arrResponse = array();
       $this->strHandle = $strHandle;
       $this->arrPostData = $arrCartData;
       $this->validateAttributes();
       $this->validateAttributeTypeCast();
       
       $arrResponse['status'] = true;
       $arrResponse['clean_data'] = $this->arrPostData;
       $arrResponse['bad_data'] = $this->arrBadData;
       $arrResponse['error_codes'] = $this->arrErrorCodes;
       if(count($this->arrPostData) == 0){
           $arrResponse['status'] = false;
       }
       return $arrResponse;
    }
    public function validateAttributes(){
        switch ($this->strHandle) {
            
            case 'addToCart':
                $arrRequiredAttributes = array(
                    'id',
                    'qty'
                );
                foreach ($this->arrPostData as $intKey => $arrCartData) {
                    if (count(array_intersect_key(array_flip($arrRequiredAttributes), $arrCartData)) != count($arrRequiredAttributes)) {
                        $this->arrBadData[] = $arrCartData;
                        unset($this->arrPostData[$intKey]);
                    }
                }
                if (count($this->arrBadData)) {
                    $this->arrErrorCodes[] = 'MISSING_REQ_ATTR';
                }
                
            break;
            case 'removeFromCart':
                
                $arrRequiredAttributes = array(
                    'id'
                );
                if (count(array_intersect_key(array_flip($arrRequiredAttributes), $this->arrPostData)) != count($arrRequiredAttributes)) {
                    $this->arrBadData[] = $this->arrPostData;
                    $this->arrErrorCodes[] = 'MISSING_REQ_ATTR';
                    unset($this->arrPostData);
                }
            break;
            case 'updateCart':
                $arrRequiredAttributes = array(
                    'id',
                    'qty'
                );
                if (count(array_intersect_key(array_flip($arrRequiredAttributes), $this->arrPostData[0])) != count($arrRequiredAttributes)) {
                    $this->arrBadData[] = $this->arrPostData;
                    $this->arrPostData = array();
                }
                if (count($this->arrBadData)) {
                    $this->arrErrorCodes[] = 'MISSING_REQ_ATTR';
                }
                
            break;
            

            default:
                break;
        }
        
    }
    public function validateAttributeTypeCast(){
        
        switch ($this->strHandle) {
            case 'addToCart':
                foreach ($this->arrPostData as $intKey => $arrCartData) {
                    // Check of Empty Value 
                   
                    if( empty($arrCartData['id'])|| empty($arrCartData['qty'])){
                        $this->boolIsValid = false;
                        $this->arrErrorCodes[] = 'ATTR_VALUE_EMPTY';
                        
                    }
                    if (preg_match($this->strPricePattern, $arrCartData['id']) == '0') {
                        $this->boolIsValid = false;
                        $this->arrErrorCodes[] = 'INVALID_FORMAT_PRODUCT_ID';
                    }
                    if (preg_match($this->strPricePattern, $arrCartData['qty']) == '0') {
                        $this->boolIsValid = false;
                        $this->arrErrorCodes[] = 'INVALID_FORMAT_PRODUCT_QTY';
                    }
                    if(false == $this->boolIsValid ){
                        $this->arrBadData[] = $arrCartData;
                        unset($this->arrPostData[$intKey]);
                    }
                }
                
                $this->arrErrorCodes = array_unique($this->arrErrorCodes);
                
            break;
            
            case 'removeFromCart':
               $arrProduct = $this->arrPostData; 
                    if(isset($arrProduct['id'])){
                        if(empty($arrProduct['id'])){
                            $this->boolIsValid = false;
                            $this->arrErrorCodes[] = 'ATTR_VALUE_EMPTY';
                        }else{
                            if (!filter_var($arrProduct['id'], FILTER_VALIDATE_INT)) {
                            $this->boolIsValid = false;
                            $this->arrErrorCodes[] = 'INVALID_FORMAT_PRODUCT_ID';
                        }
                    }
                    }
                    if (!$this->boolIsValid) {
                        $this->arrBadData[] = $arrProduct;
                        $this->arrPostData = array();
                    }
                    $this->arrErrorCodes = array_unique($this->arrErrorCodes);
            break;
            case 'updateCart':
                    if( empty($this->arrPostData[0]['id'])|| empty($this->arrPostData[0]['qty'])){
                        $this->boolIsValid = false;
                        $this->arrErrorCodes[] = 'ATTR_VALUE_EMPTY';
                        
                    }
                    if (!filter_var($this->arrPostData[0]['id'], FILTER_VALIDATE_INT)) {
                            $this->boolIsValid = false;
                            $this->arrErrorCodes[] = 'INVALID_FORMAT_PRODUCT_ID';
                    }
                    if (!filter_var($this->arrPostData[0]['qty'], FILTER_VALIDATE_INT)) {
                            $this->boolIsValid = false;
                            $this->arrErrorCodes[] = 'INVALID_FORMAT_PRODUCT_QTY';
                    }
                    if(false == $this->boolIsValid ){
                        $this->arrBadData[] = $this->arrPostData;
                        $this->arrPostData = array();
                    }
                $this->arrErrorCodes = array_unique($this->arrErrorCodes);
                
            break;
            default:
                break;
        }
    }

}

?>