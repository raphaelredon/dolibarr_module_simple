<?php

	require 'config.php';
	
	dol_include_once('/societe/class/societe.class.php');
	dol_include_once('/simple/class/relation.class.php'); // inclus le fiche en allant le chercher dans /htdocs ou dans /custom


	$langs->load('simple@simple'); // chargement de la langue du module
	
	$object = new Societe($db);
	$object->fetch(GETPOST('fk_soc')); // GETPOST list les paramètres passés à la page
	
	$action = GETPOST('action');
	
	$PDOdb = new TPDOdb; // L'objet std utilise un connecteur base de données différent pour une question d'histoire
	
	$relation = new TRelation;
//	$relation->loadBy($PDOdb, $object->id, 'fk_soc');	
	
	switch ($action) {
		case 'save':
			$relation->load($PDOdb, GETPOST('id')); // chargement d'un objet standard depuis la base de données
			$relation->set_values($_POST); // Mise à jour de l'object avec le tableau $_POST en correspondance clef / valeur
			$relation->save($PDOdb); // écriture de l'objet en base de données
			
			setEventMessage($langs->trans('RelationSaved'));
			
			_card($object,$relation);
			break;
		case 'view':
			$relation->load($PDOdb, GETPOST('id')); // en fonction des droits (notions non vues ensemble), l'utilisateur peut voir...
			_card($object,$relation,'view');
			break;
                case 'edit':
                        $relation->load($PDOdb, GETPOST('id')); // et/ou modifier
                        _card($object,$relation,'edit');
                        break;

		default:
			_list($PDOdb, $object);
	}
	
	
function _list(&$PDOdb,&$object) {
	global $langs;

	dol_include_once('/core/lib/company.lib.php');
        llxHeader(); // pose l'entête de la page
        $head = societe_prepare_head($object); // construit et ...
        dol_fiche_head($head, 'relation', '', 0, '');  //affiche les onglets

	$formCore=new TFormCore('relation.php', 'formRel','get');
        echo $formCore->hidden('fk_soc', $object->id);
        echo $formCore->hidden('action', 'list');


	$l=new TListViewTBS('listRelation'); // object gérant la création de liste
	echo $l->render($PDOdb,"SELECT * FROM ".MAIN_DB_PREFIX."relation WHERE fk_soc=".$object->id /* Requete construisant la liste */
	,array(
		'title'=>array('comment'=>$langs->trans('Comment')) /* traduction des titres de colonnes issus des champs de la base */
		,'type'=>array('date_cre'=>'date','date_maj'=>'date') /* typage de la colonne pour affichage */
		,'search'=>array('comment'=>true) /* on défini qu'une recherche est possible sur ce champs. D'où le formulaire entourant cette liste */
		,'link'=>array('rowid'=>'<a href="?action=view&fk_soc='.$object->id.'&id=@rowid@">@rowid@</a>') // Ajout d'un lien sur l'id pour voir la fiche de l'objet
		,'hide'=>array('fk_soc')
		,'liste'=>array('titre'=> $langs->trans('RelationList') ) // et ça gère même les recherches
		,'eval'=>array('fk_soc_related'=>'_getNomUrlSoc(@fk_soc_related@)') // ceci évalue un code dans le but d'afficher un lien vers la fiche société
	));

	$formCore->end();

	dol_fiche_end();
        llxFooter();


}

function _getNomUrlSoc($fk_soc) { // fonction appelée par le eval de la liste
global $db, $langs;
	$soc= new Societe($db);
	$soc->fetch($fk_soc);

	if($soc->id>0) return $soc->getNomUrl(1);
	else return '';
}

function _card(&$object,&$relation,$mode = 'view') {
	global $langs,$form,$db;

	dol_include_once('/core/lib/company.lib.php');
	
	llxHeader();
	$head = societe_prepare_head($object);
	dol_fiche_head($head, 'relation', '', 0, '');
	
	$formCore=new TFormCore('relation.php', 'formSimple'); // et hop, voilà mon formulaire
	$formCore->set_typeAff($mode); // Si le mode = view alors la classe ne retourne que du texte. Sinon elle retourne des inputs
	echo $formCore->hidden('fk_soc', $object->id); // On stocke les paramètres à rappeler pour le bon affichage de la page
	echo $formCore->hidden('id', $relation->id);
	echo $formCore->hidden('action', 'save');
	
	echo '<h2>'.$langs->trans('RelationTitle').'</h2>';
	
	echo $formCore->texte($langs->trans('Comment'),'comment',$relation->comment,80,80).'<br />'; // ceci sera un texte ou un input en fonction du $mode
	
	if($mode == 'edit') // Zut ce n'est pas un object formCore.
		echo $form->select_company($relation->fk_soc_related, 'fk_soc_related'); // fonction native dolibarr
	else {
		echo _getNomUrlSoc($relation->fk_soc_related); // rappel de cette petite fonction en mode affichage.
	}

	if($mode == 'view'){
		echo '<a href="?action=edit&fk_soc='.$object->id.'&id='.$relation->id.'" class="butAction" >'.$langs->trans('Edit').'</a>';

	}
	else {
		echo $formCore->btsubmit($langs->trans('Save'), 'bt_save');
	}

	$formCore->end();
	
	dol_fiche_end();
	llxFooter();	  
		 	
}

