A simple mapping extension for Typo3, based on [OpenStreetMap](www.openstreetmap.org) and [OpenLayers](http://www.openlayers.org/) 

This is the first version, developed for Typo3 v4.10

### Features

* Typo Extension
* Displays Map with Openlayers and Openstreetmap
* Use the Mapnik, Osmarender and CycleMap as base layers
* Use basic map controls like zoom & pan
* Shows one or more address entries with geo-coordinates
* Calcs map extent
* Shows clickable popups with basic address informations  

### Including Software

* [OpenLayers 2.8](http://trac.openlayers.org/wiki/Release/2.8/Notes)
* [OpenStreetMap.js](http://www.openstreetmap.org/openlayers/OpenStreetMap.js)

### Dependencies 

* tt_address v.2.2.1 (http://typo3.org/extensions/repository/view/tt_address/2.2.1/)

### Installation

* Download Extension File of 3px_osmap to your harddisk
* Login to a Typo3 Backend as Administrator
* Install tt_address via the Extension Manager > TYPO3 Extension Repository
* Install 3px_osmap via the Extension Manager > Upload Extension File (.T3X) 
* Select an existing page (or create a new one) 
* Add static template »osmap«
* In case your address data will be stored on a different page: Define PID at constants template with 
** `plugin.tx_3pxosmap_pi1.pid = {pid of your address entries}`
* Creater one oder more tt_address records
* Define at least »name«, »lat« and »lon« values for each entry
* Place the plugin »3px_osmap« on the page
* Define Zoomlevel (1-18, default: 10), map width (default: 600px) and map height (default: 400px)


### TODO:

* Internationalization
* Make Icon selectable via flexform, enable upload of icons
* Add Typo3.org standard documentation & screenshots
