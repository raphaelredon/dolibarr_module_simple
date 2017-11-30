<?php

class TSimple208000 extends SeedObject {

	function __construct(&$db) {
		
		$this->db = $db;

		$this->table_element = 'simple208000';

		$this->fields=array(
			'rowid'=>array('type'=>'integer','index'=>true)
			,'title'=>array('type'=>'string','index'=>true,'length'=>80)
			,'fk_contact'=>array('type'=>'integer','index'=>true)
			,'datec'=>array('type'=>'date')
			,'tms'=>array('type'=>'date')
			
		);

		$this->init();
	}

	function fetchByContact($fk_contact) {
		
		$res = $this->db->query("SELECT rowid FROM ".MAIN_DB_PREFIX.$this->table_element." 
			WHERE fk_contact=".(int)$fk_contact);
		if($obj = $this->db->fetch_object($res)) {
			return $this->fetchCommon($obj->rowid);
		}

		return false;
	}

}

