<html>
    <head>
        <title>Comprimeer website foto's</title>
        <script>
            function showDiv(elem){
               if(elem.value == "ander")
                  document.getElementById("hidden_div").style.display = "block";
            }
        </script>
    </head>
    <body>
        <h1><br>Websitefoto's comprimeren</h1>
        <p><b>LET OP:</b> is de bestandnaam van de foto goed?</p>
        <p>De bestandsnaam moet een foto omschrijving zijn of zoekterm in geval van SEO <br>Bijvoorbeeld een foto van een appeltaart moet de bestandsnaam <i>appaltaart.jpg</i> heben.</p>
        
        <form action="compress.php" enctype="multipart/form-data" method="post">
            <div>
                <p>Selecteer uw foto's (maximaal 20 stuks):</p>
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

            <p><input type="submit" name="submit" value="Verstuur"></p>
        </form>
    </body>
</html>
