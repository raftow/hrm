<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table hrm_leave_type : hrm_leave_type - أنواع الاجازات والأذونات 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class HrmLeaveType extends AFWObject{

	public static $DATABASE		= ""; 
        public static $MODULE		    = "hrm"; 
        public static $TABLE			= "hrm_leave_type"; 
        public static $DB_STRUCTURE = null; 
        
        public function __construct(){
		parent::__construct("hrm_leave_type","id","hrm");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "hrm_leave_type_name_ar";
                $this->ORDER_BY_FIELDS = "hrm_leave_type_name_ar";
	}
}
?>