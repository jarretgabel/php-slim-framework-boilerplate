<?php

	/**
   * Slugify
   * @param  [type] $text [description]
   * @return [type]       [description]
   */
  function slugify($text) {
    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
    $text = trim($text, '-');
    if (function_exists('iconv')) {
      $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    }
    $text = strtolower($text);
    $text = preg_replace('~[^-\w]+~', '', $text);

    if (empty($text)) {
      return 'n-a';
    }

    return $text;
  }

  /**
   * [getLocalizedUICopy description]
   * @return [type] [description]
   */
  function getLocalizedUICopy($local, $root) {
    $root = $root ? $root : '';
    $local = $local ? $local : "usa";
    return json_decode(file_get_contents($root . '../app/locale/' . $local . '.json'));
  }

  function postNewsletter($email, $gender, $firstname, $lastname, $birthday, $zipcode) {
    $DBhost = "localhost";
    $DBName = "admin_mb";
    $DBuser = "maxbrenner";
    $DBpass = "ZoeyTheDog123!";

    $DBConn = mysql_connect($DBhost, $DBuser, $DBpass);
    mysql_select_db($DBName, $DBConn);

    $email = stripslashes(strip_tags($email));
    $gender = stripslashes(strip_tags($gender));
    $firstname = stripslashes(strip_tags($firstname));
    $lastname = stripslashes(strip_tags($lastname));
    $birthday = stripslashes(strip_tags($birthday));
    $zipcode = stripslashes(strip_tags($zipcode));

    $sql = "INSERT INTO `newsletter` ";
    $sql .= "(email, firstname, lastname, gender, birthday, zipcode) ";
    $sql .= "VALUES ('$email', '$firstname', '$lastname', '$gender', '$birthday', '$zipcode') ";

    mysql_query($sql);
  }

  function getMainNavPages() {
    $mainNavPages = $GLOBALS['experiencePages'];
    usort($mainNavPages, function($a, $b){
      return strcmp($a['mainNavPosition'], $b['mainNavPosition']);
    });
    return $mainNavPages;
  }

  function getCurrentLocationPage($route) {
    $arr = explode("/", $route);
    for ($i=0; $i < count($arr); $i++) {
      if(strlen($arr[$i]) == 0) {
        $arr1 = array_slice($arr, 0, $i);
        $arr2 = array_slice($arr, $i + 1);
        $arr = array_merge($arr1, $arr2);
      }
    }

    $currentPage = array();
    $countryPage = array();
    if($arr[0] == 'locations') {
      for ($i = 0; $i < count($GLOBALS['locationCollection']); $i++) {
        if(count($arr) > 1 && $GLOBALS['locationCollection'][$i]['slug'] == $arr[1]) {
          $currentPage = $GLOBALS['locationCollection'][$i];
          $countryPage = $currentPage;
          $locations = $GLOBALS['locationCollection'][$i]['locations'];
          if($locations && count($locations) > 0) {
            for ($j = 0; $j < count($locations); $j++) {
              if(count($arr) > 2 && $locations[$j]['slug'] == $arr[2]) {
                $currentPage = $locations[$j];
              }
            }
          }
        }
      }
    }
    return array("country" => $countryPage, "city" => $currentPage);
  }

  function fetch_content( $sUrl ) {

    $aOptions = array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERPWD => "dev" . ":" . "ZoeyTheDog123!",
    CURLOPT_HTTPHEADER => array('Content-type: application/json'),
    CURLOPT_HEADER         => false
    );

    $cUrlHndl = curl_init( $sUrl );
    curl_setopt_array( $cUrlHndl, $aOptions );
    $binaryContents = curl_exec( $cUrlHndl );
    curl_close( $cUrlHndl );

    return $binaryContents;
  }

  function fetch_metadata($route) {

    $arr = explode("/", $route);
    for ($i=0; $i < count($arr); $i++) {
      if(strlen($arr[$i]) == 0) {
        unset($arr[$i]);
      }
    }

    $arr = array_values($arr);

    $metadata = $GLOBALS['experiencePages'][0]['metadata'];

    foreach ($GLOBALS['experiencePages'] as $key => $value) {

      // grab home metadata if root
      if($arr[0] == "" && $value['id'] == 'home') {
        return $value['metadata'];
      }

      if($value['slug'] == $arr[0]) {
        $currentPage = $value;
        $metadata = $value['metadata'];
        foreach ($currentPage['subpages'] as $key => $value) {
          if($value['slug'] == $arr[1]) {
            $metadata = $value['metadata'];
          }
        }
      }
    }

    if($arr[0] == 'locations') {
      for ($i = 0; $i < count($GLOBALS['locationCollection']); $i++) {
        if($GLOBALS['locationCollection'][$i]['slug'] == $arr[1]) {
          $metadata = $GLOBALS['locationCollection'][$i]['metadata'];
          $locations = $GLOBALS['locationCollection'][$i]['locations'];
          if($locations && count($locations) > 0) {
            for ($j = 0; $j < count($locations); $j++) {
              if($locations[$j]['slug'] == $arr[2]) {
                $metadata = $locations[$j]['metadata'];
              }
            }
          }
        }
      }
    }

    foreach ($GLOBALS['corporatePages'] as $key => $value) {

      // grab home metadata if root
      if($arr[1] == "" && $value['id'] == 'home') {
        return $value['metadata'];
      }

      if($value['slug'] == $arr[1]) {
        $currentPage = $value;
        $metadata = $value['metadata'];
      }
    }

    return $metadata;
  }

  function cmp($a, $b) {
    return strcmp($a->name, $b->name);
  }

 ?>