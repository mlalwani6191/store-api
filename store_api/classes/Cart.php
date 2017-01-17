<?php
include 'classes/CartValidations.php';
class Cart extends CartValidations {
    private $objDatabase;
    // Constructor
    function __construct($objDb) {
        parent::__construct($objDb);
        $this->objDatabase = $objDb;
    }
    public function addToCart($arrCartData){
       $strHandle = 'addToCart';
       $arrApiResponse = $this->validateCartData($arrCartData,$strHandle);
       $arrResponse = array();
       if(TRUE == $arrApiResponse['status']){
            $strMessage = '';
            $arrPostedData = $arrApiResponse['clean_data'];
            $arrCartData = (isset($_SESSION['cart'])?$_SESSION['cart']:array());
            if(!is_array($arrCartData)){
                $arrCartData = array();
            }
            $arrNewCartData = array();
            
            $arrMergePostData = $this->mergePostedData($arrPostedData);
            if(count($arrCartData) == 0){
                foreach ($arrMergePostData as $key => $arrProduct) {
                    if(false == $this->isExist($arrProduct['id'], 'id', 'tbl_products')){
                        $strMessage.='Product with Id:  '.$arrProduct['id'].'  Does not Exist';
                        $arrApiResponse['bad_data'][] = $arrMergePostData[$key];
                        unset($arrMergePostData[$key]);
                    }
                }
               $arrNewCartData =  $arrMergePostData;
            }else{
                $arrNewCartData = $arrCartData;
                foreach ($arrMergePostData as $key => $arrCartData) {
                   $intProductId = $arrCartData['id'];
                   $intProductQty = $arrCartData['qty'];
                    
                   // Saved Session
                   foreach ( $arrNewCartData as $key => $arrSavedProductData) {
                      if($arrSavedProductData['id'] == $intProductId ){
                         $intOldQty = $arrSavedProductData['qty'];
                         $intNewQty = $intOldQty + $intProductQty ;
                         $arrNewCartData[$key]['qty'] = $intNewQty; 
                      } 
                   }
                }
            }
            
            $_SESSION['cart'] = $arrNewCartData;
            if(!empty($strMessage)){
                $strMessage.=' , ';
            }
            $arrResponse['status'] = true;
            $strMessage.="Products Added To Cart Successfully";
            $arrResponse['msg'] = $strMessage;
        }
        if(count($arrApiResponse['error_codes'])){
            foreach($arrApiResponse['error_codes'] as $strErrorCode){
                $arrResponse['errors'][] = $this->getError($strErrorCode);
            }
          
        }
        $arrResponse['invalid_data'] = $arrApiResponse['bad_data'];
        return $arrResponse;
    }
    private function mergePostedData($arrProducts){
        $arrNormalizedData = array();
        $arrResponse = array();
        foreach ($arrProducts as $arrProduct) {
            $intProductId = $arrProduct['id'];
            $intProductQty = $arrProduct['qty'];
            if(array_key_exists($intProductId, $arrNormalizedData)){
                $intOldQty = $arrNormalizedData[$intProductId]['qty'];
                $intNewQty = $intOldQty + $intProductQty ;
                $arrNormalizedData[$intProductId]['qty'] = $intNewQty;
            }else{
               $arrNormalizedData[$intProductId] = $arrProduct; 
            }
        }
        foreach ($arrNormalizedData as $arrProducts){
            $arrResponse[] = $arrProducts;
        }
        return $arrResponse;
    }
    public function updateCart($arrProductData){
       $strHandle = 'updateCart';
       $arrResponse = array();
       $arrApiResponse = $this->validateCartData($arrProductData,$strHandle);
       $arrResponse['status'] = TRUE;
       if(TRUE == $arrApiResponse['status']){
            
            $strMessage = '';
            $arrPostedData = $arrApiResponse['clean_data'];
            $arrCartData = (isset($_SESSION['cart'])?$_SESSION['cart']:array());
            if(!is_array($arrCartData)){
                $arrCartData = array();
            }
            if(count($arrCartData) == 0){
                $strMessage = 'Cart Empty, Please Add Products...!';
            }else{
                $boolIsCartUpdated = false;
                $intProductId = $arrPostedData[0]['id'];
                $intProductQty = $arrPostedData[0]['qty'];
                foreach ($arrCartData as $key => $arrCart) {
                    if( $intProductId == $arrCart['id']){
                       $intCurrentQty = $arrCartData[$key]['qty'];
                       $arrCartData[$key]['qty'] = $intCurrentQty + $intProductQty; 
                       $boolIsCartUpdated = true;
                    }
                }
               if($boolIsCartUpdated){
                   $strMessage="Cart Updated...!";
                   $_SESSION['cart'] = $arrCartData;
               }else{
                   $strMessage="Product with specified Id not found in cart.!";
               }
               
               
            }
            $arrResponse['msg'] = $strMessage;
        }
        if(count($arrApiResponse['error_codes'])){
            foreach($arrApiResponse['error_codes'] as $strErrorCode){
                $arrResponse['errors'][] = $this->getError($strErrorCode);
            }
          
        }
        if(isset($arrApiResponse['bad_data'])){
            $arrBadData = $arrApiResponse['bad_data'];
            if(count($arrBadData)){
                $arrResponse['invalid_data'] = $arrBadData;
            }
            
        }
        
        return $arrResponse;
    }
    public function getCart() {
        $arrResponse = array();
        $strMessage = '';
        $arrCartData = (isset($_SESSION['cart']) ? $_SESSION['cart'] : array());
        if (!is_array($arrCartData)) {
            $arrCartData = array();
        }
        if (0 == count($arrCartData)) {
            $arrResponse['status'] = false;
            $strMessage = 'Cart Empty, Please Add Products';
        } else {
            $strMessage = 'Cart Data Retrieved successfully.';
            $arrResponse = $this->getCartTotals($arrCartData);
        }
        $arrResponse['msg'] = $strMessage;

        return $arrResponse;
    }

