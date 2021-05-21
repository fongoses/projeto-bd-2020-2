<!DOCTYPE html>
<?php
error_reporting(E_ALL);
?>
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

        $client = new MongoDB\Client("mongodb://root:@localhost/");

        $db = $client->selectDatabase("acerpi");

        //var_dump($db);

        $collection = $db->selectCollection("ufrgs-records");

        //var_dump($collection);

       ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome Assinatura</th>
                <th>Matrículas SIAPE do Documento</th>
                <th>Nome do Arquivo</th>
            </tr>
            <?php
            echo "teste 1";
            $portarias = $collection->find();
            echo "teste 2";

            var_dump($portarias);

            foreach($portarias as $p) {
                echo "<tr>";
                echo "<td>" . $p->id . "</td>";
                //echo "<td>" . $p->name . "</td>";
                //echo "<td>" . implode($p->siape) . "</td>";
                //echo "<td>" . $p->document->name . "</td>";
                echo "</tr>";
            };


            ?>
        </table>

    </div>
    <br>

</body>
</hmtl>