<?php

require '../env/config.php';

require '../class/Connection-class.php';
require '../class/Videos-class.php';

if (isset($_POST['id']) && !empty($_POST['id'])) {

   // initialize variable
   $videos = null;
   $response = "An error occured";

   $id = (int)htmlentities(htmlspecialchars($_POST['id']));
   $pid = null;
   
   // start stream if stream isn't running
   try {

      $videos = new Videos();
      $s_on = $videos->getStream($id);

      if (!$s_on) {

         $descriptorspec = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w"),
            2 => array("file", "/tmp/error-output.txt", "a")
         );

         $cwd = '/tmp';

         $process = proc_open('bash /var/www/html/fiverr/youtube-multistream/upload/'.$id.'/stream.sh', $descriptorspec, $pipes);

         if (is_resource($process)) {

            $info = proc_get_status($process);
            $pid = $info['pid']+2;

         } 

         $videos->setPID($id, $pid);
         $videos->setStream($id);

         $response = "ok";

      } else {
         $response = "The video is already streamed.";
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
