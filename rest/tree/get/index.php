<?php


$this->data = ORM::for_table('pages_tree')->where('page_id',$this->identifier)->find_one()->as_array();

switch ($this->mimetype) {
	case "json":
		header ( 'Content-type: application/json' );
		$this->contentbuffer = json_encode($this->data);
	;
	break;
	case "html":
		$this->fetch();
	;

	break;

	default:
		$this->fetch();
	break;
}
