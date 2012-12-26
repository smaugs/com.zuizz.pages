<?php

// Parameter :: Style
$style = XT::getParam ( 'style' ) != '' ? XT::getParam ( 'style' ) : 'default.tpl';

$result = XT::query ( "
    SELECT
        *
    FROM
        " . XT::getTable ( "" ), __FILE__, __LINE__ );

$data = array ();

while ( $row = $result->FetchRow () ) {
	$data ['id'] = $row;
}

$data = XT::getQueryData ( $result, 'id' );

// build content
$content = XT::build ( $style );
?>