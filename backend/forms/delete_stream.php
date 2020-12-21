<?php

require '../env/config.php';

require '../class/Connection-class.php';
require '../class/Videos-class.php';

if (isset($_POST['id']) && !empty($_POST['id'])) {

   // initialize variable
   $videos = null;
   $response = "An error occured";

   $id = (int)htmlentities(htmlspecialchars($_POST['id']));

   // delete video and directory if stream isn't running
   try {

      $videos = new Videos();
      $s_on = $videos->getStream($id);

      if (!$s_on) {

         $videos->delete($id);
         system("rm -rf ../../upload/".$id."/");
         $response = "ok";

      } else {
         $response = "You must stop the stream before deleting the source video.";
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
