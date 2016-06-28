<?php
/**
 * Created : 8 juin 2016
 * Creator : quinton
 * Encoding : UTF-8
 * Copyright 2016 - All rights reserved
 */
include_once 'modules/classes/container.class.php';
require_once 'modules/classes/object.class.php';
$dataClass = new Container ( $bdd, $ObjetBDDParam );
$keyName = "uid";
$id = $_REQUEST [$keyName];
$_SESSION["moduleParent"] = "container";

switch ($t_module ["param"]) {
	case "list":
		/*
		 * Display the list of all records of the table
		 */
		$_SESSION ["searchContainer"]->setParam ( $_REQUEST );
		$dataSearch = $_SESSION ["searchContainer"]->getParam ();
		if ($_SESSION ["searchContainer"]->isSearch () == 1) {
			$data = $dataClass->containerSearch( $dataSearch );
			$smarty->assign ( "containers", $data );
			$smarty->assign ( "isSearch", 1 );
		}
		$smarty->assign ( "containerSearch", $dataSearch );
		$smarty->assign ( "corps", "gestion/containerList.tpl" );
		/*
		 * Ajout des listes deroulantes
		 */
		include 'modules/gestion/container.functions.php';
		break;
	case "display":
		/*
		 * Display the detail of the record
		 */
		$data = $dataClass->lire ( $id );
		$smarty->assign ( "data", $data );
		/*
		 * Recuperation des conteneurs parents
		 */
		$smarty->assign("parents", $dataClass->getAllParents($data["uid"]));
		/*
		 * Recuperation des conteneurs  et des échantillons contenus
		 */
		$smarty->assign("containers", $dataClass->getContentContainer($data["uid"]));
		$smarty->assign("samples", $dataClass->getContentSample($data["uid"]));
		/*
		 * Recuperation des evenements
		 */
		require_once 'modules/classes/event.class.php';
		$event = new Event($bdd, $ObjetBDDParam);
		$smarty->assign("events", $event->getListeFromUid($data["uid"]));
		$smarty->assign("moduleParent", "container");
		$smarty->assign ( "corps", "gestion/containerDisplay.tpl" );
		break;
	case "change":
		/*
		 * open the form to modify the record
		 * If is a new record, generate a new record with default value :
		 * $_REQUEST["idParent"] contains the identifiant of the parent record
		 */
		$data = dataRead ( $dataClass, $id, "gestion/containerChange.tpl" );
		//$object = new Object ( $bdd, $ObjetBDDParam );
		//$smarty->assign ( "objectData", $object->lire ( $data ["uid"] ) );
		include 'modules/gestion/container.functions.php';
		break;
	case "write":
		/*
		 * write record in database
		 */
		$id = dataWrite ( $dataClass, $_REQUEST );
		printr($id);
		if ($id > 0) {
			$_REQUEST [$keyName] = $id;
		}
		break;
	case "delete":
		/*
		 * delete record
		 */
		/*
		 * Recherche si le conteneur est reference
		 */
		require_once 'modules/classes/storage.class.php';
		$storage = new Storage ( $bdd, $ObjetBDDParam );
		try {
			$nb = $storage->getNbFromContainer ( $id );
		} catch (Exception $e){
			$nb = 0;
		}
		if ($nb > 0) {
			$message = "Le conteneur est référencé dans les mouvements et ne peut être supprimé";
			$module_coderetour = - 1;
		} else
			dataDelete ( $dataClass, $id );
		break;
}
?>