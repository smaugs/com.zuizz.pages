<?php
// diese Klasse erweitert die standart tree klasse um eigenschaften der navigation
class Navigation_tree extends ZUTREE {
	private $table_tree = NULL;
	private $table_details = NULL;
	
	function __autoload() {
		ZU::load_class ( "tree" );
	}
}
?>