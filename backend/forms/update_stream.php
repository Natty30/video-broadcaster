<?php

require '../env/config.php';

require '../class/Connection-class.php';
require '../class/Videos-class.php';

if (isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['url']) && !empty($_POST['url']) && isset($_POST['sk']) && !empty($_POST['sk'])) {

   // initialize variable
   $videos = null;
   $response = "An error occured";

   $id = (int)htmlentities(htmlspecialchars($_POST['id']));
   $url = htmlentities(htmlspecialchars($_POST['url']));
   $stream_key = htmlentities(htmlspecialchars($_POST['sk']));

   // update video
   try {

      $videos = new Videos();
      $response = $videos->update($id, $url, $stream_key);

   } catch (Exception $e) {

      echo $e->getMessage();
      exit;

   }

   //return response
	echo $response;

} else {

	echo 'Data sent are incomplete.';

}
