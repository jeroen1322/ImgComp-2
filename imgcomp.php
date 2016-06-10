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
        <script>
            function showDiv(elem){
               if(elem.value == "ander")
                  document.getElementById("hidden_div").style.display = "block";
            }
        </script>
        <style>
            #letop{
                max-width: 500px;
                border: 1px solid #bbbbbb;
                 -moz-box-shadow: 1px 2px 3px rgba(0,0,0,.5);
                -webkit-box-shadow: 1px 2px 3px rgba(0,0,0,.5);
                box-shadow: 1px 2px 3px rgba(0,0,0,.5);
            }
            
            #letop p{
                padding-left: 10px;
                padding-right: 10px;
            }

            #help img{
                float: left;
                height: 20px;
                width: 20px;
                margin-top: 10px;
            }
            
            #help_hidden{
                max-width: 200px;
                max-height: 200px;
                margin-top: 10px;
                margin-left: 50px;
                display: none;
                background-color: #DEDEDE;
                border: 1.5px dashed black;
                border-radius: 5px;
            }
            
            #help_hidden p{
                padding-left: 10px;
                padding-right: 10px;
            }
            
            #help:hover #help_hidden{
                display: block;
            }
            
        </style>
        <script>
        function myFunction() {
            location.reload();
        }
        </script>
    </head>
    <body>
        <h1><br>Websitefoto\'s comprimeren</h1>
        <div id="letop">
            <p><b>LET OP:</b> is de bestandnaam van de foto goed?</p>
            <p>De bestandsnaam moet een foto omschrijving zijn of zoekterm in geval van SEO <br>Bijvoorbeeld een foto van een appeltaart moet de bestandsnaam <i>appaltaart.jpg</i> heben.</p>
        </div>
            
        <form action="compress.php" enctype="multipart/form-data" method="post">
            <div>
                <p>Selecteer uw foto\'s (maximaal 20 stuks):</p>
                <input id="upload" name="upload[]" type="file" multiple="multiple" accept="image/*" />
                <p>Selecteer het gewenste foto formaat:</p>
                <select name="size" onchange="showDiv(this)">
                    <option value="normaal">Normaal</option>
                    <option value="slider">Slider</option>
                    <option value="logo">Logo</option>
                    <option value="ander">Ander, Namelijk:</option>
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
            <img src="Images/question-mark.png" />
            <div id="help_hidden"><p>Het kan enkele minuten duren om het bestand te uploaden, aanpassen en downloaden. Het kan zijn dat de pagina niet veranderd, maar de download start automatisch.</p></div>
        </div>
    </body>
</html>
'
;

}
 
?>
