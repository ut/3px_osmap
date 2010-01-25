<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Ulf Treger <ulf.treger@3plusx.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'OSM Map' for the '3px_osmap' extension.
 *
 * @author	Ulf Treger <ulf.treger@3plusx.de>
 * @package	TYPO3
 * @subpackage	tx_3pxosmap
 */
class tx_3pxosmap_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_3pxosmap_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_3pxosmap_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = '3px_osmap';	// The extension key.
	var $pi_checkCHash = true;
	
	function init(){
     $this->pi_initPIflexForm();
     $this->lConf = array(); 
     $piFlexForm = $this->cObj->data['pi_flexform'];
     foreach ( $piFlexForm['data'] as $sheet => $data ) {
         foreach ( $data as $lang => $value ) {
             foreach ( $value as $key => $val ) {
                 $this->lConf[$key] = $this->pi_getFFvalue($piFlexForm, $key, $sheet);
             }
         }
     }
     // t3lib_div::debug($this->lConf);


   }
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content,$conf)	{
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		$pid = $this->conf['pid'];
		$this->init();
		$addresses  = array(); 
    $addresses = $this->getAddress($pid);
		
		$content = $this->makeMap($addresses);
		
		return $this->pi_wrapInBaseClass($content);
	}
	function getAddress($pid='') {
    $table = 'tt_address';
    // via flexform
    $pages = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'pages', 'sDEF');
    // via ts 
    if (!$pages) {
      $pages = $pid;
    }
    // the page itself
    if (!$pages) {
        $pages = $this->pi_getPidList($this->cObj->data['pages'],$this->cObj->data['recursive']);
    }
    //t3lib_div::debug($this->cObj->data['pi_flexform']); 
    $where = ' pid IN ('.$pages.')';
     
    $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*',$table,$where,'','','');
    $i = 0;
    while ($row= $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
      if ( $row['name'] ) {
         $addresses[$i]['name'] = $row['name'];
      } else {
         $addresses[$i]['name'] = $row['first_name']." ".$row['last_name'];
      }
      $addresses[$i]['uid'] = $row['uid'];
      $addresses[$i]['address'] = $row['address'];
      $addresses[$i]['zip'] = $row['zip'];
      $addresses[$i]['city'] = $row['city'];
      $addresses[$i]['lat'] = $row['tx_3pxosmap_lat'];
      $addresses[$i]['lon'] = $row['tx_3pxosmap_lon'];
      $i++;
    }
    return $addresses;
  
  }

  function makeMap($addresses) {
    $script_url = t3lib_extMgm::siteRelPath($this->extKey).'scripts/OpenLayers/OpenLayers.js';
    $GLOBALS['TSFE']->additionalHeaderData[$this->prefixId] = '<script type="text/javascript" src="'.$script_url.'" language="JavaScript"></script><script type="text/javascript" src="http://openstreetmap.org/openlayers/OpenStreetMap.js" language="JavaScript"></script>';
    $iconUrl = "typo3conf/ext/3px_osmap/res/".$this->conf['iconFile'];
    
    $zoom = $this->conf['zoom'] ? $this->conf['zoom'] : 10;
    $zoom = $this->lConf['zoom'] ? $this->lConf['zoom'] : $zoom;
    $mapWidth = $this->conf['mapWidth'] ? $this->conf['mapWidth'] : 600;
    $mapWidth = $this->lConf['mapWidth'] ? $this->lConf['mapWidth'] : $mapWidth;
    $mapHeight = $this->conf['mapHeight'] ? $this->conf['mapHeight'] : 400;
    $mapHeight = $this->lConf['mapHeight'] ? $this->lConf['mapHeight'] : $mapWidth;
    
    $map = '
    <div id="tx_3pxosmap_pi1_map" style="width:'.$mapWidth.'px; height:'.$mapWidth.'px;">
    <script type="text/javascript">
        function Lon2Merc(lon) {
          return 20037508.34*lon/180;
        }
        function Lat2Merc(lat) {
          var PI = 3.14159265358979323846;
          lat = Math.log(Math.tan((90 + lat)*PI/360))/(PI/180);
          return 20037508.34*lat/180;
        }
        var currentPopup;
        var zoom = '.$zoom.';
            var map = new OpenLayers.Map("tx_3pxosmap_pi1_map", {
            controls: [
              new OpenLayers.Control.KeyboardDefaults(),
              new OpenLayers.Control.MouseDefaults(),
              new OpenLayers.Control.LayerSwitcher(),
              new OpenLayers.Control.PanZoomBar()],
              maxExtent:
              new OpenLayers.Bounds(-20037508.34, -20037508.34,
          			   20037508.34,  20037508.34),
              numZoomLevels: 18,
              maxResolution: 156543,
              units: "meters",
              projection: "EPSG:41001"} );
           
            var mapnik_layer = new OpenLayers.Layer.OSM.Mapnik("Mapnik");
            var tah_layer = new OpenLayers.Layer.OSM.Osmarender("Osmarender");
            var cyclemap_layer = new OpenLayers.Layer.OSM.CycleMap("CycleMap");
   
            map.addLayers([mapnik_layer, tah_layer, cyclemap_layer]);

            var size = new OpenLayers.Size('.$this->conf['iconWidth'].','.$this->conf['iconHeight'].');
            var offset = new OpenLayers.Pixel(-((size.w/2)-0), -(size.h-0));
            var offset = 0;
            var icon = new OpenLayers.Icon("'.$iconUrl.'",size,offset);

            icon.setOpacity(0.8);
            var markers = new OpenLayers.Layer.Markers( "map" );
            map.addLayer(markers);
            
            
            var markerClick = function (evt) {
                if (this.popup == null) {
                    this.popup = this.createPopup(true);
                    this.popup.setOpacity(0.8);
                    map.addPopup(this.popup);
                    this.popup.show();
                } else {
                    this.popup.toggle();
                }
                currentPopup = this.popup;
                OpenLayers.Event.stop(evt);
            };
      ';
       $c_lat = 0; $y = 0;
      foreach ($addresses as $address) {
        $map .= '
 
            var coords =  new OpenLayers.LonLat(Lon2Merc('.$address['lon'].'),Lat2Merc('.$address['lat'].'));
            var feature = new OpenLayers.Feature('.$address['uid'].',coords);
            feature.popupClass = OpenLayers.Class(OpenLayers.Popup.FramedCloud, { minSize: new OpenLayers.Size(150, 100) });
            feature.data.popupContentHTML = "<div class=\"popup\"><strong>'.$address['name'].'</strong><br />'.$address['address'].', '.$address['zip'].' '.$address['city'].'</div>";

            // marker = feature.createMarker();
            var marker = new OpenLayers.Marker(coords,icon.clone());
            marker.feature = feature;

            markers.addMarker(marker);
            marker.events.register("click", feature, markerClick);

              
        ';
        $c_lat =  $c_lat + $address['lat'];
        $c_lon =  $c_lon + $address['lon'];
        $y++;

          
      }
      // calc map center 
      $c_lat = $c_lat / $y;
      $c_lon = $c_lon / $y;
      // recalc zoom!!!
      
      $map .= '
            var x = Lon2Merc('.$c_lon.');
            var y = Lat2Merc('.$c_lat.');
            map.setCenter(new OpenLayers.LonLat(x, y), zoom);
  
      </script>
      </div>
      <div id="tx_3pxosmap_pi1_credits">karte von <a href="http://www.openstreetmap.de/">openstreetmap</a>, anzeige mit <a href="http://www.openlayers.org/">openlayers</a></div>
      
    
    ';
    return $map;  
  }
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/3px_osmap/pi1/class.tx_3pxosmap_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/3px_osmap/pi1/class.tx_3pxosmap_pi1.php']);
}

?>
