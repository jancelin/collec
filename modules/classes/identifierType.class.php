<?php
/**
 * Created : 28 sept. 2016
 * Creator : quinton
 * Encoding : UTF-8
 * Copyright 2016 - All rights reserved
 */
class IdentifierType extends ObjetBDD {
	function __construct($bdd, $param = array()) {
		$this->table = "identifier_type";
		$this->colonnes = array (
				"identifier_type_id" => array (
						"type" => 1,
						"key" => 1,
						"requis" => 1,
						"defaultValue" => 0
				),
				"identifier_type_name" => array (
						"requis" => 1
				),
				"identifier_type_code" => array (
						"type" => 0,
						"requis" => 1
				)
		);
		parent::__construct ( $bdd, $param );
	}
}
?>