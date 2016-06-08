#ImgComp-2
Resize an image, compress it and change the DPI.

#How to use
There are 2 possible ways to use ImgComp. One, is to use it as a standalone script. To do this, can upload it to a webserver and access it with your browser. The other way is to download the WordPress plugin [here](http://jeroengrooten.nl/ImgComp.zip) and upload it to WordPress. If you upload it to WordPress, after activation a new item will be added to the menu in the admin panel and you can access the form from there.

#How it works
Images can be uploaded through the form. There is also an select menu to select the size of the processed images. 
If the user selects "Anders, namelijk:", new input fields will appear so the user can put in their own desired height and width.
The width and height for "Normaal" are width: 800, height: 2000. The width and height voor "Slider" are width: 1920, height: 3000.
Width and height are in pixels. 

It should be noted that the default is Normaal, so the image will be 800px wide. The height, however set at 2000px, will be scaled with the width so it looks good. The width and height are just the MAXIMUM width and height. 
For example: If you put in an 500 wide and 200 height, the image will apear as 200 height and the scaled width.

The data will be send to comprimeer.php where it is comprimised and processed. 
If there are more then one image uploaded and processed, the files will be put in a .zip called "gecomprimeerd.zip" and downloaded automatically. If there is just one image uploaded and processed, that one image will be downloaded directly.

#Results: 
[Normal format]

-**Image 1**:

     ORIGINAL: 10,2 MB (10200 kB) - 7202 x 4329 - 300 DPI - JPG
     MODIFIED: 24 kB (0.024 MB) - 800 x 480 - 72 DPI - JPG
     
-**Image 2**: 

     ORIGINAL: 12,5 MB (12500 kB) - 5250 x 3450 - 96 DPI - JPG
     MODIFIED: 45,5 kB (0,0455 kB) - 800 x 525 - 72 DPI - JPG

-**Image 3:**

     ORIGINAL: 5,49 MB (5490 kB) - 4752 x 3169 - 72 DPI - JPG
     MODIFIED: 94,8 kB (0,0948 MB) - 800 x 533 - 72 DPI - JPG
    
-**Image 4:**

     ORIGINAL: 3,87 MB (3870 kB) - 4450 x 2887 - 96 DPI - JPG
     MODIFIED: 60,0 kB (0,06 MB) - 800 x 519 - 72 DPI - JPG
    
    
-**Image 5:** 

    ORIGINAL: 700 kB - 1105 x 1501 - PNG
    MODIFIED: 501 kB - 800 x 1086 - PNG
    
-**Image 6:** 

    ORIGINAL: 901 kB - 3476 x 828 - PNG
    MODIFIED: 156kB - 800 x 190 - PNG
    
-**Image 7:** 

    ORIGINAL: 2,71 MB (2710 kB) - 5184 - 3456 - 350 DPI - JPG
    MODIFIED: 34,1 kB (0,0341 MB) - 800 x 533 - 72 DPI - JPG



###Issues:
- No JPEG support. Now there is only JPG & PNG support. Should be easy fix. [SOLVED]

###TODO:
- Further optimise PNG compression 
