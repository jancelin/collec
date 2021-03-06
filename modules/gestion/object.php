<?php
/**
 * Created : 16 août 2016
 * Creator : quinton
 * Encoding : UTF-8
 * Copyright 2016 - All rights reserved
 */
include_once 'modules/classes/object.class.php';
$dataClass = new Object ( $bdd, $ObjetBDDParam );
$keyName = "uid";
$id = $_REQUEST [$keyName];
switch ($t_module ["param"]) {
	case "getDetailAjax" :
		/**
		 * Retourne le detail d'un objet a partir de son uid
		 * (independamment du type : sample ou container)
		 */
		$vue->set ( $dataClass->getDetail ( $id, $_REQUEST ["is_container"] ) );
		break;
	case "printLabel" :
		/*
		 * Test de generation des qrcodes
		 */
		$data = $dataClass->generateQrcode ( $_REQUEST ["uid"] );
		/*
		 * Generation du fichier xml
		 */
		if (count ( $data ) > 0) {
			try {
				$xml_id = bin2hex ( openssl_random_pseudo_bytes ( 6 ) );
				/*
				 * Preparation du fichier xml
				 */
				$doc = new DOMDocument ( '1.0', 'utf-8' );
				$objects = $doc->createElement ( "objects" );
				$label_id = 0;
				foreach ( $data as $object ) {
					/*
					 * Mise a jour du modele d'etiquette
					 */
					if ($object ["label_id"] > 0 && $label_id == 0)
						$label_id = $object ["label_id"];
					$item = $doc->createElement ( "object" );
					foreach ( $object as $key => $value ) {
						if (strlen ( $key ) > 0 && strlen ( $value ) > 0) {
							$elem = $doc->createElement ( $key, $value );
							$item->appendChild ( $elem );
						}
					}
					$objects->appendChild ( $item );
				}
				$doc->appendChild ( $objects );
				if ($label_id > 0) {
					$xmlfile = $APPLI_temp . '/' . $xml_id . ".xml";
					$doc->save ( $xmlfile );
					/*
					 * Recuperation du fichier xsl
					 */
					$xslfile = $APPLI_temp . '/' . $label_id . ".xsl";
					if (! file_exists ( $xslfile )) {
						require_once 'modules/classes/label.class.php';
						$label = new Label ( $bdd, $ObjetBDDParam );
						$dataLabel = $label->lire ( $label_id );
						$handle = fopen ( $xslfile, 'w' );
						fwrite ( $handle, $dataLabel ["label_xsl"] );
						fclose ( $handle );
					}
					/*
					 * Generation de la commande de creation du fichier pdf
					 */
					$pdffile = $APPLI_temp . '/' . $xml_id . ".pdf";
					$command = $APPLI_fop . " -xsl $xslfile -xml $xmlfile -pdf $pdffile";
					exec ( $command );
					if (! file_exists ( $xmlfile )) {
						$message->set ( "Impossible de generer le fichier pdf" );
						$module_coderetour = - 1;
					} else {
						$vue->setFilename ( $pdffile );
						$vue->setDisposition ( "inline" );
					}
				} else {
					$message->set ( "Pas de modèle d'étiquettes décrit" );
					$module_coderetour = - 1;
				}
			} catch ( Exception $e ) {
				$module_coderetour = - 1;
				$message->set ( $e->getMessage () );
				if ($APPLI_modeDeveloppement == true)
					$message->set ( $e->getTraceAsString () );
			}
		} else {
			$module_coderetour = - 1;
		}
		/*
		 * Suppression des qrcodes
		 */
		foreach ($data as $value) {
			unlink($APPLI_temp."/".$value["uid"].".png");
		}
		/*
		 * suppression du modele d'etiquette (sinon, impossible de mettre au point un 
		 * nouveau modele ou de prendre en compte les modifications)
		 */
		unlink($xslfile);
		break;
	case "exportCSV" :
		$data = $dataClass->getForPrint ( $_REQUEST ["uid"] );
		if (count ( $data ) > 0) {
			$vue->set ( $data );
			$vue->setFilename ( "printlabel.csv" );
		} else {
			unset ( $vue );
			$module_coderetour = - 1;
		}
		break;
}
?>