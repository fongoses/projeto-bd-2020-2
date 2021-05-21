<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Consulta por ID do Registro</title>
</head>
<body>
    <div><h1>Consulta por ID do Registro</h1></div>
    <br>

    <div>
        <form method="get" action="consulta1.php">
            <label for="txtId">ID do Registro: </label><br>
            <input id="txtId" name="txtId" type="text" value=""><br>
            <input type="submit" value="Consultar">
        </form>
    </div>
    <br>

    <div>
        <?php
        require_once __DIR__ . "/vendor/autoload.php";

        $client = new MongoDB\Client("mongodb+srv://root:@localhost/?serverSelectionTryOnce=false&serverSelectionTimeoutMS=15000&w=majority");

        $db = $client->acerpi;

        var_dump($db);

        ?>
    </div>
    <br>

</body>
</hmtl>