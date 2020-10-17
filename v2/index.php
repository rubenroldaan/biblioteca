<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>

<body>
    <?php

    session_start();
    $db = new mysqli("localhost", "root", "", "biblioteca");
    if (isset($_REQUEST["action"])) {
        $action = $_REQUEST["action"];
    } else {
        $action = "mostrarFormularioLogin";
    }


    switch($action) {
        case "mostrarListaLibros":
        echo "<h1>Biblioteca</h1>"; 
        if ($result = $db->query("SELECT * FROM libros")) {
            if ($result->num_rows != 0) {
                echo "<a href='index.php?action=formularioAltaLibros'>Nuevo</a>";
                echo "<form action='index.php'><input type='hidden' name='action' value='buscarLibros'>
              <input type='text' name='textoBusqueda'>
              <input type='submit'>
			  </form>";
                echo '<table align="center" border="1" cellpadding="15px" cellspacing="0">';
                echo '<tr>
                    <td>ID</td>
                    <td>Título</td>
                    <td>Género</td>
                    <td>Páginas</td>
                    <td style="border: 0" colspan="2"></td>';
                while ($fila = $result->fetch_object()) {
                    echo "<tr>";
                    echo '<td>' . $fila->id_libro . '</td>';
                    echo "<td>" . $fila->titulo . "</td>";
                    echo "<td>" . $fila->genero . "</td>";
                    echo "<td>" . $fila->numPaginas . "</td>";
                    echo "<td><a href='index.php?action=formularioModificarLibro&idLibro=" . $fila->id_libro . "'>Modificar</td>";
                    echo "<td><a href='index.php?action=borrarLibro&idLibro=" . $fila->id_libro . "'>Borrar</a></td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No se encontraron datos<br>";
                echo '<a href="index.php?action=formularioAltaLibros">Nuevo</a>';
            }
        }
        break;
            case "formularioAltaLibros":
                echo '<h1>Formulario de alta de libros</h1>
                <form action = "index.php" method = "get">
                    Título:<input type="text" name="titulo"><br>
                    Género:<input type="text" name="genero"><br>
                    País:<input type="text" name="pais"><br>
                    Año:<input type="text" name="anyo"><br>
                    Número de páginas:<input type="text" name="numPaginas"><br>
                Autor:<select name="autor[]" size="3" multiple="true">';
                if ($result = $db->query("SELECT * FROM autores")) {
                    if ($result->num_rows != 0) {
                        while ($fila = $result->fetch_object()) {
                            echo '<option value="' . $fila->id_autor . '">' . $fila->nombre . ' ' . $fila->apellidos . '</option>';
                        }
                    } else {
                        echo '<option disabled>No se han encontrado autores</option>';
                    }
                }
                echo '</select>
                    <a href="index.php?action=formularioAltaAutores">Nuevo autor</a>
                    <input type="hidden" name="action" value="insertarLibro"><br><br>
                    <input type="submit">
                    </form>';
                break;

            case "insertarLibro":
                $titulo = $_REQUEST["titulo"];
                $genero = $_REQUEST["genero"];
                $pais = $_REQUEST["pais"];
                $anyo = $_REQUEST["anyo"];
                $numPaginas = $_REQUEST["numPaginas"];
                $idAutor = $_REQUEST["autor"];
                // Conectamos con el servidor y abrimos la BD.
                $db->query("INSERT INTO libros (titulo,genero, pais, anyo, numPaginas)
                            VALUES ('$titulo','$genero','$pais','$anyo','$numPaginas')");
                if ($db->affected_rows == 1) {
                    $result = $db->query("SELECT MAX(id_libro) AS ultimoIdLibro FROM libros");
                    $idLibro = $result->fetch_object()->ultimoIdLibro;
                    foreach ($idAutor as $idAutor) {
                        $db->query("INSERT INTO escriben(id_libro, id_autor) VALUES('$idLibro', '$idAutor')");
                    }
                    echo "Libro insertado con éxito";
                }
                else {
                    echo "Ha ocurrido un error al insertar el libro. Por favor, inténtelo más tarde.";
                }
                    echo 'Libro insertado con éxito.<br>
                      <a href="index.php">Volver al inicio</a>';
                
                break;

            case "borrarLibro":
                $idLibro = $_REQUEST["idLibro"];
                if ($db->query("DELETE FROM libros WHERE id_libro = '$idLibro'")) {
                    echo 'Libro borrado con éxito<br>
                  <a href="index.php">Volver a inicio.</a>';
                } else {
                    echo "Ha ocurrido un error al borrar el libro. Por favor, inténtelo de nuevo.<br>";
                    echo '<a href="index.php">Volver a inicio.</a>';
                }
                break;

            case "buscarLibros":
                $libroBuscado = $_REQUEST['textoBusqueda'];
                if ($result = $db->query("SELECT * FROM libros WHERE titulo LIKE '%$libroBuscado%'")) {
                    //if ($result = $db->query("SELECT * FROM libros WHERE id_libro = $libroBuscado")) {
                    if ($result->num_rows != 0) {
                        echo '<table align="center" border="1" cellpadding="15px" cellspacing="0">';
                        echo '<tr>
                            <td>ID</td>
                            <td>Título</td>
                            <td>Género</td>
                            <td>Páginas</td>
                            <td style="border: 0" colspan="2"></td>';
                        while ($fila = $result->fetch_object()) {
                            echo "<tr>";
                            echo '<td>' . $fila->id_libro . '</td>';
                            echo "<td>" . $fila->titulo . "</td>";
                            echo "<td>" . $fila->genero . "</td>";
                            echo "<td>" . $fila->numPaginas . "</td>";
                            echo "<td>Modificar</td>";
                            echo "<td><a href='index.php?action=borrarLibro&idLibro=" . $fila->id_libro . "'>Borrar</a></td>";
                            echo "</tr>";
                        }
                        echo "</table><br><br>";
                        echo '<a href="index.php">Volver a inicio</a>';
                    } else {
                        echo "No se encontraron datos<br>";
                    }
                }
                break;

            case 'formularioAltaAutores':
                echo '<h1>Formulario de alta de autores</h1>
                    <form action = "index.php" method = "get">
                        Nombre: <input type="text" name="nombre"><br>
                        Apellidos: <input type="text" name="apellidos"><br><br>
                        <input type="hidden" name="action" value="insertarAutor">
                        <input type="submit">';
                break;

            case 'insertarAutor':
                $nombre = $_REQUEST["nombre"];
                $apellidos = $_REQUEST["apellidos"];
                if ($db->query("INSERT INTO autores (nombre, apellidos)
                            VALUES('$nombre','$apellidos')")) {
                    echo 'Autor creado con éxito<br>
                                <a href="index.php?action=formularioAltaLibros">Volver a inicio.</a>';
                } else {
                    echo "Ha habido un error. Inténtelo de nuevo más tarde.";
                }
                break;

            case 'formularioModificarLibro':
                echo '<h1>Modificación de libros</h1>';
                $idLibro = $_REQUEST['idLibro'];
                if ($result = $db->query("SELECT * FROM libros WHERE id_libro = '$idLibro'")) {
                    $libro = $result->fetch_object();
                    echo "<form action = 'index.php' method = 'get'>
				            <input type='hidden' name='idLibro' value='$idLibro'>
                            Título:<input type='text' name='titulo' value='$libro->titulo'><br>
                            Género:<input type='text' name='genero' value='$libro->genero'><br>
                            País:<input type='text' name='pais' value='$libro->pais'><br>
                            Año:<input type='text' name='anyo' value='$libro->anyo'><br>
                            <input type='hidden' name='action' value='modificarLibro'>
                            Número de páginas:<input type='text' name='numPaginas' value='$libro->numPaginas'><br>";
                    
                    $todosLosAutores = $db->query("SELECT * FROM autores");
                    $autoresLibro = $db->query("SELECT autores.id_autor FROM autores
                                                INNER JOIN escriben ON autores.id_autor = escriben.id_autor
                                                INNER JOIN libros ON libros.id_libro = escriben.id_libro
                                                WHERE libros.id_libro = '$idLibro'");
                    $listaAutoresLibro = array();
                    while ($autor = $autoresLibro->fetch_object()) {
                        $listaAutoresLibro[] = $autor->id_autor;
                    }
                    echo "Autores: <select name='autor[]' multiple size='3'";
                    while ($fila = $todosLosAutores->fetch_object()) {
                        if (in_array($fila->id_autor, $listaAutoresLibro))
                            echo "<option value='$fila->id_autor' selected>$fila->nombre $fila->apellido</option>";
                        else
                            echo "<option value='$fila->id_autor'>".$fila->nombre."  ".$fila->apellido."</option>";
                    }
                    echo "</select><br><br>";
                    echo "<input type='submit'>";
                } else {
                    echo 'No se ha podido modificar el libro. Por favor, inténtelo de nuevo más tarde.';
                }
                break;
            
            case 'modificarLibro':
                echo "<h1>Modificación de libros</h1>";
                $idLibro = $_REQUEST["idLibro"];
                $titulo = $_REQUEST["titulo"];
                $genero = $_REQUEST["genero"];
                $pais = $_REQUEST["pais"];
                $anyo = $_REQUEST["anyo"];
                $numPaginas = $_REQUEST["numPaginas"];
                $autores = $_REQUEST["autor"];

                $db->query("UPDATE libros SET
							titulo = '$titulo',
							genero = '$genero',
							pais = '$pais',
							anyo = '$anyo',
							numPaginas = '$numPaginas'
                            WHERE id_libro = '$idLibro'");
                
                if ($db->affected_rows == 1) {
                    $db->query("DELETE FROM escriben WHERE id_libro = '$idLibro'");
                    foreach ($autores as $idAutor) {
                        $db->query("INSERT INTO escriben(id_libro, id_autor) VALUES('$idLibro', '$idAutor')");
                    }
                    echo "Libro actualizado con éxito";
                } else {
                    echo "Ha ocurrido un error al modificar el libro. Por favor, inténtelo más tarde.";
                }
                echo "<p><a href='index.php'>Volver</a></p>"; 

            break;

            default:
                echo "Error 404: página no encontrada";
                break;
        } // switch

    ?>

</body>

</html>