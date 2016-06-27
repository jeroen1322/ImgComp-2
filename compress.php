<?php
require __DIR__ . '/resources/functions.php';
//AUTHOR: Jeroen Grooten
//DATE: 3-6(June)-2016
//WEBSITE: http://www.jeroengrooten.nl
//NAME: ImgComp - Compression file. 
if (isset($_POST['submit'])) {
    
    if (count($_FILES['upload']['name']) > 0) {
        //Loop through each file
        for ($i = 0; $i < count($_FILES['upload']['name']); $i++) {
            //Get the temp file path
            $total       = count($_FILES['upload']['tmp_name']);
            $fileTmpLoc  = $_FILES["upload"]["tmp_name"];
            $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
            $fileType = $_FILES['upload']['type'];
            
            //Check if the uploaded file is actually a image. 
            //If not, stop the script and display an error. 
            if (!preg_match("/.(jpg|png)$/i", $_FILES['upload']['name'][$i])){
                echo "ERROR: Selecteer alstublieft een correct foto bestand.";
                die();
            } else {
                //Make sure we have a filepath
                if ($tmpFilePath != "") {

                    //save the filename
                    $shortname = $_FILES['upload']['name'][$i];

                    //save the url and the file
                    $filePath = "uploads/" . $_FILES['upload']['name'][$i];

                    //Upload the file into the temp dir
                    if (move_uploaded_file($tmpFilePath, $filePath)) {

                        $files[] = $shortname;
                        //use $shortname for the filename
                        //use $filePath for the relative url to the file
                    }
                } else {
                    echo "<br>ERROR: Er was een probleem tijdens het uploaded van uw bestand. Probeer het later opnieuw.";
                    die();
                }
            }
        }
    }    
    
    
    //Declare some stuff
    $size = $_POST['size']; //Default height and width selector
    
    $w = ""; //Define Height
    $h = ""; //Define Width
    
    //Use the predefined height and width that the user selects from the HTML form.
    //The height is the max height. When scaling the image, the height will be corrected.
    //But it will be no more than the set height.
    switch ($size) {
        
        case "normaal": //Default selected option
            $w = 800;
            $h = 2000;
            break;
        
        case "slider": //Slider image
            $w = 1920;
            $h = 3000; //Just a number I chose.
            break;
            
        case "logo": //Logo image
            $w = 200;
            $h = 111;
            break;
        
        //If the user selects "anders, namelijk:", the user can put in the height and width themselfes.
        case "ander":
            $wijd = $_POST['breedte']; //Get the width
            $hoog = $_POST['hoogte']; //Get the height
            
            //If the user put in width and height, use it. If not, exit the program and display an error.
            if ($wijd !== "" && $hoog !== "") {
                $w = $wijd;
                $h = $hoog;
            } else {
                echo "<br>ERROR: Voer alstublieft de hoogte en breedte voor de foto's in";
                
                //Delete all the files from the uploads/ folder after the download.
                $del = glob('uploads/*'); //Get all the files from uploads/
                //Loop through all the files and unlink (delete) them
                foreach ($del as $d) {
                    if (is_file($d)) {
                        unlink($d);
                    }
                }
                exit(0); //Can also use die();
            }
            break;
    }
    
    //Define the arrays
    $targetArray = array();
    $newcopy     = array();
    
    //Loop through all the uploaded files and get the correct filename 
    if (isset($files)) { //If $files extist, execute the code. This check is to prevent error when no files are uploaded.
        foreach ($files as $i) {
            
            $target = "uploads/" . $i; //Target will be "uploads/[FILENAME]"
            
            //Made multiple arrays from the same var for code clarity 
            $newcopy[]     = $target;
            $targetArray[] = $target;
            
            //the array $fileExt2 MUST be declared on this loop. 
            //If it isn't the case, and multible files are uploaded, the content of the .zip will be corrupted.
            $fileExt2 = array();
            
            //Get the file extension of every uploaded file.
            foreach ($files as $b) {
                $kaboom     = explode(".", $b);
                $fileExt2[] = end($kaboom);
            }
            
            
        }
    }
    
    if (isset($fileExt2)) { //If $fileExt2 extist, execute the code. This check is to prevent error when no files are uploaded.
        //Call the functions to manipulate the images and use the correct vars.
        //It is a combined array foreach loop because there are 2 arrays that need to be worked with.
        foreach (array_combine($targetArray, $fileExt2) as $arr => $ext2) {
            //Resize the image(s)
            ak_img_resize($arr, $w, $h, $ext2, $shortname);
            
            //If the image is a .jpg, change the DPI. PNG does NOT use DPI.
            //The if statement could be changed to: 
            //if($ext2 != "png"){}. But this works. 
            $ext2 = strtolower($ext2);
            if ($ext2 == "jpg" ) {
                $dpi_x = 72; //The horizontal DPI will be changed to 72
                $dpi_y = 72; //The vertical DPI will be changed to 72
                setDPI($arr, $dpi_x, $dpi_y);
            }
        }
    }
    
    //If there are more than 1 file uploaded and processed, create and put them in a .zip file and download it.
    //After the download the .zip will be deleted.
    //If there is just one file uploaded and processed, use a HTTP request to download that one file.
    download($total, $filePath, $shortname, $files);
    
} else {
    echo "ERROR";
}

?>
