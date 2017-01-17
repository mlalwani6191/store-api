<?php
include 'classes/CategoryValidations.php';
class Category extends CategoryValidations {
    private $objDatabase;
    // Constructor
    function __construct($objDb) {
        $this->objDatabase = $objDb;
    }
    public function addCategory( $arrCategories ){
        $strHandle = 'addCategory';
        $arrApiResponse = $this->validateCategoryData($arrCategories,$strHandle);
        $arrResponse = array();
        if(TRUE == $arrApiResponse['status']){
            $arrPostedData = $arrApiResponse['clean_data'];
            $intCount = 0;
            foreach($arrPostedData as $intKey => $arrCategory){
                
                if( false == $this->isDuplicate($arrCategory['name'])){
                    $intAffectedRows = $this->Save($arrCategory);
                    $intCount+=$intAffectedRows;
                }else{
                   $arrResponse['duplicate_data'][] = $arrCategory;  
                }
                
            }
            $arrResponse['status'] = true;
            $arrResponse['msg'] = "$intCount : Categories Added Successfully";
        }
        if(count($arrApiResponse['error_codes'])){
            foreach($arrApiResponse['error_codes'] as $strErrorCode){
                $arrResponse['errors'][] = $this->getError($strErrorCode);
            }
          
        }
        $arrResponse['invalid_data'] = $arrApiResponse['bad_data'];
        return $arrResponse;
    }
    public function  deleteCategory($arrProduct){
        $strHandle = 'deleteCategory';
        $arrApiResponse = $this->validateCategoryData($arrProduct,$strHandle);
        if(TRUE == $arrApiResponse['status']){
            $arrPostedData = $arrApiResponse['clean_data'];
            $intCount = 0;
            $intId = $arrPostedData['id'];
            if($this->isExist($intId,'id','tbl_categories')) {
                $intAffectedRows = $this->Delete($intId);
                if($intAffectedRows){
                    $strMessage = "Category with Id : $intId deleted Successfully";
                }else{
                    $strMessage = "Error Deleting Category";
                }
            }else{
                $strMessage = "Category Id Does Not Exist";
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
    public function  updateCategory($arrCategory){
        $strHandle = 'updateCategory';
        $arrApiResponse = $this->validateCategoryData($arrCategory,$strHandle);
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
            if( false == $this->isExist($intPostedId,'id','tbl_categories')) {
                $boolIsvalid = false;
                $strMessage.="Id Passed Does Not Exist";
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
        $intTax= (isset($arrPostData['tax'])?$arrPostData['tax']:'');
        $strAddedOn = date('Y-m-d H:i:s');
        if( $intId == NULL ){
            //Insert 
            $strSql = 'INSERT INTO tbl_categories (id,name,description,tax,added_on) VALUES (NULL,?,?,?,?)';
            if( $stmt = $this->objDatabase->prepare($strSql) ){
                $stmt->bind_param('ssis', $strName, $strDescription,$intTax,$strAddedOn);
                $stmt->execute();
                return $stmt->affected_rows;
            }  else {
                printf("Errormessage: %s\n", $this->objDatabase->error);
            }
            
        }else{
            // Update
            //var_dump($arrPostData);
            $strSql = "UPDATE tbl_categories SET updated_on = ? ";
            $type = 's';
            $strUpdatedOn = date('Y-m-d H:i:s');
            $arrSqlParams = array();
            $arrSqlParams[] = &$strUpdatedOn;
            if(isset($arrPostData['name'])){
                $strSql.=" , name=?";
                $type.= 's';
                $arrSqlParams[] = &$arrPostData['name'];
            }
            
            if(isset($arrPostData['tax'])){
                $strSql.=" , tax=?";
                $type.= 'i';
                $arrSqlParams[] = &$arrPostData['tax'];
            }
            if(isset($arrPostData['description'])){
                $strSql.=" , description=?";
                $type.= 's';
                $arrSqlParams[] = &$arrPostData['description'];
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
        $strSql = "DELETE from tbl_categories where id = ? ";
        if ($stmt = $this->objDatabase->prepare($strSql)) {
            $stmt->bind_param('i',$intId);
            $stmt->execute();
            return $stmt->affected_rows;
        } else {
            printf("Errormessage: %s\n", $this->objDatabase->error);
            trigger_error('Wrong SQL: ' . $strSql . ' Error: ' . $this->objDatabase->errno . ' ' . $this->objDatabase->error, E_USER_ERROR);
        }
    }

    public function getCategories() {
        $strSql="SELECT 
                    *
                 FROM 
                    tbl_categories ";       
        if( $results = $this->objDatabase->query($strSql) ){
            $arrResult = array();
            while($row = $results->fetch_array()){
                $arrCategoryData['id'] = $row['id'];
                $arrCategoryData['name'] = $row['name'];
                $arrCategoryData['description'] = $row['description'];
                $arrCategoryData['tax'] = $row['tax'].'%';
                $arrCategoryData['added_on'] = $row['added_on'];
                $arrResult[] = $arrCategoryData;
            }
            $arrResponse['status'] = true;
            $arrResponse['Categories'] = $arrResult;
            return $arrResponse;
        }  else {
            printf("Errormessage: %s\n", $this->objDatabase->error);
        }
    }
    
    function isDuplicate($strCategoryName){
        $trimmedName = trim($strCategoryName);
        $strSql = 'SELECT * from tbl_categories where name = ?';
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