    public function getCartTotal() {
        $arrResponse = array();
        $strHandle = 'cart_total';
        $strMessage = '';
        $arrCartData = (isset($_SESSION['cart']) ? $_SESSION['cart'] : array());
        if (!is_array($arrCartData)) {
            $arrCartData = array();
        }
        if (0 == count($arrCartData)) {
            $arrResponse['status'] = false;
            $strMessage = 'Cart Empty, Please Add Products';
        } else {
            $strMessage = 'Cart Total Retrieved successfully.';
            $arrResponse = $this->getCartTotals($arrCartData, $strHandle);
        }
        $arrResponse['msg'] = $strMessage;

        return $arrResponse;
    }
    
    public function getCartTotalDiscount() {
        $arrResponse = array();
        $strHandle = 'cart_total_discount';
        $strMessage = '';
        $arrCartData = (isset($_SESSION['cart']) ? $_SESSION['cart'] : array());
        if (!is_array($arrCartData)) {
            $arrCartData = array();
        }
        if (0 == count($arrCartData)) {
            $arrResponse['status'] = false;
            $strMessage = 'Cart Empty, Please Add Products';
        } else {
            $strMessage = 'Cart Total Disount  Retrieved successfully.';
            $arrResponse = $this->getCartTotals($arrCartData, $strHandle);
        }
        $arrResponse['msg'] = $strMessage;

        return $arrResponse;
    }
    
    public function getCartTotalTax() {
        $arrResponse = array();
        $strHandle = 'cart_total_tax';
        $strMessage = '';
        $arrCartData = (isset($_SESSION['cart']) ? $_SESSION['cart'] : array());
        if (!is_array($arrCartData)) {
            $arrCartData = array();
        }
        if (0 == count($arrCartData)) {
            $arrResponse['status'] = false;
            $strMessage = 'Cart Empty, Please Add Products';
        } else {
            $strMessage = 'Cart Total Tax  Retrieved successfully.';
            $arrResponse = $this->getCartTotals($arrCartData, $strHandle);
        }
        $arrResponse['msg'] = $strMessage;

        return $arrResponse;
    }

