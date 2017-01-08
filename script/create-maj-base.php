<?php
/*
 * Script créant et vérifiant que les champs requis s'ajoutent bien
 */

if(!defined('INC_FROM_DOLIBARR')) {
	define('INC_FROM_CRON_SCRIPT', true);

	require('../config.php');

}


dol_include_once('/simple/class/simple.class.php');

$PDOdb=new TPDOdb;

$o=new TSimple208000;
$o->init_db_by_vars($PDOdb);

dol_include_once('/simple/class/relation.class.php');
$o=new TRelation;
$o->init_db_by_vars($PDOdb);

global $db;
dol_include_once('/core/class/extrafields.class.php');
$extrafields=new ExtraFields($db);
$res = $extrafields->addExtraField('risque', 'Risque', 'double', 0, '10,2', 'societe');

