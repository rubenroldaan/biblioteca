<h1>Iniciar sesión</h1>

<?php
    if (isset($data['msjError'])) {
        echo "<p style='color:red'>".$data['msjError']."</p>";
    }
    if (isset($data['msjInfo'])) {
        echo "<p style='color:blue'>".$data['msjInfo']."</p>";
    }
?>

<form action="index.php" method="get">
    Usuario: <input type="text" name="user"><br>
    <input type="hidden" name="action" value="procesarLogin">
    Contraseña: <input type="text" name="passwd"></br>
    <input type="submit">
</form>