<?php

class ProductValidations extends Utils  {
    public $arrobjProductPostData;
    public $arrBadData = array();
    public $arrErrorCodes = array();
    public $arrResponse = array();
    public $strHandle;
    public $boolIsValidType = true;
    public $boolIsValid = true;
    public $strPricePattern = '/^\d+(?:\.\d{2})?$/';

    public function validateProductData($objProducts , $strHandle) {
       $arrResponse = array();
       $this->strHandle = $strHandle;
       $this->arrobjProductPostData = $objProducts;
       $this->validateProductAttributes();
       $this->validateProductAttributeTypeCast();
       
       $arrResponse['status'] = true;
       $arrResponse['clean_data'] = $this->arrobjProductPostData;
       $arrResponse['bad_data'] = $this->arrBadData;
       $arrResponse['error_codes'] = $this->arrErrorCodes;
       if(count($this->arrobjProductPostData) == 0){
           $arrResponse['status'] = false;
       }
       return $arrResponse;
    }
    public function validateProductAttributes(){
        switch ($this->strHandle) {
            
            case 'addProduct':
                $arrProductRequiredAttributes = array(
                    'name',
                    'description',
                    'price',
                    'discount',
                    'category_id'
                );
                foreach ($this->arrobjProductPostData as $intKey => $arrProduct) {
                    if (count(array_intersect_key(array_flip($arrProductRequiredAttributes), $arrProduct)) != count($arrProductRequiredAttributes)) {
                        $this->arrBadData[] = $arrProduct;
                        unset($this->arrobjProductPostData[$intKey]);
                    }
                }
                if (count($this->arrBadData)) {
                    $this->arrErrorCodes[] = 'MISSING_REQ_ATTR';
                }
                
            break;
            case 'updateProduct':
                $arrProductRequiredAttributes = array(
                    'id',
                    'data'
                );
                if (count(array_intersect_key(array_flip($arrProductRequiredAttributes), $this->arrobjProductPostData)) != count($arrProductRequiredAttributes)) {
                    $this->arrBadData[] = $arrProduct;
                    $this->arrErrorCodes[] = 'MISSING_REQ_ATTR';
                    unset($this->arrobjProductPostData);
                }
                
            break;
            case 'deleteProduct':
                $arrRequiredAttributes = array(
                    'id',
                );
                if (count(array_intersect_key(array_flip($arrRequiredAttributes), $this->arrobjProductPostData)) != count($arrRequiredAttributes)) {
                    $this->arrBadData[] = $arrProduct;
                    $this->arrErrorCodes[] = 'MISSING_REQ_ATTR';
                    unset($this->arrobjProductPostData);
                }
                
            break;
            default:
                break;
        }
        
        
        
    }
    public function validateProductAttributeTypeCast(){
        
        switch ($this->strHandle) {
            case 'addProduct':
                foreach ($this->arrobjProductPostData as $intKey => $arrProduct) {
                    // Check of Empty Value 
                   
                    if( empty($arrProduct['name'])|| empty($arrProduct['price']) || empty($arrProduct['description']) ||  empty($arrProduct['description'] )){
                        $this->boolIsValid = false;
                        $this->arrErrorCodes[] = 'ATTR_VALUE_EMPTY';
                        
                    }
                    if (preg_match($this->strPricePattern, $arrProduct['price']) == '0') {
                        $this->boolIsValid = false;
                        $this->arrErrorCodes[] = 'INVALID_FORMAT_PRICE';
                    }
                    if (preg_match($this->strPricePattern, $arrProduct['discount']) == '0') {
                        $this->boolIsValid = false;
                        $this->arrErrorCodes[] = 'INVALID_FORMAT_DISCOUNT';
                    }
                    if (!$this->boolIsValid) {
                        $this->arrBadData[] = $arrProduct;
                        unset($this->arrobjProductPostData[$intKey]);
                    }
                }
                
                $this->arrErrorCodes = array_unique($this->arrErrorCodes);
                
            break;
            
            case 'updateProduct':
               $arrProduct = $this->arrobjProductPostData['data']; 
                    // Check of Empty Value 
                   if(isset($arrProduct['price'])){
                        if(empty($arrProduct['price'])){
                            $this->boolIsValid = false;
                            $this->arrErrorCodes[] = 'ATTR_VALUE_EMPTY';
                        }else{
                            if (preg_match($this->strPricePattern, $arrProduct['price']) == '0') {
                                $this->boolIsValid = false;
                                $this->arrErrorCodes[] = 'INVALID_FORMAT_PRICE';
                            }
                        }
                    }
                    if(isset($arrProduct['name'])){
                        if(empty($arrProduct['name'])){
                            $this->boolIsValid = false;
                            $this->arrErrorCodes[] = 'ATTR_VALUE_EMPTY';
                        }
                    }
                    if(isset($arrProduct['discount'])){
                        if(empty($arrProduct['discount'])){
                            $this->boolIsValid = false;
                            $this->arrErrorCodes[] = 'ATTR_VALUE_EMPTY';
                        }else{
                            if (preg_match($this->strPricePattern, $arrProduct['discount']) == '0') {
                                $this->boolIsValid = false;
                                $this->arrErrorCodes[] = 'INVALID_FORMAT_DISCOUNT';
                            }
                            if(100 < $arrProduct['discount'] ){
                                $this->boolIsValid = false;
                                $this->arrErrorCodes[] = 'DISCOUNT_EXCEED';
                            }
                        }
                    }
                    if(isset($arrProduct['category_id'])){
                        if(empty($arrProduct['category_id'])){
                            $this->boolIsValid = false;
                            $this->arrErrorCodes[] = 'ATTR_VALUE_EMPTY';
                        }else{
                            if (!filter_var($arrProduct['category_id'], FILTER_VALIDATE_INT)) {
                            $this->boolIsValid = false;
                            $this->arrErrorCodes[] = 'INVALID_CATEGORY';
                        }
                    }
                    }
                    if (!$this->boolIsValid) {
                        $this->arrBadData[] = $arrProduct;
                        $this->arrobjProductPostData = array();
                    }
                    $this->arrErrorCodes = array_unique($this->arrErrorCodes);
            break;
            
            case 'deleteProduct':
               $arrProduct = $this->arrobjProductPostData;
                    // Check of Empty Value 
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
                        $this->arrobjProductPostData = array();
                    }
                    $this->arrErrorCodes = array_unique($this->arrErrorCodes);
            break;
            
            default:
                break;
        }
    }

}

?>