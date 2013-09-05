<?php

$page = $this->parameter['page'];
$current_page = $GLOBALS['page']['page_id'];
$current_lang =

$this->data['navtitle'] = $GLOBALS['page']['nav_title'];

$fulltree = ORM::for_table('pages_tree')->raw_query("SELECT d.page_title,
	p.root,
	p.active,
	p.type,
	p.is_active,
	p.route,
	p.lft,
	p.rgt,
	p.page_id id,
	d.*
FROM pages_tree h, pages_tree p LEFT JOIN pages_tree_details d ON (d.page_id = p.page_id AND d.lang=:lang AND d.display_in_nav=1)
WHERE h.page_id = :page_id
AND p.lft > h.lft
AND p.rgt < h.rgt
and p.root = h.root
ORDER by p.lft ASC", array('page_id' => $page, 'lang' => $_SESSION ['ZUIZZ'] ['LANG'] ['interface_lang']))->find_array();

$level = 1;
$lright[1] = $fulltree[0]['rgt'];
$lastright = $fulltree[0]['rgt'];
$pid[1] = $page;
$pidlevel = 1;
$structure = array();
$idtree = array();

foreach ($fulltree as $key => $node) {
    if (!ZU::check_permission(100, $node['id'], 1)) {
        unset($fulltree[$key]);
        unset($node);
    } else {
        $idtree[$node['id']] = $node;
        $idtree[$node['id']]['in_path'] = false;


        // eine ebene in die Tiefe gehen
        if ($node['rgt'] < $lastright) {
            $level++;
            reset($lright);
            $lright[$level] = $node['rgt'];

        } else {
            // gleiche Ebene oder höher
            krsort($lright);
            foreach ($lright as $lvl => $rgt) {
                if ($rgt + 1 == $node['lft']) {
                    $lright[$lvl] = $node['rgt'];
                    $level = $lvl;
                    break;
                }
            }
        }


        $idtree[$node['id']]['level'] = $level;

        $lastright = $node['rgt'];

        // PIDs
        if ($pidlevel < $level) {
            $pid[$level] = $lastid;
        }

        $idtree[$node['id']]['pid'] = $pid[$level];
        $pidlevel = $level;
        $lastid = $node['id'];

        $structure[$idtree[$node['id']]['pid']]['level'] = $idtree[$node['id']]['level'];
        // pages registrieren (mit active und inactive)
        if ($node['page_id']) {
            $structure[$idtree[$node['id']]['pid']]['subs'][$node['id']] = true;
        } else {
            $structure[$idtree[$node['id']]['pid']]['subs'][$node['id']] = false;
        }


        if ($node['rgt'] - $node['lft'] == 1) {
            $idtree[$node['id']]['have_subnodes'] = false;
        }else{
            $idtree[$node['id']]['have_subnodes'] = true;
        }

    }
}

foreach ($structure as $page => $node) {
    if (array_sum($node['subs']) == 0) {
        $idtree[$page]['no_active_subs'] = true;
    }
}


// Pfad holen und markieren

$path = ORM::for_table('pages_tree')->raw_query("SELECT
	p.page_id id
FROM pages_tree h, pages_tree p LEFT JOIN pages_tree_details d ON (d.page_id = p.page_id AND d.lang='de')
WHERE h.page_id = :current_page
AND p.lft <= h.lft
AND p.rgt >= h.rgt
and p.root = h.root", array('current_page' => $current_page))->find_array();


foreach ($path as $item) {
    if (isset($idtree[$item['id']])) {
        $idtree[$item['id']]['in_path'] = true;
    }
}
if (isset($idtree[$current_page])) {
    $idtree[$item['id']]['selected'] = true;
}

$this->data['tree'] = $idtree;
$this->data['tree_start'] = $pid[1];
$this->data['tree_structure'] = $structure;


$this->fetch();