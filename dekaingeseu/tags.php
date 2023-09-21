<?php 
session_start();
include 'dekaingeseu.php';
include '../functions.php';

function gif2jpg($originalFile, $outputFile, $quality=80) {
    $image = imagecreatefromgif($originalFile);
    imagejpeg($image, $outputFile, $quality);
    imagedestroy($image);
}

function makeThumb($sourceImage,$destImage) {
    list($width,$height) = getimagesize($sourceImage);
    $ratio = $height/$width;
    Debug("$sourceImage is $width x $height [ratio $ratio]");
    $newWidth = 30;
    $newHeight = $newWidth * $ratio; 
    $img = imagecreatefromjpeg($sourceImage);
    // create a new temporary image
    Debug("Create thumb with $newHeight x $newWidth for $sourceImage");
    $tmp_img = imagecreatetruecolor($newHeight,$newWidth);
    // copy and resize old image into new image
    imagecopyresized( $tmp_img, $img, 0, 0, 0, 0,$newHeight,$newWidth, $width, $height );
    // use output buffering to capture outputted image stream
    ob_start();
    $quality = 90;
    imagejpeg($tmp_img,$destImage,$quality);
    imagedestroy($tmp_img);
    imagedestroy($img);
}

# make sure we have a jpg version and a thumbnail
function prepare_pic($img,$img_info) {
  $path_parts = pathinfo($img);
  $ext = $path_parts['extension'];
  if ($ext != "jpg") {
    switch ($img_info[2]) {
      case IMAGETYPE_GIF  : $src = imagecreatefromgif($img);  break;
      case IMAGETYPE_JPEG : $src = imagecreatefromjpeg($img); break;
      case IMAGETYPE_PNG  : 
        $tmp = imagecreatefrompng($img);  
        $src = imagecreatetruecolor(imagesx($tmp), imagesy($tmp));
        imagefill($src, 0, 0, imagecolorallocate($src, 255, 255, 255));
        imagealphablending($src, TRUE);
        imagecopy($src, $tmp, 0, 0, 0, 0, imagesx($tmp), imagesy($tmp));
        imagedestroy($tmp);
        break;
      default : 
        err_msg("Unknown filetype.");
        die("Unknown filetype");
    }
    $out = $path_parts['dirname'] . "/" . $path_parts['filename'] . ".jpg";
    Debug("Creating $out from $img");
    $quality=90;
    imagejpeg($src, $out, $quality);
    imagedestroy($src);
  } else {
    $out = $img;
  }

  $thumb = $path_parts['dirname'] . "/thumbs/" . $path_parts['filename'] . ".jpg";
  makeThumb($out,$thumb);
  return 1;
}

function png2jpg($filePath,$out,$quality=80) {
  $image = imagecreatefrompng($filePath);
  $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
  imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
  imagealphablending($bg, TRUE);
  imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
  imagedestroy($image);
  imagejpeg($bg, $out, $quality);
  imagedestroy($bg);
}

function add_instructions($tag) {
  print "<p><ul><li>Please choose a $tag and upload a picture of it.
         </ul>";
}

function upload_file($target_file,$imageFileType) {
  // Check if file already exists
  $uploadOk = 1;
  if (file_exists($target_file)) {
      echo "File $target_file already exists. Will replace.<br>";
      $uploadOk = 1;
  }
  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 5000000) {
      err_msg( "Sorry, your file is too large." );
      $uploadOk = 0;
  }
  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" ) {
      err_msg( "Sorry, only JPG, JPEG, PNG & GIF files are allowed." );
      $uploadOk = 0;
  }
  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
      err_msg( "Sorry, your file was not uploaded." );
  // if everything is ok, try to upload file
  } else {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
          echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.<br>";
      } else {
          $uploadOk=0;
          err_msg( "Sorry, there was an error uploading your file." );
      }
  }

  return $uploadOk;
}

function err_msg($msg) {
  print "<h2><b>ERROR</b>: $msg";
}

