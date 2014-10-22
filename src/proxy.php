<?php
  // This file makes possible to access Map Servers
  // that doesnt contains a crossdomain.xml file, to
  // avoid security problems of Flash
  // Those servers declared into map_conf.xml file that
  // have setted the proxied property as true, will make
  // its GetCapabilities Request throw use of this proxy

  /* This is the WMS Server address */
  $wmsURL = "";
  // Gets the Mapserver url from the url passed by Geoar
  foreach ( $_REQUEST as $key => $value ) {
    if ( $key == "mapserver") {
      $wmsURL .= $value;
    }
  }

  /* Replace "\\" by "\".
     Typically introduced by map specification in UNM server
     in Windows environment. */
  if(get_magic_quotes_gpc())
    $wmsURL = stripslashes($wmsURL);

  //$fh = fopen("proxy.log", "ab+");
  //$timestamp = strftime("[%Y-%m-%d %H:%M:%S]");
  //print_r($_REQUEST);

  // Load each parameter into the URL
  $request = "";
  $sep = "";
  foreach ( $_REQUEST as $key => $value ) {
    //echo "Key: ".$key.", Value: ".$value."<br>";
    /* Remove the "mapserver" key from the request */
    if ( $key != "mapserver") {
      $request .= $sep.$key."=".$value;
      $sep = "&";
    //  fwrite($fh, $timestamp.": ".$request."\n");
    }
  }

  $format = "text/xml";
  if (array_key_exists('format', $_REQUEST))
  	$format = $_REQUEST["format"];
  else if (array_key_exists('FORMAT', $_REQUEST))
  	$format = $_REQUEST["FORMAT"];
  else
   $format = "text/xml";

  $sep = "?";
  if ( strrchr($wmsURL, "?") != false ) $sep = "&";
  if ( $wmsURL{strlen($wmsURL)-1} == "?" ) $sep = "";

  /* Optionally write all requests in a log file */
  //fwrite($fh, $timestamp.": ".$wmsURL.$sep.$request."\n");
  //fclose($fh);
  header("Content-type: $format");
  readfile($wmsURL.$sep.$request);
?>