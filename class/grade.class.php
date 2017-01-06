<?php

class Grade  {

	static function get(&$societe) {

		$pt = 0;
		$dep = substr($societe->zip,0,2);
		if($dep == '07' || $dep == '26') {
			$pt++;
		}

		if($societe->capital>5000) $pt++;
		if($societe->capital>50000) $pt++;
		if($societe->capital>500000) $pt++;

		$risque = $societe->array_options['options_risque'];
		if($risque>30) $pt--;
		if($risque>60) $pt--;
		if($risque>90) $pt = 0;
		
		if($pt>4) $pt = 4;
		else if($pt<0) $pt = 0;

		$TGrade=array('E','D','C','B','A');

		return $TGrade[$pt];

	}

}

