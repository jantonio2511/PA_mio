<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h2>ALTA AEROLINEA: OK</h2>
        <?php
        $vectorCiudadesDestino = $_POST['vectorCiudadesDestino'];
        $id_aerolinea = $_POST['id_aerolinea'];
        $nombreAerolinea = $_POST['nombreAerolinea'];
//        $escritura_txt_altaCompleta = fopen("altaCompleta.txt", 'a');    //modo escritura
//        $lectura_txt_altaCompleta = fopen("altaCompleta.txt", 'r');   //modo lectura
//        $lectura_txt_id_nombreAerolinea = fopen("iDnombreAerolinea.txt", 'r');    //modo lectura

        $escritura_txt_altaCompleta = fopen("altaCompleta.txt", 'a');    //modo escritura
        flock($escritura_txt_altaCompleta, LOCK_EX);  //bloqueo escritura
        foreach ($vectorCiudadesDestino as $value) {
            fwrite($escritura_txt_altaCompleta, $id_aerolinea . ";" . $value . "\n");
        }

        flock($escritura_txt_altaCompleta, LOCK_UN);
        fclose($escritura_txt_altaCompleta);
        ?> 
        <h2>Aerol&iacute;neas registradas</h2> 
        <?php
        //Leemos las aerolineas
        $nombreAero = array();
        $lectura_txt_id_nombreAerolinea = fopen("iDnombreAerolinea.txt", 'r');    //modo lectura
        flock($lectura_txt_id_nombreAerolinea, LOCK_SH);  //bloqueo lectura

        $leerNomAero = fgetcsv($lectura_txt_id_nombreAerolinea, 999, ";");   //lee la primera linea
        while (!feof($lectura_txt_id_nombreAerolinea)) {
            $nombreAero[0][] = $leerNomAero[0];
            $nombreAero[1][] = $leerNomAero[1];
            $leerNomAero = fgetcsv($lectura_txt_id_nombreAerolinea, 999, ";");
        }
        flock($lectura_txt_id_nombreAerolinea, LOCK_UN);
        fclose($lectura_txt_id_nombreAerolinea);
        //leemos las ciudades destino para cada aerolinea

        $nombreDest = array();
        $lectura_txt_altaCompleta = fopen("altaCompleta.txt", 'r');   //modo lectura
        flock($lectura_txt_altaCompleta, LOCK_SH);  //bloqueo lectura
        $leerDest = fgetcsv($lectura_txt_altaCompleta, 999, ";");   //lee la primera linea
        while (!feof($lectura_txt_altaCompleta)) {
            $nombreDest[0][] = $leerDest[0];
            $nombreDest[1][] = $leerDest[1];
            $leerDest = fgetcsv($lectura_txt_altaCompleta, 999, ";");
        }
        flock($lectura_txt_altaCompleta, LOCK_UN);
        fclose($lectura_txt_altaCompleta);
//        tenemos que mostrar en la web el nombre de las aerolineas. y los destinos de cada aerolinea en un radio boton 
//        imprimo las aerolineas y sus destinos

        for ($ind = 0; $ind < count($nombreAero[0]); $ind++) {
            echo "<article>";
            echo "<h4>" . $nombreAero[1][$ind] . "</h4>";

            for ($inde = 0; $inde < count($nombreDest[0]); $inde++) {

                if ($nombreAero[0][$ind] == $nombreDest[1][$inde]) {    // value='" . .  "'". 
                    echo"<input type='radio' name='destinos'>" . $nombreDest[1][$inde] . "</input>";
                }

                echo "</article>";
            }
        }
        ?>
        <form method="post" action="altaVuelos.php">
            <br />
            Seleccione Origen y pulse: <input type="submit" name="enviarDestino" value="Enviar">

        </form>
    </body>
</html>
