<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table relship : relship - صلات القرابة 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class RelationShip extends AFWObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "hrm"; 
        public static $TABLE			= "relation_ship"; 
        public static $DB_STRUCTURE = null; 
        
        public function __construct(){
		parent::__construct("relation_ship","id","hrm");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "relship_name";
                $this->ORDER_BY_FIELDS = "id";
        }
        
        public function fld_CREATION_USER_ID()
        {
                return "creation_user_id";
        }

        public function fld_CREATION_DATE()
        {
                return "creation_date";
        }

        public function fld_UPDATE_USER_ID()
        {
        	return "update_user_id";
        }

        public function fld_UPDATE_DATE()
        {
        	return "update_date";
        }
        
        public function fld_VALIDATION_USER_ID()
        {
        	return "validation_user_id";
        }

        public function fld_VALIDATION_DATE()
        {
                return "validation_date";
        }
        
        public function fld_VERSION()
        {
        	return "version";
        }

        public function fld_ACTIVE()
        {
        	return  "active";
        }
}
?>