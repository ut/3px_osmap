<?php

########################################################################
# Extension Manager/Repository config file for ext: "3px_osmap"
#
# Auto generated 04-12-2009 20:38
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Simple Map with OSM',
	'description' => 'Show adresses on a map with OpenStreetMap and OpenLayers',
	'category' => 'plugin',
	'author' => 'Ulf Treger',
	'author_email' => 'ulf.treger@3plusx.de',
	'shy' => '',
	'dependencies' => 'tt_address',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.0.1',
	'constraints' => array(
		'depends' => array(
			'tt_address' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:16:{s:9:"ChangeLog";s:4:"46b5";s:10:"README.txt";s:4:"ee2d";s:12:"ext_icon.gif";s:4:"1bdc";s:17:"ext_localconf.php";s:4:"1e5e";s:14:"ext_tables.php";s:4:"2e67";s:14:"ext_tables.sql";s:4:"614c";s:28:"ext_typoscript_editorcfg.txt";s:4:"96b6";s:25:"icon_tx_3pxosmap_maps.gif";s:4:"475a";s:16:"locallang_db.xml";s:4:"1812";s:7:"tca.php";s:4:"dab5";s:19:"doc/wizard_form.dat";s:4:"9546";s:20:"doc/wizard_form.html";s:4:"0b1f";s:29:"pi1/class.tx_3pxosmap_pi1.php";s:4:"7528";s:17:"pi1/locallang.xml";s:4:"355b";s:26:"static/osmap/constants.txt";s:4:"0191";s:22:"static/osmap/setup.txt";s:4:"e9ba";}',
	'suggests' => array(
	),
);

?>