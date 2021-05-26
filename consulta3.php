<!DOCTYPE html>
<?php
error_reporting(E_ALL);
?>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Consulta por Matrícula SIAPE</title>
</head>
<body>
    <div><h1>Consulta por Matrícula SIAPE</h1></div>
    <br>

    <div>
        <form method="get" action="consulta3.php">
            <label for="txtSIAPE">Número da Matrícula SIAPE: </label><br>
            <input id="txtSIAPE" name="txtSIAPE" type="text" value=""><br>
            <input type="submit" value="Consultar">
        </form>
    </div>
    <br>

    <div>
        <?php
        require_once __DIR__ . "/vendor/autoload.php";

        if(!isset($_GET["txtSIAPE"]) || ($_GET["txtSIAPE"]=="")) exit;
        $siape = $_GET["txtSIAPE"];

        $client = new MongoDB\Client("mongodb://localhost:27017");

        $db = $client->selectDatabase("registros-ufrgs");

        $collection = $db->selectCollection("ufrgs-records");

        $query = [
        '$or' => [
            [
              'siape.0' => $siape
            ],
            [
              'siape.1' => $siape
            ]
        ]
        ];

        $options = [];

       ?>
        <div><h1>Parâmetros da Consulta</h1></div>
        <div>
            <div><span>Número da Matrícula SIAPE:</span> <?=$siape ?></div>
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