function tag_record_task($result) {
  #print "Hey, something was uploaded!<br>";
  #print_r($_POST);
  #print_r($_FILES);

  $success = 1;
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
      echo "File is an image - " . $check["mime"] . ".<br>";
      $success = 1;
  } else {
      err_msg( "File is not an image" );
      $success = 0;
  }
  $which = $_POST['which'];
  list($wid,$id) = explode(":",$which);
  $target_dir = "../uploads/pics/";
  $imageFileType = pathinfo($_FILES['fileToUpload']['name'],PATHINFO_EXTENSION);
  $target_file = "$target_dir/$wid.$imageFileType";

  if ($success == 1) {
    Debug("Try to upload $target_file of type $imageFileType");
    $success = upload_file($target_file,$imageFileType);
  }

  if ($success == 1) {
    $success = prepare_pic($target_file,$check);
  } else {
    err_msg("Could not upload pictures.<br>");
  }

  if ($success == 1) {
    if (file_exists($target_file)) {
      $success == 1;
    } else {
      $success == 0;
      err_msg("File was not uploaded successfully.");
    }
  }

  if ($success == 1) { 
    echo "Thanks for uploading $target_file!<br>";
    echo "Here is a preview to make sure it worked:<br>";
    echo "<img width=30% src=$target_file><br>";
  }
  $_SESSION['taskid'] = $id;
  $who = $_SESSION['worker'];
  $update = "uploaded=$success,assignee='$who'";
  Debug("Uploaded $target_file: $success");
  return $update;
}

function tag_make_task($db_row) {
  $tag = $GLOBALS['TAG'];
  add_instructions($tag);
  $regexp = "regexp '\\\\b" . $tag . "\\\\b'";
  $q = "select a.pal,b.allwid,b.id,a.eng,a.pdef from all_words3 a,pictures b where a.pos not like 'var.' and (b.uploaded != 1 and b.uploaded != 2) and b.tag $regexp and a.id=b.allwid order by a.pal";
  #echo "$q";
  list($res,$num_rows) = check_table($q);


  $style = "style='background-color:blue'"; 
  $submit = submitButton("Upload",$tag,$style); 

  print "Choose which $tag and upload a picture of it:
          <form method='post' enctype='multipart/form-data'>
          <select name='which'>
        ";
  while($row = mysqli_fetch_row($res)) {
    if (strlen($row[3]) > 0) {
      $eng = " [" . substr($row[3],0,50) . "]";  
    } else {
      $eng = " [" . substr($row[4],0,50) . "]";  
    }
    print "<option value='$row[1]:$row[2]'>$row[0]$eng</option>\n";
  }
  print "
          <br>
          <input type='file' name='fileToUpload' id='fileToUpload'>
          <br>
          $submit
        </form>
        ";
  return NULL; 
}

function simple_tag_page($tag) {
  $GLOBALS['TAG'] = $tag; 
  $config = array();
  $config['tag'] = $GLOBALS['TAG']; 
  make_tag_page($config);
}

function make_tag_page($options) {
  $tag = $options['tag'];

  $regexp = "regexp '\\\\b" . $tag . "\\\\b'";

  # make sure that all the words are in the pictures table
  $q = "insert into pictures (allwid,tag,pal) (select id,tags,pal from all_words3 where pos not like 'var.' and tags $regexp and id not in (select allwid from pictures));";
  
  $table = 'pictures';
  $title = "Uploading pictures of $tag";
  $config = array();
  $config['intro'] = "Help upload pictures of $tag.";
  $where = "(uploaded != 1 and uploaded != 2) and tag $regexp";
  $config['extra_update'] = $q;
  $config['q_find'] = "select id,allwid from $table where $where";
  $config['get_total'] = "where tag $regexp";
  $config['get_count'] = "where $where";
  $config['table'] = $table; 
  $config['make_task'] = "tag_make_task"; # function pointer
  $config['record_task'] = "tag_record_task"; # function pointer
  $config['timeout'] = 7200; # give people 2 hours 7200 
  $task = new Dekaingeseu($title,$config);
}

?>
