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

		$TGrade=array('E','D','C','B','A');

		return $TGrade[$pt];

	}

}

