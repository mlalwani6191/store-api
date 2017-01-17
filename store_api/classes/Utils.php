<?php
class Utils extends Sessions{
    
    public $arrClassConfig = array(
        'Products' => array(
            'methods' => array(
                array(
                    'name' =>'addProduct',
                    'has_params' => true,
                    'input_type'=>'MULTI'
                ),
                array(
                    'name' =>'updateProduct',
                    'has_params' => true,
                    'input_type'=>'SINGLE'
                ),
                array(
                    'name' =>'deleteProduct',
                    'has_params' => true,
                    'input_type'=>'SINGLE'
                ),
                array(
                    'name' =>'getProducts',
                    'has_params' => false,
                    'input_type'=>'NA'
                ),
                
            ),
        ),
        'Category' => array(
            'methods' => array(
                array(
                    'name' =>'addCategory',
                    'has_params' => true,
                    'input_type'=>'MULTI'
                ),
                array(
                    'name' =>'updateCategory',
                    'has_params' => true,
                    'input_type'=>'SINGLE'
                ),
                array(
                    'name' =>'deleteCategory',
                    'has_params' => true,
                    'input_type'=>'SINGLE'
                ),
                array(
                    'name' =>'getCategories',
                    'has_params' => false,
                    'input_type'=>'NA'
                ),
                
            ),
        ),
        
        'Cart' => array(
            'methods' => array(
                array(
                    'name' =>'addToCart',
                    'has_params' => true,
                    'input_type'=>'MULTI'
                ),
                array(
                    'name' =>'updateCart',
                    'has_params' => true,
                    'input_type'=>'SINGLE'
                ),
                array(
                    'name' =>'removeFromCart',
                    'has_params' => true,
                    'input_type'=>'SINGLE'
                ),
                array(
                    'name' =>'getCart',
                    'has_params' => false,
                    'input_type'=>'NA'
                ),
                array(
                    'name' =>'clearCart',
                    'has_params' => false,
                    'input_type'=>'NA'
                ),
                array(
                    'name' =>'getCartTotal',
                    'has_params' => false,
                    'input_type'=>'NA'
                ),
                array(
                    'name' =>'getCartTotalDiscount',
                    'has_params' => false,
                    'input_type'=>'NA'
                ),
                array(
                    'name' =>'getCartTotalTax',
                    'has_params' => false,
                    'input_type'=>'NA'
                ),
                
                
            )
            
        )
    );
    
    
    public $arrError = array(
        'INVALID_METHOD'=>array(
            'code' =>1001,
            'description' =>'Invalid Method Passed,Please refer to API documentation '
        ),
        'NO_CLASS'=>array(
            'code' =>1002,
            'description' =>'Invalid / No Class Name Specified, Pleaase refer to API documentation '
        ),
        'NO_CLASS_EXIST'=>array(
            'code' =>1003,
            'description' =>'Specified Class Does Not Exist, Pleaase refer to API documentation '
        ),
        'NO_METHOD'=>array(
            'code' =>1004,
            'description' =>'Invalid / No Method Name Specified, Pleaase refer to API documentation '
        ),
        'MISSING_REQ_ATTR'=>array(
            'code' =>1005,
            'description' =>'Missing Required Attributes, Pleaase refer to API documentation '
        ),
        'INVALID_DATA_POSTED'=>array(
            'code' =>1005,
            'description' =>'Invalid Data Posted , Please refer to API documentation '
        ),
        'ATTR_VALUE_EMPTY'=>array(
            'code' =>1006,
            'description' =>'Required Data for Specified Attributes Not Passed, Please refer to API documentation '
        ),
        'DISCOUNT_EXCEED'=>array(
            'code' =>1007,
            'description' =>'Discount Value Exceeds 100 , Please refer to API documentation '
        ),
        'INVALID_CATEGORY'=>array(
            'code' =>1008,
            'description' =>'Invalid Category Passed , Please refer to API documentation '
        ),
        'INVALID_FORMAT_PRODUCT_ID'=>array(
            'code' =>1009,
            'description' =>'Invalid Formmat For Product Id Passed , Please refer to API documentation '
        ),
        'INVALID_FORMAT_PRODUCT_QTY'=>array(
            'code' =>1010,
            'description' =>'Invalid Format for Product Quantity Passed , Please refer to API documentation '
        ),
        'INVALID_FORMAT_TAX' => array(
            'code' => 1011,
            'description' => 'Invalid Format for TAX Passed , Please refer to API documentation '
        ),
        'DEFAULT'=>array(
            'code' =>1000,
            'description' =>'OOPS...!, An error occured accessing API, Please contact Admin.'
        )
    );
    
    public function getError($strErrorCode){
        $arrErrors = $this->arrError;
        if(isset($arrErrors[$strErrorCode])){
            return $arrErrors[$strErrorCode] ;   
        }
         return $arrErrors['DEFAULT'];   
    }
    public function isValidJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    public function getConfig(){
       return $this->arrClassConfig; 
    }

}
?>