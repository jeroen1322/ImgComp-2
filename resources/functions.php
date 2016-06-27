<?php

    //----FUNCTION TO RESIZE THE IMAGES----//
    function ak_img_resize($arr, $w, $h, $ext2, $shortname)
    {
        
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
        $img     = "";
        $target  = $arr;
        $newcopy = $arr;
        
        //Get the file extension to lower. So .PNG = .png.
        $ext2 = strtolower($ext2);
        
        //Check the file extension and use the appropriate imagecreatefrom.
        switch ($ext2) {
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
        if ($ext2 === "png") {
            imagecolortransparent($tci, imagecolorallocatealpha($tci, 0, 0, 0, 127));
            imagealphablending($tci, false);
            imagesavealpha($tci, true);
        }
        
        //Copy the pixels of the original image to the new image
       imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
        
        switch ($ext2) {
            case "jpg":
                
                //Get file name and image information
                $short = "uploads/".$shortname;
                $short = getimagesize($short);

                //Check if an image is CMYK. If it is, echo an error, delete uploaded files and die();
                if($short['channels'] == 4){
                    echo "<div id='error'><span id='closebtn'>&times;</span>Uw foto is CMYK (Fullcolor) en wordt niet ondersteund. <br> Converteer alstublieft eerst de foto naar RGB en probeer het daarna opnieuw.</div>";

                    //Delete all the files from the uploads/ folder
                    $del = glob('uploads/*'); //Get all the files from uploads/

                    //Loop through all the files and unlink (delete) them
                    foreach ($del as $d) {
                        if (is_file($d)) {
                            unlink($d);
                        }
                    }
                    die();

                }
                
                imagejpeg($tci, $newcopy, 80); //Make the image at 60% the quality of the original
                //imagejpeg can use from 0 (worst quality) to 100 (best quality).
                //If the quality option is NOT set, the default will be 75.
                break;
            
            case "png":
                imagepng($tci, $newcopy, 8); //Make the image at 60% of the quality of the original. 
                //imagepng can use from 0 (no compression) to 9. 
                break;
        }
        
    } //----END RESIZE FUNCTION----//

    
    //----FUNCTION TO CHANGE THE DPI OF THE PHOTO TO 72----//
    function setDPI($comped, $dpi_x, $dpi_y)
    {
        
        // Read the file
        $size  = filesize($comped);
        $image = file_get_contents($comped);
        
        // Update DPI information in the JPG header
        $image[13] = chr(1);
        $image[14] = chr(floor($dpi_x / 255));
        $image[15] = chr($dpi_x % 255);
        $image[16] = chr(floor($dpi_y / 255));
        $image[17] = chr($dpi_y % 255);
        
        // Write the new JPG
        $f = fopen($comped, 'w') or die("<div id='error'><span id='closebtn'>&times;</span>ERROR: Er is een fout geweest tijdens het verwerken van uw bestand. Probeer het alstublieft opnieuw.</div>");
        fwrite($f, $image, $size);
        fclose($f);
    }
    //----END DPI CHANGE FUNCTION----//

    //If there are more than 1 file uploaded and processed, create and put them in a .zip file and download it.
    //After the download the .zip will be deleted.
    //If there is just one file uploaded and processed, use a HTTP request to download that one file.
    function download($total, $filePath, $shortname, $files){
        if ($total > 1) {

            //Defining some vars
            $zip      = new ZipArchive;
            $filename = 'gecomprimeerd.zip'; //The .zip filename

            //If the .zip can not be opened after creation, throw an error.
            if ($zip->open($filename, ZipArchive::CREATE) !== TRUE) {
                exit("cannot open <$filename>\n");
            }

            //Loop through each image and add them to the .zip
            foreach ($files as $i) {
                $zip->addFile('uploads/' . $i, $i);
            }

            $zip->close(); //Close the zip procedure


         /*   //Download the .zip file
            header('Content-Type: application/zip');
            header('Content-disposition: attachment; filename=' . $filename);
            header('Content-Length: ' . filesize($filename));
            readfile($filename);
            unlink($filename);*/
    
            echo "<br><br><br><div id='download'><a href='gecomprimeerd.zip' download='gecomprimeerd.zip'><button name='download' type='submit'>Download foto's</button></a></div>";
            
            //Delete all the files from the uploads/ folder after the download.
/*            $del = glob('uploads/*'); //Get all the files from uploads/
            //Loop through all the files and unlink (delete) them
            foreach ($del as $d) {
                if (is_file($d)) {
                    unlink($d);
                }
            }*/

        } else {

            if (isset($filePath)) {
                $shortname = preg_replace('/\s+/', '_', $shortname);
                //If there is just 1 file uploaded, send a HTTP request and download only that file.
/*                header('Content-type: octet/stream');
                header('Content-disposition: attachment; filename=' . $shortname . ';');
                header('Content-Length: ' . filesize($filePath));
                readfile($filePath);*/
                echo "<br><br><br><div id='download'><a href='$filePath' download='$filePath'><button type='button'>Download foto</button></div>";
                
            } else {
                echo "<div id='error'><span id='closebtn'>&times;</span>ERROR: Selecteer alstublieft een foto.</div>";
            }

            //Delete all the files from the uploads/ folder after the download.
/*            $del = glob('uploads/*'); //Get all the files from uploads/
            //Loop through all the files and unlink (delete) them
            foreach ($del as $d) {
                if (is_file($d)) {
                    unlink($d);
                }
            }*/
        }
    }
