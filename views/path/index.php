<?php
echo '<br>';
echo '<br>';
echo '<br>';
if (isset($this->parameter['page_id'])) {
    $current_page = $this->parameter['page_id'];
} else {
    $current_page = $GLOBALS['page']['page_id'];
}

// Pfad holen

$this->data['path'] = ORM::for_table('pages_tree')->raw_query("SELECT
	d.page_title,
	d.nav_title,
	d.page_id,
	p.root,
	p.active,
	p.type,
	p.is_active,
	p.route,
	p.lft,
	p.rgt,
	p.page_id id,
	d.icon
FROM pages_tree h, pages_tree p LEFT JOIN pages_tree_details d ON (d.page_id = p.page_id AND d.lang='de')
WHERE h.page_id = :current_page
AND p.lft <= h.lft
AND p.rgt >= h.rgt
and p.root = h.root ORDER by p.lft ASC", array('current_page' => $current_page))->find_array();


$this->fetch();