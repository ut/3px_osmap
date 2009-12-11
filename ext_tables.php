<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
$tempColumns = Array (
	"tx_3pxosmap_lat" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:3px_osmap/locallang_db.xml:tt_address.tx_3pxosmap_lat",		
		"config" => Array (
			"type" => "input",	
			"size" => "30",	
			"eval" => "trim",
		)
	),
	"tx_3pxosmap_lon" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:3px_osmap/locallang_db.xml:tt_address.tx_3pxosmap_lon",		
		"config" => Array (
			"type" => "input",	
			"size" => "30",	
			"eval" => "trim",
		)
	),
);


t3lib_div::loadTCA("tt_address");
t3lib_extMgm::addTCAcolumns("tt_address",$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes("tt_address","tx_3pxosmap_lat;;;;1-1-1, tx_3pxosmap_lon");


t3lib_extMgm::allowTableOnStandardPages('tx_3pxosmap_maps');


t3lib_extMgm::addToInsertRecords('tx_3pxosmap_maps');


t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages';

$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1']='pi_flexform';  
t3lib_extMgm::addPlugin(array('LLL:EXT:3px_osmap/locallang_db.xml:tt_content.list_type_pi1', $_EXTKEY.'_pi1'),'list_type');

t3lib_extMgm::addStaticFile($_EXTKEY,'static/osmap/', 'osmap');
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:'.$_EXTKEY.'/flexform_ds_pi1.xml');

?>