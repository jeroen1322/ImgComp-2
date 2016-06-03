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
            }else{
                echo "ERROR: Er was een probleem tijdens het uploaded van uw bestand. Probeer het later opnieuw.";
            }
        }
    }
    
    //----FUNCTION TO RESIZE THE IMAGES----//
    function ak_img_resize($arr, $w, $h, $ext2) {
        
        //Get the sizes of the original image(s)
        list($w_orig, $h_orig) = getimagesize($arr);
        
        //Scale the height with the width, and vice versa 
        $scale_ratio = $w_orig / $h_orig;
        if (($w / $h) > $scale_ratio) {
               $w = $h * $scale_ratio;
        } else {
               $h = $w / $scale_ratio;
        }

        //Define some vars
        $img = "";
        $target = $arr;
        $newcopy = $arr;
        
        //Get the file extension to lower. So PNG = png.
        $ext2 = strtolower($ext2);

        //Check the file extension and use the appropriate imagecreatefrom.
/*        if($ext2 =="png"){ 
          $img = imagecreatefrompng($target);
            imagealphablending($img, true); // setting alpha blending on
            imagesavealpha($img, true); // save alphablending setting (important)
        } else if($ext2 == "jpg"){ 
          $img = imagecreatefromjpeg($target);
        }*/
        
        //Check the file extension and use the appropriate imagecreatefrom.
        switch($ext2){
            case "png":
                $img = imagecreatefrompng($target);
            break;
                
            case "jpg":
                $img = imagecreatefromjpeg($target);
            break;
        }
        
        //Use the new height and width
        $tci = imagecreatetruecolor($w, $h);

        
        //preserve transparency in PNG images
          if($ext2 === "png"){
            imagecolortransparent($tci, imagecolorallocatealpha($tci, 0, 0, 0, 127));
            imagealphablending($tci, false);
            imagesavealpha($tci, true);
          }
        
        //Copy the pixels of the original image to the new image
        imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);

        if($ext2 == "jpg"){
            imagejpeg($tci, $newcopy, 60);
        }elseif($ext2 == "png"){
            imagepng($tci, $newcopy, 8);
        }
        
        switch($ext2){
            case "jpg":
                imagejpeg($tci, $newcopy, 60); //Make the image at 60% the quality of the original
                                              //imagejpeg can use from 0 (worst quality) to 100 (best quality).
                                             //If the quality option is NOT set, the default will be 75.
            break;
                
            case "png":
                imagepng($tci, $newcopy, 6); //Make the image at 60% of the quality of the original. 
                                            //imagepng can use from 0 (no compression) to 9. 
        }
        
    }//----END RESIZE FUNCTION----//
    
    
    //----FUNCTION TO CHANGE THE DPI OF THE PHOTO TO 72----//
    function setDPI($comped, $dpi_x, $dpi_y){

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
          $f = fopen($comped, 'w') or die("ERROR: Er is een fout geweest tijdens het verwerken van uw bestand. Probeer het alstublieft opnieuw.");
          fwrite($f, $image, $size);
          fclose($f);	
        }
    //----END DPI CHANGE FUNCTION----//

    
    //Declare some stuff
    $size = $_POST['size'];  //Default height and width selector
    
    $w = "";//Define Height
    $h = "";//Define Width
    
    //Use the predefined height and width that the user selects from the HTML form.
    //The height is the max height. When scaling the image, the height will be corrected.
    //But it will be no more than the set height.
    switch($size){

        case "normaal": //Default selected option
            $w = 800;
            $h = 2000;
        break;
        
        case "slider":
            $w = 1920;
            $h = 3000; //Just a number I chose.
        break;
            
            //If the user selects "anders, namelijk:", the user can put in the height and width themselfes.
        case "ander":
            $wijd = $_POST['breedte']; //Get the width
            $hoog = $_POST['hoogte']; //Get the height
            
            //If the user put in width and height, use it. If not, exit the program and display an error.
            if($wijd !== "" && $hoog !== ""){
                $w = $wijd;
                $h = $hoog;
            }else{
                echo "ERROR: Voer alstublieft de hoogte en breedte voor de foto(s) in";
                exit(0); //Can also use die();
            }
    }
    
    //Define the arrays
    $targetArray = array();
    $newcopy = array();
    
    //Loop through all the uploaded files and get the correct filename 
    foreach($files as $i){
        
        $target = "uploads/".$i; //Target will be "uploads/[FILENAME]"
        
        //Made multiple arrays from the same var for code clarity 
        $newcopy[] = $target; 
        $targetArray[] = $target;
        
        //the array $fileExt2 MUST be declared on this loop. 
        //If it isn't the case, and multible files are uploaded, the content of the .zip will be corrupted.
        $fileExt2 = array(); 
        
        //Get the file extension of every uploaded file.
        foreach($files as $b){
            $kaboom = explode(".", $b);
            $fileExt2[] = end($kaboom);
        }
    
        
    }
    
    //Call the functions to manipulate the images and use the correct vars.
    //It is a combined array foreach loop because there are 2 arrays that need to be worked with.
    foreach(array_combine($targetArray, $fileExt2) as $arr => $ext2){
        //Resize the image(s)
        ak_img_resize($arr, $w, $h, $ext2);
        
        //If the image is a .jpg, change the DPI. PNG does NOT use DPI.
        //The if statement could be changed to: 
        //if($ext2 != "png"){}. But this works. 
        if($ext2 == "jpg"){
            $dpi_x=72; //The horizontal DPI will be changed to 72
            $dpi_y=72; //The vertical DPI will be changed to 72
            setDPI($arr, $dpi_x, $dpi_y);
        }
    }
    



    //If there are more than 1 file uploaded and processed, create and put them in a .zip file and download it.
    //After the download the .zip will be deleted.
    //If there is just one file uploaded and processed, use a HTTP request to download that one file.
    if($total > 1){
        
        //Defining some vars
        $zip = new ZipArchive;
        $filename = 'gecomprimeerd.zip'; //The .zip filename
        
        //If the .zip can not be opened after creation, throw an error.
        if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
            exit("cannot open <$filename>\n");
        }
        
        //Loop through each image and add them to the .zip
        foreach($files as $i){
                    $zip->addFile('uploads/'.$i, $i); 
                }
        
        $zip->close(); //Close the zip procedure
        
        //Download the .zip file
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.$filename);
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
        unlink($filename);
        
        //Delete all the files from the uploads/ folder after the download.
        $del = glob('uploads/*');//Get all the files from uploads/
        //Loop through all the files and unlink (delete) them
        foreach($del as $d){
            if(is_file($d)){
                unlink($d);
            }
        }
        
    }else{      
        //If there is just 1 file uploaded, send a HTTP request and download only that file.
        header('Content-type: octet/stream');
        header('Content-disposition: attachment; filename='.$shortname.';');
        header('Content-Length: '.filesize($filePath));
        readfile($filePath);

        //Delete all the files from the uploads/ folder after the download.
        $del = glob('uploads/*');//Get all the files from uploads/
        //Loop through all the files and unlink (delete) them
        foreach($del as $d){
            if(is_file($d)){
                unlink($d);
            }
        }     
    }   
}

?>
