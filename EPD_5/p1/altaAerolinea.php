<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Epd_5_p1</title>
    </head>
    <body>
        <?php
        $nombreAerolinea = $_POST['nombreAerolinea'];
        $nDestinos = $_POST['nDestinos'];
//        $escritura_txt_id_nombreAerolinea = fopen("id_nombreAerolinea.txt", 'a');
//        $lectura_txt_id_nombreAerolinea = fopen("id_nombreAerolinea.txt", 'r');
//        $lectura_txt_ciudades = fopen("ciudades.txt", 'r');
        $id_aerolinea = NULL;
        $vectorIds = array();

        if (isset($_POST['siguiente'])) {
            if ($_POST['nombreAerolinea'] != "") {
                $errores[] = 'Debe indicar el nombre de la Aerol&iacute;nea';
            }

            if (!isset($errores)) {
                echo $id_aerolinea;     //*********************
                die("hola1");
                $lectura_txt_id_nombreAerolinea = fopen("id_nombreAerolinea.txt", 'r'); //abro lectura
                echo $id_aerolinea;     //*********************
                die("hola2");
                if (filesize("id_nombreAerolinea.txt") <= 0) {
                    echo $id_aerolinea;     //*********************
                    die("hola1");
                    fclose($lectura_txt_id_nombreAerolinea);    //cierro lectura
                    $id_aerolinea = 0;

                    $escritura_txt_id_nombreAerolinea = fopen("id_nombreAerolinea.txt", 'a');   //abro escritura
                    flock($escritura_txt_id_nombreAerolinea, LOCK_EX);  //bloqueo escritura
                    fwrite($escritura_txt_id_nombreAerolinea, $id_aerolinea . ";" . $nombreAerolinea . "\n");
                    flock($escritura_txt_id_nombreAerolinea, LOCK_UN);
                    fclose($escritura_txt_id_nombreAerolinea);  //cierro escritura
                } else {
                    echo $id_aerolinea;     //*********************
                    die("hola3");
                    //lectura del fichero para comprobar id
                    echo $id_aerolinea;
                    fclose($lectura_txt_id_nombreAerolinea);    //cierro lectura//*********************
                    $lectura_txt_id_nombreAerolinea = fopen("id_nombreAerolinea.txt", 'r'); //abro lectura

                    flock($lectura_txt_id_nombreAerolinea, LOCK_SH);  //bloqueo lectura

                    $leerIdAero = fgetcsv($lectura_txt_id_nombreAerolinea, 999, ";");   //lee la primera linea

                    while (!feof($lectura_txt_id_nombreAerolinea)) {
                        $vectorIds[] = $leerIdAero[0];
                        $leerIdAero = fgetcsv($lectura_txt_id_nombreAerolinea, 999, ";");
                    }

                    $maxIds = max($vectorIds);
                    $id_aerolinea = $maxIds + 1;

                    flock($lectura_txt_id_nombreAerolinea, LOCK_UN);
                    fclose($lectura_txt_id_nombreAerolinea);    //cierro lectura

                    $escritura_txt_id_nombreAerolinea = fopen("id_nombreAerolinea.txt", 'a');   //abro escritura
                    flock($escritura_txt_id_nombreAerolinea, LOCK_EX);
                    fwrite($escritura_txt_id_nombreAerolinea, $id_aerolinea . ";" . $nombreAerolinea . "\n");
                    flock($escritura_txt_id_nombreAerolinea, LOCK_UN);
                    fclose($escritura_txt_id_nombreAerolinea);  //cierro escritura
                }
            }
        }
        ?>
        <h2>Seleccione Destino</h2>
        <?php
        //leo el fichero de todas las ciudades destino y las almaceno en un vector
        $lectura_txt_ciudades = fopen("ciudades.txt", 'r');
        if ($lectura_txt_ciudades) {
            flock($lectura_txt_ciudades, LOCK_SH);
            $ciudades = fgetcsv($lectura_txt_ciudades, 999, ",");
            flock($lectura_txt_ciudades, LOCK_UN);
            fclose($lectura_txt_ciudades);
        } else {
            echo "error en el fichero";
        }
        ?>

        <form method="post" action ="altaCompleta.php" name="alta">
            <!--si nDestinos = 3 por ejemplo debe mostrar 3 selec y selecionar uno de cada uno sin que se repita la ciudad para eso debemos eliminar el atributo multiple-->  

            <?php for ($i = 0; $i <= count($nDestinos); $i++) { ?>
                <p>Destino <?php echo $i ?> </p>

                <select  size="8" name="vectorCiudadesDestino[]">    
                    <?php
                    foreach ($ciudades as $ciudad) {
                        ?><option value="<?php echo $ciudad; ?>"><?php echo $ciudad ?></option><?php
                    }
                    ?>

                </select>
            <?php } ?>
            <input type="hidden" name="id_aerolinea" value="<?php echo $id_aerolinea; ?>">
            <input type="hidden" name="nombreAerolinea" value="<?php echo $_POST['nombreAerolinea']; ?>">
            <input type="submit" name="enviarDestino" value="Enviar">
        </form>
    </body>
</html>
