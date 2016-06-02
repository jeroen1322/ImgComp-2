<?php
if(isset($_POST['submit'])){
    if(count($_FILES['upload']['name']) > 0){
        //Loop through each file
        for($i=0; $i<count($_FILES['upload']['name']); $i++) {
          //Get the temp file path
            $total = count($_FILES['upload']['tmp_name']);
            $fileTmpLoc = $_FILES["upload"]["tmp_name"];
            $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                
            $fileType = $_FILES['upload']['type'];
            //Make sure we have a filepath
            if($tmpFilePath != ""){
            
                //save the filename
                $shortname = $_FILES['upload']['name'][$i];

                //save the url and the file
                $filePath = "uploads/".$_FILES['upload']['name'][$i];

                //Upload the file into the temp dir
                if(move_uploaded_file($tmpFilePath, $filePath)) {

                    $files[] = $shortname;
                    //use $shortname for the filename
                    //use $filePath for the relative url to the file

                }
            }
        }
    }
    
    //----FUNCTION TO RESIZE THE IMAGES----//
    function ak_img_resize($arr, $w, $h, $ext2) {
        list($w_orig, $h_orig) = getimagesize($arr);

        $scale_ratio = $w_orig / $h_orig;

        if (($w / $h) > $scale_ratio) {
               $w = $h * $scale_ratio;
        } else {
               $h = $w / $scale_ratio;
        }

        $img = "";
        $target = $arr;
        $newcopy = $arr;

        $ext2 = strtolower($ext2);

        if ($ext2 == "gif"){ 
          $img = imagecreatefromgif($target);
        } else if($ext2 =="png"){ 
          $img = imagecreatefrompng($target);
            imagealphablending($img, true); // setting alpha blending on
            imagesavealpha($img, true); // save alphablending setting (important)
        } else if($ext2 == "jpg"){ 
          $img = imagecreatefromjpeg($target);
        }
        //echo "Bla"; die();
        $tci = imagecreatetruecolor($w, $h);

        
        // preserve transparency
          if($ext2 == "gif" or $ext2 == "png"){
            imagecolortransparent($tci, imagecolorallocatealpha($tci, 0, 0, 0, 127));
            imagealphablending($tci, false);
            imagesavealpha($tci, true);
          }

        imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);

        if($ext2 == "jpg"){
            imagejpeg($tci, $newcopy, 60);
        }elseif($ext2 == "png"){
            imagepng($tci, $newcopy, 8);
        }
    }
    
    //----FUNCTION TO CHANGE THE DPI OF THE PHOTO TO 72----//
    function setDPI($comped,$dpi_x=72,$dpi_y=72){

      // Read the file
        $size    = filesize($comped);
        $image   = file_get_contents($comped);

      // Update DPI information in the JPG header
        $image[13] = chr(1);
        $image[14] = chr(floor($dpi_x/255));
        $image[15] = chr($dpi_x%255);
        $image[16] = chr(floor($dpi_y/255));
        $image[17] = chr($dpi_y%255);

          // Write the new JPG
          $f = fopen($comped, 'w') or die("Could not open");
          fwrite($f, $image, $size);
          fclose($f);	
        }
    
    //----GET WIDTH AND HEIGHT FROM HTML FORM----//
    
    $size = $_POST['size'];
    $w = "";
    $h = "";
    
    switch($size){
        case "normaal":
            $w = 800;
            $h = 2000;
        break;
        
        case "slider":
            $w = 1920;
            $h = 3000;
        break;
            
        case "ander":
            $wijd = $_POST['breedte'];
            $hoog = $_POST['hoogte'];
            
            if($wijd !== "" && $hoog !== ""){
                $w = $wijd;
                $h = $hoog;
            }else{
                echo "ERROR: Voer alstublieft de hoogte en breedte voor de foto(s) in";
                exit(0);
            }
    }
    
    $targetArray = array();
    $newcopy = array();
    foreach($files as $i){
        
        $target = "uploads/".$i;
        $comped = "uploads/".$i;
        $newcopy[] = $comped;
        $targetArray[] = $target;

        
        $fileExt2 = array();
        foreach($files as $b){
            $kaboom = explode(".", $b);
            $fileExt2[] = end($kaboom);
        }
    
        
    }
    
    foreach(array_combine($targetArray, $fileExt2) as $arr => $ext2){
        ak_img_resize($arr, $w, $h, $ext2);
        
        if($ext2 == "jpg"){
            setDPI($arr,$dpi_x=72,$dpi_y=72);
        }
    }
    



    //----ZIP AND DOWNLAOD THE IMAGES THROUGH THAT .ZIP----//
    //----THIS IS THE BEST WAY TO DOWNLOAD MULTIPLE FILES----//
    //----IF THERE IS JUST 1 FILE, DOWNLOAD IT WITH JUST A HTTP REQUEST----//
    if($total > 1){
        $zip = new ZipArchive;
        $filename = 'gecomprimeerd.zip';

        if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
            exit("cannot open <$filename>\n");
        }


        $zip = new ZipArchive();
        if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
            exit("cannot open <$filename>\n");
        }
        foreach($files as $i){
                    $zip->addFile('uploads/'.$i, $i); //Put the images in gecomprimeerd.zip
                }
        $zip->close();

        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.$filename);
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
        unlink($filename);

        //----DELETE FILES FROM uploads/ FOLDER----//
            $del = glob('uploads/*');
            foreach($del as $d){
                if(is_file($d)){
                    unlink($d);
                }
            }
    }else{
                header('Content-type: octet/stream');
                header('Content-disposition: attachment; filename='.$shortname.';');
                header('Content-Length: '.filesize($filePath));
                readfile($filePath);
    }
}

?>