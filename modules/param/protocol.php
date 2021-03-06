<?php
/**
 * Created : 13 sept. 2016
 * Creator : quinton
 * Encoding : UTF-8
 * Copyright 2016 - All rights reserved
 */
require_once 'modules/classes/protocol.class.php';
$dataClass = new Protocol ( $bdd, $ObjetBDDParam );
$keyName = "protocol_id";
$id = $_REQUEST [$keyName];

switch ($t_module ["param"]) {
	case "list" :
		
		$vue->set ( $dataClass->getListe (), "data" );
		$vue->set ( "param/protocolList.tpl", "corps" );
		break;
	case "change":
		/*
		 * open the form to modify the record
		 * If is a new record, generate a new record with default value :
		 * $_REQUEST["idParent"] contains the identifiant of the parent record
		 */
		dataRead ( $dataClass, $id, "param/protocolChange.tpl" );
		break;
	case "write":
		/*
		 * write record in database
		 */
		$id = dataWrite ( $dataClass, $_REQUEST );
		if ($id > 0) {
			/*
			 * Traitement du fichier eventuellement joint
			 */
			if ($_FILES ["protocol_file"] ["size"] > 0) {
				try {
					$dataClass->ecrire_document ( $id, $_FILES ["protocol_file"] );
				} catch ( FileException $fe ) {
					$message->set ( $fe->getMessage () );
				} catch ( Exception $e ) {
					$message->setSyslog ( $e->getMessage () );
					$message->set ( "impossible d'enregistrer la pièce jointe" );
				}
			}
			$_REQUEST [$keyName] = $id;
		}
		break;
	case "delete":
		/*
		 * delete record
		 */
		dataDelete ( $dataClass, $id );
		break;
	case "file" :
		$ref = $dataClass->getProtocolFile ( $id );
		if (! is_null ( $ref )) {
			$vue->setDisposition ( "inline" );
			$vue->setFileReference ( $ref );
		} else {
			$module_coderetour = - 1;
			$message->set ( "La description n'existe pas pour le protocole considéré" );
		}
		break;
}
?>