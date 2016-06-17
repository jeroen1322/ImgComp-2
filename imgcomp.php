<?php
/*
Plugin Name: ImgComp
Plugin URI: http://www.vaneckoosterink.com
Description: Foto compressie. -Optimaliseer foto's voor uw website
Author: Jeroen Grooten - Van Eck & Oosterink
Version: 2.0
Author URI: http://www.jeroengrooten.nl
*/

//THIS FILE IS FOR WORDPRESS
//This file will display the plugin form in the WP admin panel.
//If the script will be used as a standalone script, this file can be removed. 

add_action('admin_menu', 'imgcomp_main_page');
 
function imgcomp_main_page(){
        add_menu_page( 'Comprimeer hoofdpagina', 'Comprimeer', 'manage_options', 'comprimeer', 'compress_main_page' );
}
 
function compress_main_page(){
echo '
<html>
    <head>
        <title>Comprimeer website foto\'s</title>
        <link rel="stylesheet" type="text/css" href="/wp-content/plugins/ImgComp/css/style.css">
        <script src="/wp-content/plugins/ImgComp/js/js.js"></script>
    </head>
    
    <body>
        <h1><br>Websitefoto\'s comprimeren</h1>
        <div id="letop">
            <p><b>LET OP:</b> is de bestandnaam van de foto goed?</p>
            <p>De bestandsnaam moet een foto omschrijving zijn of zoekterm in geval van SEO <br>Bijvoorbeeld een foto van een appeltaart moet de bestandsnaam <i>appaltaart.jpg</i> heben.</p>
        </div>
            
        <form action="/wp-content/plugins/ImgComp/compress.php" enctype="multipart/form-data" method="post">
            <div>
                <p>Selecteer uw foto\'s (maximaal 20 per keer):</p>
                <input id="upload" name="upload[]" type="file" multiple="multiple" accept="image/*" />
                <p>Selecteer het gewenste foto formaat:</p>
                <select name="size" onchange="showDiv(this)">
                    <option value="normaal">Normaal</option>
                    <option value="slider">Slider</option>
                    <option value="logo">Logo</option>
                    <option value="ander">Anders, Namelijk:</option>
                </select>
                <div id="hidden_div" style="display: none;">
                    <p>Hoogte: <br>  <input type="input" name="hoogte" placeholder="Hoogte in pixels"></p>
                    <p>Breedte: <br> <input type="input" name="breedte" placeholder="Breedte in pixels"></p>
                    <p><br>De hoogte en breedte zijn de maximale hoogte en breedte. Als er als hoogte 100 wordt ingesteld en breedte 50, wordt hij 50 breed en de geschaalde hoogte.</p>
                </div>
            </div>

            <p><input type="submit" name="submit" value="Verstuur" onclick="myFunction()"></p>
        </form>
        <div id="help">
            <img src="/wp-content/plugins/ImgComp/Images/question-mark.png" />
            <div id="help_hidden"><p>Het kan enkele minuten duren om het bestand te uploaden, aanpassen en downloaden. Het kan zijn dat de pagina niet veranderd, maar de download start automatisch.</p></div>
        </div>
    </body>
    
</html>

'
;

}
 
?>