    public function removeFromCart($arrProductData){
       $strHandle = 'removeFromCart';
       $arrResponse = array();
       $arrApiResponse = $this->validateCartData($arrProductData,$strHandle);
       $arrResponse['status'] = TRUE;
       if(TRUE == $arrApiResponse['status']){
            
            $strMessage = '';
            $arrPostedData = $arrApiResponse['clean_data'];
            $arrCartData = (isset($_SESSION['cart'])?$_SESSION['cart']:array());
            if(!is_array($arrCartData)){
                $arrCartData = array();
            }
            $arrNewCartData = $arrCartData;
            if(count($arrCartData) == 0){
                $strMessage = 'Cart Empty, Please Add Products...!';
            }else{
                $intProductId = $arrPostedData['id'];
                foreach ($arrNewCartData as $key => $arrCart) {
                    if( $intProductId == $arrCart['id']){
                        unset($arrNewCartData[$key]);
                    }
                }
               if(count($arrNewCartData) == count($arrCartData)){
                   $strMessage="Products Not Found in Cart";
               }else{
                   $_SESSION['cart'] = $arrNewCartData;
                   $strMessage="Products Removed From Cart Successfully";
               }
               
               
            }
            $arrResponse['msg'] = $strMessage;
        }
        if(count($arrApiResponse['error_codes'])){
            foreach($arrApiResponse['error_codes'] as $strErrorCode){
                $arrResponse['errors'][] = $this->getError($strErrorCode);
            }
          
        }
        if(isset($arrApiResponse['bad_data'])){
            $arrBadData = $arrApiResponse['bad_data'];
            if(count($arrBadData)){
                $arrResponse['invalid_data'] = $arrBadData;
            }
            
        }
        
        return $arrResponse;
        
    }
    public function clearCart() {
        $arrResponse = array();
        $strMessage = '';

        if (session_destroy()) {
            $arrResponse['status'] = TRUE;
            $strMessage = 'Cart Cleared Successfully.';
        } else {
            $arrResponse['status'] = FALSE;
            $strMessage = 'Error Clearing Cart, Please Try Again.';
        }
        $arrResponse['msg'] = $strMessage;
        return $arrResponse;
    }

