<?php
include 'classes/ProductValidations.php';
class Products extends ProductValidations {
    private $objDatabase;
    // Constructor
    function __construct($objDb) {
        $this->objDatabase = $objDb;
    }
    public function addProduct( $objProducts ){
        $strHandle = 'addProduct';
        $arrApiResponse = $this->validateProductData($objProducts,$strHandle);
        $arrResponse = array();
        if(TRUE == $arrApiResponse['status']){
            $arrPostedData = $arrApiResponse['clean_data'];
            $intCount = 0;
            foreach($arrPostedData as $intKey => $arrProduct){
                
                if( false == $this->isDuplicate($arrProduct['name'])){
                    if (false == $this->isExist($arrProduct['category_id'], 'id', 'tbl_categories')) {
                        $arrApiResponse['error_codes'][] = 'INVALID_CATEGORY';
                        $arrApiResponse['bad_data'][] = $arrProduct;
                    }else{
                        $intAffectedRows = $this->Save($arrProduct);
                        $intCount = $intCount + $intAffectedRows;
                    }
                    
                }else{
                   $arrResponse['duplicate_data'][] = $arrProduct;  
                }
                
            }
            $arrResponse['status'] = true;
            $arrResponse['msg'] = "$intCount : Products Added Successfully";
        }
        if(count($arrApiResponse['error_codes'])){
            $arrErrorCodes = array_unique($arrApiResponse['error_codes']);
            foreach( $arrErrorCodes as $strErrorCode){
                $arrResponse['errors'][] = $this->getError($strErrorCode);
            }
          
        }
        $arrResponse['invalid_data'] = $arrApiResponse['bad_data'];
        return $arrResponse;
    }
    public function  deleteProduct($arrProduct){
     $strHandle = 'deleteProduct';
        $arrApiResponse = $this->validateProductData($arrProduct,$strHandle);
        if(TRUE == $arrApiResponse['status']){
            $arrPostedData = $arrApiResponse['clean_data'];
            $intId = $arrPostedData['id'];
            if($this->isExist($intId,'id','tbl_products')) {
                $intAffectedRows = $this->Delete($intId);
                if($intAffectedRows){
                    $strMessage = "Product with Id : $intId deleted Successfully";
                }else{
                    $strMessage = "Error Deleting Product";
                }
            }else{
                $strMessage = "Product Id Does Not Exist";
            }
            $arrResponse['status'] = true;
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
    public function  updateProduct($objProducts){
        $strHandle = 'updateProduct';
        $arrApiResponse = $this->validateProductData($objProducts,$strHandle);
        $arrResponse = array();
        if(TRUE == $arrApiResponse['status']){
            $intPostedId = $arrApiResponse['clean_data']['id'];
            $arrPostedData = $arrApiResponse['clean_data']['data'];
            $boolIsvalid = true;
            $strMessage = '';
            if(isset($arrPostedData['name'])){
               if( true == $this->isDuplicate($arrPostedData['name'])) {
                $boolIsvalid = false;
                $strMessage.="Product Name Already Exists.";
               }  
            }
            if( false == $this->isExist($intPostedId,'id','tbl_products')) {
                $boolIsvalid = false;
                $strMessage.="Id Passed Does Not Exist";
            }
            if(isset($arrPostedData['category_id'])){
              if( false == $this->isExist($arrPostedData['category_id'],'id','tbl_categories')) {
                $boolIsvalid = false;
                $strMessage.="Category Id Passed Does Not Exist";
                }  
            }
            if($boolIsvalid){
              $arrResponse['status'] = true;
              $intAffectedRows = $this->Save($arrPostedData,$intPostedId);
              if($intAffectedRows){
                   $arrResponse['msg'] = "Products Updated Successfully";  
              }
            }else{
             $arrResponse['status'] = false; 
             $arrResponse['msg'] = $strMessage;
            }

            
        }
        if(count($arrApiResponse['error_codes'])){
            foreach($arrApiResponse['error_codes'] as $strErrorCode){
                $arrResponse['errors'][] = $this->getError($strErrorCode);
            }
          
        }
        $arrResponse['invalid_data'] = $arrApiResponse['bad_data'];
        return $arrResponse;
        
    }
    public function Save($arrPostData,$intId = NULL){
        $strName = (isset($arrPostData['name'])?$arrPostData['name']:'');
        $strDescription = (isset($arrPostData['description'])?$arrPostData['description']:'');
        $strPrice = (isset($arrPostData['price'])?$arrPostData['price']:'');
        $strDiscount = (isset($arrPostData['discount'])?$arrPostData['discount']:'');
        $strCategoryId= (isset($arrPostData['category_id'])?$arrPostData['category_id']:'');
        $strAddedOn = date('Y-m-d H:i:s');
        if( $intId == NULL ){
            //Insert 
            $strSql = 'INSERT INTO tbl_products (id,name,description,price,discount,category_id,added_on) VALUES (NULL,?,?,?,?,?,?)';
            if( $stmt = $this->objDatabase->prepare($strSql) ){
                $stmt->bind_param('ssddis', $strName, $strDescription,$strPrice,$strDiscount,$strCategoryId,$strAddedOn);
                if ($stmt->execute()) {
                    $stmt->store_result();
                    if ($stmt->affected_rows < 0) {
                        return 0;
                    } else {
                        return $stmt->affected_rows;
                    }
                } else {
                    die('execute() failed: ' . $this->objDatabase->error);
                }
            }  else {
                printf("Errormessage: %s\n", $this->objDatabase->error);
            }
            
        }else{
            // Update
            //var_dump($arrPostData);
            $strSql = "UPDATE tbl_products SET updated_on = ? ";
            $type = 's';
            $strUpdatedOn = date('Y-m-d H:i:s');
            $arrSqlParams = array();
            $k = 'ssii';
            //$arrSqlParams[] = &$k;
            $arrSqlParams[] = &$strUpdatedOn;
            if(isset($arrPostData['name'])){
                $strSql.=" , name=?";
                $type.= 's';
                $arrSqlParams[] = &$arrPostData['name'];
            }
            
            if(isset($arrPostData['price'])){
                $strSql.=" , price=?";
                $type.= 'd';
                $arrSqlParams[] = &$arrPostData['price'];
            }
            if(isset($arrPostData['description'])){
                $strSql.=" , description=?";
                $type.= 's';
                $arrSqlParams[] = &$arrPostData['description'];
            }
            if(isset($arrPostData['discount'])){
                $strSql.=" , discount=?";
                $type.= 'd';
                $arrSqlParams[] = &$arrPostData['discount'];
            }
            if(isset($arrPostData['category_id'])){
                $strSql.=" , category_id=?";
                $type.= 'i';
                $arrSqlParams[] = &$arrPostData['category_id'];
            }
            
            
            $type.= 'i';
            
            $arrSqlParams[] = &$intId;
            array_unshift($arrSqlParams,$type);
            $arrSqlParams[0] = &$arrSqlParams[0];
            $strSql.=' WHERE id=?';
            if( $stmt = $this->objDatabase->prepare($strSql) ){
                call_user_func_array(array($stmt, 'bind_param'), $arrSqlParams);
                $stmt->execute();
                return $stmt->affected_rows;
            }  else {
                printf("Errormessage: %s\n", $this->objDatabase->error);
                trigger_error('Wrong SQL: ' . $strSql . ' Error: ' . $this->objDatabase->errno . ' ' . $this->objDatabase->error, E_USER_ERROR);
            }
        }
    }
    public function Delete($intId){
        $strSql = "DELETE from tbl_products where id = ? ";
        if ($stmt = $this->objDatabase->prepare($strSql)) {
            $stmt->bind_param('i',$intId);
            $stmt->execute();
            return $stmt->affected_rows;
        } else {
            printf("Errormessage: %s\n", $this->objDatabase->error);
            trigger_error('Wrong SQL: ' . $strSql . ' Error: ' . $this->objDatabase->errno . ' ' . $this->objDatabase->error, E_USER_ERROR);
        }
    }

    public function getProducts() {
        $strSql="SELECT 
                    prod_info.*,
                    category.name as category
                FROM 
                    tbl_products prod_info
                JOIN
                    tbl_categories category
                ON
                    prod_info.category_id = category.id";
        
        if( $results = $this->objDatabase->query($strSql) ){
            $arrResult = array();
            while($row = $results->fetch_array()){
                $arrProductData['id'] = $row['id'];
                $arrProductData['name'] = $row['name'];
                $arrProductData['description'] = $row['description'];
                $arrProductData['price'] = $row['price'];
                $arrProductData['discount'] = $row['discount'];
                $arrProductData['category'] = $row['category'];
                $arrProductData['added_on'] = $row['added_on'];
                $arrResult[] = $arrProductData;
            }
            $arrResponse['status'] = true;
            $arrResponse['Products'] = $arrResult;
            return $arrResponse;
        }  else {
            printf("Errormessage: %s\n", $this->objDatabase->error);
        }
    }
    
    function isDuplicate($strProductName){
        $trimmedName = trim($strProductName);
        $strSql = 'SELECT * from tbl_products where name = ?';
            if( $stmt = $this->objDatabase->prepare($strSql) ){
                $stmt->bind_param('s',$trimmedName);
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