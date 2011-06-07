<?php

abstract class Geocoder {
	
	const YAHOO_API_KEY = 'YOUR_YAHOO_API_KEY';
	
	public function getLatLng($location) {
		$gc = self::getGoogleLatLng($location);
		return $gc !== null ? $gc : self::getYahooLatLng($location);
	}
	

	/**
	 * @param string $address
	 */
	function coords($location) {
		$oXmlCoords = simplexml_load_file('http://maps.googleapis.com/maps/api/geocode/xml?sensor=false&address='.urlencode($location));
		if($oXmlCoords->status == 'OK') { 
			$oCoords = $oXmlCoords->result->geometry->location;
			return array((float) $oCoords[0]->lat, (float) $oCoords[0]->lng);
		}
		return null;
	}
	
	public function getYahooLatLng($location) {
		$wsurl = 'http://local.yahooapis.com/MapsService/V1/geocode?location=%s&appid=%s&output=php';
		$data = unserialize(file_get_contents(sprintf($wsurl, urlencode($location), self::YAHOO_API_KEY)));
		$coord = $data === false ? null : array((float)$data[ResultSet][Result][Latitude],(float)$data[ResultSet][Result][Longitude]);
		return $coord;
	}
	
}

?>