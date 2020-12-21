<?php

require '../env/config.php';

require '../class/Connection-class.php';
require '../class/Videos-class.php';

if (isset($_POST['id']) && !empty($_POST['id'])) {

   // initialize variable
   $videos = null;
   $response = "An error occured";

   $id = (int)htmlentities(htmlspecialchars($_POST['id']));

   // stop stream if stream is running
   try {

      $videos = new Videos();
      $s_on = $videos->getStream($id);

      if ($s_on) {

         $pid = $videos->getPID($id);

         if ($pid !== 1 && $pid !== null) system('kill -9 '.$pid);

         $videos->setPID($id, null);

         $videos->setStream($id);

         $response = "ok";

      } else {
         $response = "The video isn't streaming.";
      }

   } catch (Exception $e) {

      echo "Error : ".$e->getMessage();
      exit;

   }

   //return response
	echo $response;

} else {

	echo 'Data sent are incomplete.';

}
