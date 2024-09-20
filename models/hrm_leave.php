<?php
// ------------------------------------------------------------------------------------
// ----             auto generated php class of table hrm_leave : hrm_leave - الاجازات والأذونات 
// ------------------------------------------------------------------------------------

                
$file_dir_name = dirname(__FILE__); 
                
// old include of afw.php

class HrmLeave extends AFWObject{

	public static $DATABASE		= ""; 
	public static $MODULE		    = "hrm"; 
	public static $TABLE			= ""; 
	public static $DB_STRUCTURE = null; 
	
	public function __construct(){
		parent::__construct("hrm_leave","id","hrm");
                $this->QEDIT_MODE_NEW_OBJECTS_DEFAULT_NUMBER = 15;
                $this->DISPLAY_FIELD = "hrm_leave_name";
                $this->ORDER_BY_FIELDS = "hrm_leave_name";
	}
}
?>