    private function getCartTotals($arrProducts,$strHandle = NULL){
        $arrProductIds = array();
        $arrProductResponse = array();
        $arrProductResponse['status'] = false;
        foreach ($arrProducts as $arrProduct){
            $arrProductIds[] = $arrProduct['id'];
        }
        $strProductIds = implode(',', $arrProductIds);
        $strSql = "SELECT 
                        prod_info.*,
                        category.name as category_name,
                        category.description as category_description,
                        category.tax as category_tax
                    FROM 
                        tbl_products prod_info
                    JOIN
                        tbl_categories category
                    ON
                    prod_info.category_id = category.id
                    WHERE prod_info.id IN ($strProductIds)";
        $objProducts = $this->objDatabase->query($strSql);
        
        if($objProducts){
            switch ($strHandle) {
                case 'cart_total':
                    $arrProductDetails = array();
                    $intGrandTotal = 0;
                    while ($arrProductInfo = $objProducts->fetch_assoc()) {
                        $intProductQty = $this->getCartProductQty($arrProducts, $arrProductInfo['id']);
                        $intProductTotalPrice = $arrProductInfo['price'] * $intProductQty;
                        $intDiscount = $arrProductInfo['discount'];
                        $intProductTotalDiscountedPrice =  round($intProductTotalPrice * (( 100 - $intDiscount) / 100));
                        $intCategoryTax = $arrProductInfo['category_tax'];
                        $intCategoryTaxRate = ($intProductTotalDiscountedPrice * $intCategoryTax ) / 100;
                        $intProductTotalTaxInclude = round($intProductTotalDiscountedPrice + $intCategoryTaxRate);
                        $intGrandTotal = round($intGrandTotal + $intProductTotalTaxInclude);
                    }
                    $arrProductResponse['status'] = true;
                    $arrProductResponse['grand_total'] = $intGrandTotal;
                    break;
                case 'cart_total_discount':
                    $arrProductDetails = array();
                    $intTotaDiscount = 0;
                    while ($arrProductInfo = $objProducts->fetch_assoc()) {
                        $intDiscount = $arrProductInfo['discount'];
                        $intTotaDiscount = round($intTotaDiscount + $intDiscount);
                    }
                    $arrProductResponse['status'] = true;
                    $arrProductResponse['total_discount'] = $intTotaDiscount.'%';
                break;
                case 'cart_total_tax':
                    $arrProductDetails = array();
                    $intTotalTax = 0;
                    while ($arrProductInfo = $objProducts->fetch_assoc()) {
                        $intTax = $arrProductInfo['category_tax'];
                        $intTotalTax = round($intTotalTax + $intTax);
                    }
                    $arrProductResponse['status'] = true;
                    $arrProductResponse['total_tax'] = $intTotalTax.'%';
                break;
                default:
                    $arrProductDetails = array();
                    $intGrandTotal = 0;
                    while ($arrProductInfo = $objProducts->fetch_assoc()) {
                        $intProductQty = $this->getCartProductQty($arrProducts, $arrProductInfo['id']);
                        $arrProductDetails['product_id'] = $arrProductInfo['id'];
                        $arrProductDetails['product_name'] = $arrProductInfo['name'];
                        $arrProductDetails['product_description'] = $arrProductInfo['description'];
                        $arrProductDetails['product_price'] = $arrProductInfo['price'];
                        $arrProductDetails['product_qty'] = $intProductQty;
                        $arrProductDetails['product_total_price'] = $arrProductInfo['price'] * $intProductQty;
                        $arrProductDetails['product_discount'] = $arrProductInfo['discount'] . '%';
                        $arrProductDetails['product_total_discounted_price'] = round($arrProductDetails['product_total_price'] * (( 100 - $arrProductInfo['discount']) / 100));
                        $arrProductDetails['product_category'] = $arrProductInfo['category_name'];
                        $arrProductDetails['product_category_tax'] = $arrProductInfo['category_tax'] . '%';
                        $intCategoryTaxRate = ($arrProductDetails['product_total_discounted_price'] * $arrProductInfo['category_tax'] ) / 100;
                        $arrProductDetails['product_total_tax_include'] = round($arrProductDetails['product_total_discounted_price'] + $intCategoryTaxRate);
                        $intGrandTotal = round($intGrandTotal + $arrProductDetails['product_total_tax_include']);
                        $arrProductResponse['products'][] = $arrProductDetails;
                    }
                    $arrProductResponse['status'] = true;
                    $arrProductResponse['products']['grand_total'] = $intGrandTotal;
                 break;
            }
            
            
        }
       return $arrProductResponse;
    }
    private function getCartProductQty($arrProducts,$intId){
        $intQty = 0;
        foreach ($arrProducts as $product){
            if($intId == $product['id']){
             $intQty = $product['qty'];
             break;
            }
        }
        return $intQty;
    }
    function isExist($intId,$strField,$table){
        
        $intRowId = trim($intId);
        $strSql = 'SELECT * from '.$table.' where '. $strField.' = ?';
            if( $stmt = $this->objDatabase->prepare($strSql) ){
                $stmt->bind_param('i',$intRowId);
                $stmt->execute();
                $stmt->store_result();
                /* Get the number of rows */
                $num_of_rows = $stmt->num_rows;
                if($num_of_rows > 0 ){
                    return true;
                }
                return false;
        } 
    }
    
}
?>