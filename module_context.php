<?php
        $contextLabel = array("ar"=>"إختيار  المنظمة","fr"=>"choix de l'organisation","en"=>"organization choice");
        $contextShortLabel = array("ar"=>"ت.م", "fr"=>"a.p","en"=>"p.a"); 
        
             
        
     
        $obj = new Orgunit();
        $obj->where("id_sh_type in (5,6,7)");
        $obj->select("active","Y");
     
        $obj_list = $obj->loadMany();
        
        foreach($obj_list as $obj_item)
        {
              if($obj_item->getId()>0)
              {
                   $contextList[$obj_item->getId()] = $obj_item;
              }
        }
      
        return $contextList;
?>