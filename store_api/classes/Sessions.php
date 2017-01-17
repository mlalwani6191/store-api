<?php
class Sessions {
    private $objDatabase;
    public function __construct($objDb) {
        // Instantiate new Database object
        $this->objDatabase = $objDb;
        // Set handler to overide SESSION
        //session_destroy();
        session_set_save_handler(
                array($this, "_open"), array($this, "_close"), array($this, "_read"), array($this, "_write"), array($this, "_destroy"), array($this, "_gc")
        );
        // Start the session

        if(!isset($_SESSION)){
         session_start();
        }
    }
    
    /**
     * Open
     */
    public function _open() {
        // If successful
        if ($this->objDatabase) {
            // Return True
            return true;
        }
        // Return False
        return false;
    }
    
    /**
     * Close
     */
    public function _close() {
        // Close the database connection
        // If successful
        if ($this->objDatabase->close()) {
            // Return True
            return true;
        }
        // Return False
        return false;
    }

    /**
     * Read
     */
    public function _read($id) {
        // Set query
        $strSql = "SELECT data FROM tbl_sessions WHERE id = ?";
            if( $stmt = $this->objDatabase->prepare($strSql) ){
                $stmt->bind_param('s',$id);
                if ($stmt->execute()) {
                /* Get the result */
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                if (NULL == $row) {
                    return '';
                }
                if (isset($row['data'])) {
                    if(!empty($row['data'])){
                        return $row['data'];
                    }
                    return '';
                }
            }
        }
    }
    
    /**
     * Write
     */
    public function _write($id, $data) {
        // Create time stamp
        $access = time();
        $strSql = "REPLACE INTO tbl_sessions VALUES (?, ?, ?)";
        // Set query  
        if ($stmt = $this->objDatabase->prepare($strSql)) {
            $stmt->bind_param('sss', $id, $access, $data);
            if($stmt->execute()){
              return true;  
            }else{
               return false; 
            }            
        } else {
            printf("Errormessage: %s\n", $this->objDatabase->error);
        }
        
    }
    
    /**
     * Destroy
     */
    public function _destroy($id) {
        // Set query
        $strSql = 'DELETE FROM tbl_sessions WHERE id = ?';
        if ($stmt = $this->objDatabase->prepare($strSql)) {
            $stmt->bind_param('s', $id);
            if($stmt->execute()){
              return true;  
            }else{
               return false; 
            }            
        } else {
            printf("Errormessage: %s\n", $this->objDatabase->error);
        }
    }
    
    /**
     * Garbage Collection
     */
    public function _gc($max) {
        // Calculate what is to be deemed old
        $old = time() - $max;
        $strSql = 'DELETE  FROM tbl_sessions WHERE access < ?';
        if ($stmt = $this->objDatabase->prepare($strSql)) {
            $stmt->bind_param('s', $old);
            if($stmt->execute()){
              return true;  
            }else{
               return false; 
            }            
        } else {
            printf("Errormessage: %s\n", $this->objDatabase->error);
        }
    }
    
}
?>