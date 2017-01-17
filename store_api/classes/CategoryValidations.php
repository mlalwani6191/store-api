<?php

class CategoryValidations extends Utils  {
    public $arrobjPostData;
    public $arrBadData = array();
    public $arrErrorCodes = array();
    public $arrResponse = array();
    public $strHandle;
    public $boolIsValidType = true;
    public $boolIsValid = true;
    public $strPricePattern = '/^\d+(?:\.\d{2})?$/';

    public function validateCategoryData($arrcategories , $strHandle) {
       $arrResponse = array();
       $this->strHandle = $strHandle;
       $this->arrobjPostData = $arrcategories;
       $this->validateAttributes();
       $this->validateAttributeTypeCast();
       
       $arrResponse['status'] = true;
       $arrResponse['clean_data'] = $this->arrobjPostData;
       $arrResponse['bad_data'] = $this->arrBadData;
       $arrResponse['error_codes'] = $this->arrErrorCodes;
       if(count($this->arrobjPostData) == 0){
           $arrResponse['status'] = false;
       }
       return $arrResponse;
    }
    private function validateAttributes(){
        switch ($this->strHandle) {
            
            case 'addCategory':
                $arrRequiredAttributes = array(
                    'name',
                    'description',
                    'tax'
                );
                foreach ($this->arrobjPostData as $intKey => $arrCategory) {
                    if (count(array_intersect_key(array_flip($arrRequiredAttributes), $arrCategory)) != count($arrRequiredAttributes)) {
                        $this->arrBadData[] = $arrProduct;
                        unset($this->arrobjPostData[$intKey]);
                    }
                }
                if (count($this->arrBadData)) {
                    $this->arrErrorCodes[] = 'MISSING_REQ_ATTR';
                }
                
            break;
            case 'updateCategory':
                $arrRequiredAttributes = array(
                    'id',
                    'data'
                );
                if (count(array_intersect_key(array_flip($arrRequiredAttributes), $this->arrobjPostData)) != count($arrRequiredAttributes)) {
                    $this->arrBadData[] = $arrProduct;
                    $this->arrErrorCodes[] = 'MISSING_REQ_ATTR';
                    unset($this->arrobjProductPostData);
                }
                
            break;
            
            case 'deleteCategory':
                $arrRequiredAttributes = array(
                    'id',
                );
                if (count(array_intersect_key(array_flip($arrRequiredAttributes), $this->arrobjPostData)) != count($arrRequiredAttributes)) {
                    $this->arrBadData[] = $arrProduct;
                    $this->arrErrorCodes[] = 'MISSING_REQ_ATTR';
                    unset($this->arrobjProductPostData);
                }
                
            break;

            default:
                break;
        }
        
        
        
    }
    public function validateAttributeTypeCast(){
        
        switch ($this->strHandle) {
            case 'addCategory':
                foreach ($this->arrobjPostData as $intKey => $arrCategory) {
                    // Check of Empty Value 
                   
                    if( empty($arrCategory['name']) || empty($arrCategory['description']) || empty($arrCategory['tax']) ){
                        if($arrCategory['tax'] != 0 ){
                            $this->boolIsValid = false;
                            $this->arrErrorCodes[] = 'ATTR_VALUE_EMPTY';
                        }
                        
                        
                    }
                    if (preg_match($this->strPricePattern, $arrCategory['tax']) == '0') {
                        $this->boolIsValid = false;
                        $this->arrErrorCodes[] = 'INVALID_FORMAT_TAX';
                    }
                    if (!$this->boolIsValid) {
                        $this->arrBadData[] = $arrCategory;
                        unset($this->arrobjPostData[$intKey]);
                    }
                }
                
                $this->arrErrorCodes = array_unique($this->arrErrorCodes);
                
            break;
            
            case 'updateCategory':
               $arrProduct = $this->arrobjPostData['data']; 
                    // Check of Empty Value 
                   if(isset($arrProduct['tax'])){
                        if(empty($arrProduct['tax'])){
                            $this->boolIsValid = false;
                            $this->arrErrorCodes[] = 'ATTR_VALUE_EMPTY';
                        }else{
                            if (preg_match($this->strPricePattern, $arrProduct['tax']) == '0') {
                                $this->boolIsValid = false;
                                $this->arrErrorCodes[] = 'INVALID_FORMAT_TAX';
                            }
                        }
                    }
                    if(isset($arrProduct['name'])){
                        if(empty($arrProduct['name'])){
                            $this->boolIsValid = false;
                            $this->arrErrorCodes[] = 'ATTR_VALUE_EMPTY';
                        }
                    }
                    
                    if (!$this->boolIsValid) {
                        $this->arrBadData[] = $arrProduct;
                        $this->arrobjPostData = array();
                    }
                    $this->arrErrorCodes = array_unique($this->arrErrorCodes);
            break;
            
            case 'deleteCategory':
               $arrProduct = $this->arrobjPostData;
                    // Check of Empty Value 
                   if(isset($arrProduct['id'])){
                        if(empty($arrProduct['id'])){
                            $this->boolIsValid = false;
                            $this->arrErrorCodes[] = 'ATTR_VALUE_EMPTY';
                        }else{
                            if (!filter_var($arrProduct['id'], FILTER_VALIDATE_INT)) {
                            $this->boolIsValid = false;
                            $this->arrErrorCodes[] = 'INVALID_CATEGORY';
                        }
                    }
                    }
                    
                    if (!$this->boolIsValid) {
                        $this->arrBadData[] = $arrProduct;
                        $this->arrobjPostData = array();
                    }
                    $this->arrErrorCodes = array_unique($this->arrErrorCodes);
            break;

            default:
                break;
        }
    }

}

?>