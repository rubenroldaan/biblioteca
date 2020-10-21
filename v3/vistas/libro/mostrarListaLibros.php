<?php
    echo "<h1>Biblioteca</h1>";
    if (isset($_SESSION["id_usuario"])) {
        echo "Hola, ".$_SESSION['nombre_usuario']."<br>";
    }



    if (isset($_SESSION["id_usuario"])) {
        echo "<a href='index.php?action=cerrarSesion'>Cerrar sesión</a><br>";
        echo "<a href='index.php?action=formularioAltaLibros'>Nuevo</a>";
    } else {
        echo "<a href='index.php?action=mostrarFormularioLogin'>Iniciar sesión</a>";
    }

    echo "<form action='index.php'>
			<input type='hidden' name='action' value='buscarLibros'>
           	<input type='text' name='textoBusqueda'>
			<input type='submit' value='Buscar'>
            </form><br>"; 


    if (count($data['listaLibros']) > 0) {
        // Ahora, la tabla con los datos de los libros
        echo "<table align='center' border='1' cellpadding='15px' cellspacing='0'>";
        foreach($data['listaLibros'] as $libro) {
            echo "<tr>";
            echo '<td>' . $libro->id_libro . '</td>';
            echo "<td>" . $libro->titulo . "</td>";
            echo "<td>" . $libro->genero . "</td>";
            echo "<td>" . $libro->numPaginas . "</td>";
                // Los botones "Modificar" y "Borrar" solo se muestran si hay una sesión iniciada
                if (isset($_SESSION["id_usuario"])) {
                    echo "<td><a href='index.php?action=formularioModificarLibro&idLibro=" . $libro->id_libro . "'>Modificar</a></td>";
                    echo "<td><a href='index.php?action=borrarLibro&idLibro=" . $libro->id_libro . "'>Borrar</a></td>";
                }
                echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No se encontraron datos";
    }
?>