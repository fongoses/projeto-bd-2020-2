<!DOCTYPE html>
<?php
error_reporting(E_ALL);
?>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Consulta por Nome Completo</title>
</head>
<body>
    <div><h1>Consulta por Nome Completo</h1></div>
    <br>

    <div>
        <form method="get" action="consulta2.php">
            <label for="txtNome">Nome: </label><br>
            <input id="txtNome" name="txtNome" type="text" value=""><br>
            <input type="submit" value="Consultar">
        </form>
    </div>
    <br>

    <div>
        <?php
        require_once __DIR__ . "/vendor/autoload.php";

        if(!isset($_GET["txtNome"]) || ($_GET["txtNome"]=="")) exit;
        $nome = $_GET["txtNome"];

        $client = new MongoDB\Client("mongodb://localhost:27017");

        $db = $client->selectDatabase("registros-ufrgs");

        $collection = $db->selectCollection("ufrgs-records");

        $query = [
            'name' => $nome
        ];

        $options = [];

       ?>
        <div><h1>Parâmetros da Consulta</h1></div>
        <div>
            <div><span>Nome:</span> <?=$nome ?></div>
        </div>
        <br>

        <div>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nome Assinatura</th>
                    <th>Matrículas SIAPE do Documento</th>
                    <th>Nome do Arquivo</th>
                </tr>
                <?php
                $portarias = $collection->find($query, $options);

                foreach($portarias as $p) {
                    unset($siape);
                    foreach($p["siape"] as $s) {
                        $siape[] = $s;
                    }

                    echo "<tr>";
                    echo "<td>" . $p->id . "</td>";
                    echo "<td>" . $p->name . "</td>";
                    echo "<td>" . implode(", ", $siape) . "</td>";
                    echo "<td>" . $p->document->name . "</td>";
                    echo "</tr>";
                };
                ?>
            </table>
        </div>
    </div>
    <br>

</body>
</hmtl>
