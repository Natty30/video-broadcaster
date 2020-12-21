<?php

require '../env/config.php';

require '../class/Connection-class.php';
require '../class/Videos-class.php';

if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name']) && isset($_POST['url']) && !empty($_POST['url']) && isset($_POST['sk']) && !empty($_POST['sk'])) {

   // initialize variable
   $videos = null;
   $currentId = null;
   $url = htmlentities(htmlspecialchars($_POST['url']));
   $stream_key = htmlentities(htmlspecialchars($_POST['sk']));

   // get file infos
   $filename = $_FILES['file']['name'];
   $response = "An error occured";

   $imageFileType = pathinfo($filename, PATHINFO_EXTENSION);
   $imageFileType = strtolower($imageFileType);

   // insert new videos and get it id
   try {

      $videos = new Videos();
      $videos->insert($imageFileType, $url, $stream_key);
      $currentId = $videos->getLastID();

   } catch (Exception $e) {

      echo "Error : ".$e->getMessage();
      exit;

   }

   // create directory and get location
   $path = "../../upload/".$currentId."/";
   mkdir($path, 0777, true);
   $location = $path."video.".$imageFileType;

   // make some verification
   $valid_extensions = array("mp4", "flv", "ogg");

   if (in_array(strtolower($imageFileType), $valid_extensions)) {

      if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {

         $response = 'ok';

      } else {
         
   		$response = 'File upload failed, please try again.';
   	
      }

   } else {
		$response = 'Please select a mp4, ogg or flv video to upload.';
   }

   // create the stream file
   $stream_file_path = $path."stream.sh";
   $stream_file_content = "ffmpeg -stream_loop -1 -re -i /var/www/html/fiverr/youtube-multistream/upload/".$currentId."/video.".$imageFileType." -vcodec copy -c:a aac -b:a 160k -ar 44100 -strict -2 -f flv ".$url."/".$stream_key;
   file_put_contents($stream_file_path, $stream_file_content);

   // if upload failed, delete info and directory
   if ($response !== 'ok') {

      try {

         $videos->delete($id);
         rmdir("./upload/".$currentId);

      } catch (Exception $e) {

         echo "Error : ".$e->getMessage();
         exit;

      }

   }

   //return response
	echo $response;

} else {

	echo 'Data sent are incomplete.';

}
