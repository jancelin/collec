<?php
/**
 * Created : 14 sept. 2016
 * Creator : quinton
 * Encoding : UTF-8
 * Copyright 2016 - All rights reserved
 */
require_once 'modules/classes/operation.class.php';
$dataClass = new Operation($bdd,$ObjetBDDParam);
$keyName = "operation_id";
$id = $_REQUEST[$keyName];

switch ($t_module["param"]) {
	case "list":
		$vue->set( $dataClass->getListe(),"data" );
		$vue->set( "param/operationList.tpl", "corps");
		break;
	case "change":
		/*
		 * open the form to modify the record
		 * If is a new record, generate a new record with default value :
		 * $_REQUEST["idParent"] contains the identifiant of the parent record
		 */
		dataRead($dataClass, $id, "param/operationChange.tpl", $_REQUEST["protocol_id"]);
		/*
		 * Recuperation de la liste des protocoles
		 */
		require_once 'modules/classes/protocol.class.php';
		$protocol = new Protocol($bdd, $ObjetBDDParam);
		$vue->set($protocol->getListe("protocol_year desc, protocol_name, protocol_version desc"), "protocol");
		break;
	case "write":
		/*
		 * write record in database
		 */
		$id = dataWrite($dataClass, $_REQUEST);
		if ($id > 0) {
			$_REQUEST[$keyName] = $id;
		}
		break;
	case "delete":
		/*
		 * delete record
		 */
		dataDelete($dataClass, $id);
		break;
}
?>