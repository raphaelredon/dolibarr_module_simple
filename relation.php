<?php

	require 'config.php';
	
	dol_include_once('/societe/class/societe.class.php');
	dol_include_once('/simple/class/relation.class.php');


	$langs->load('simple@simple');
	
	$object = new Societe($db);
	$object->fetch(GETPOST('fk_soc'));
	
	$action = GETPOST('action');
	
	$PDOdb = new TPDOdb;
	
	$relation = new TRelation;
	$relation->loadBy($PDOdb, $object->id, 'fk_soc');
	
	
	switch ($action) {
		case 'save':
			
			$relation->set_values($_POST);
			$relation->save($PDOdb);
			
			setEventMessage($langs->trans('RelationSaved'));
			
			_card($object,$relation);
			break;
		default:
			_card($object,$relation);
			break;
	}
	
	
	
function _card(&$object,&$relation) {
	global $langs;

	dol_include_once('/core/lib/company.lib.php');
	
	llxHeader();
	$head = societe_prepare_head($object);
	dol_fiche_head($head, 'relation', '', 0, '');
	
	$formCore=new TFormCore('relation.php', 'formSimple');
	echo $formCore->hidden('fk_soc', $object->id);
	echo $formCore->hidden('action', 'save');
	
	echo '<h2>'.$langs->trans('RelationTitle').'</h2>';

	
	
	echo $formCore->texte($langs->trans('Comment'),'comment',$relation->comment,80,80).'<br />';
	
	echo $formCore->btsubmit($langs->trans('Save'), 'bt_save');
	
	$formCore->end();
	
	dol_fiche_end();
	llxFooter();	  
		 	
}

