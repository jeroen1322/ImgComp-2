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

###Issues:
- No JPEG support. Will be implemented soon. Now there is only JPG & PNG support.
