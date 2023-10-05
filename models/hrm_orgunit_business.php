<?php

class HrmOrgunitBusiness
{
    public static function trigger_new_organization($orgunit)
    {
        
        
    
    }
    
    public static function trigger_before_delete_organization($id, $id_replace=0, $simul=false)
    {
        
        /*
        $file_dir_name = dirname(__FILE__); 
        require_once("$file_dir_name/../award/practice.php");
        $obj = new Practice();
        
        if($id_replace==0)
        {
                // btb.work_branch-الوحدة في الموارد البشرية	tcompany_id  جزء مني ولا يعمل إلا بي-OneToOneBidirectional
                $obj->where("orgunit_id = '$id' and active='Y' ");
                $nbRecords = $obj->count();
                // check if there's no record that block the delete operation
                if($nbRecords>0)
                {
                    return array(false, "Used in some practice(s) as owner Orgunit");
                }
                // if there's no record that block the delete operation perform the delete of the other records linked with me and deletable
                if(!$simul) $obj->deleteWhere("orgunit_id = '$id' and active='N'");
        }
        else
        {
                // btb.work_branch-الوحدة في الموارد البشرية	tcompany_id  جزء مني ولا يعمل إلا بي-OneToOneBidirectional
                if(!$simul) {
                    $sets_arr = array();
                    $sets_arr["orgunit_id"] = $id_replace;                    
                    $obj->updateWhere($sets_arr, "orgunit_id='$id' ");
                }

        }*/
        
        return array(true, "");

    }

    public static function trigger_before_delete_employee($id, $id_replace=0, $simul=false)
    {
        return array(true, "");
    }
    
}