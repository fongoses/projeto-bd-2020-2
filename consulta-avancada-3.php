<!DOCTYPE html>
<?php
error_reporting(E_ALL);
?>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Lista de Matrículas SIAPE que aparecem em uma mesma portaria de uma Matrícula SIAPE informada</title>
</head>
<body>
    <div><h1>Lista de Matrículas SIAPE que aparecem em uma mesma portaria de uma Matrícula SIAPE informada</h1></div>
    <br>

    <div>
        <form method="get" action="consulta-avancada-3.php">
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

        $options = [];

        $pipeline = [
            [
                '$match' => [
                    'siape' => $siape
                ]
            ],
            [
                '$lookup' => [
                    'from' => 'ufrgs-records',
                    'localField' => 'document.name',
                    'foreignField' => 'document.name',
                    'as' => 'documentsOfSiape'
                ]
            ],
            [
                '$project' => [
                    '_id' => FALSE,
                    'documentsOfSiape' => TRUE
                ]
            ],
            [
                '$project' => [
                    'listaSiape' => [
                        '$concatArrays' => [
                            '$documentsOfSiape.siape'
                        ]
                    ]
                ]
            ],
            [
                '$unwind' => [
                    'path' => '$listaSiape'
                ]
            ],
            [
                '$unwind' => [
                    'path' => '$listaSiape'
                ]
            ],
            [
                '$group' => [
                    '_id' => '$listaSiape',
                    'siapeSingle' => [
                        '$first' => '$listaSiape'
                    ]
                ]
            ],
            [
                '$project' => [
                    '_id' => FALSE,
                    'siapeSingle' => TRUE
                ]
            ],
            [
                '$match' => [
                    'siapeSingle' => [
                        '$ne' => $siape
                    ]
                ]
            ],
            [
                '$sort' => [
                    'siapeSingle' => -1.0
                ]
            ]
        ];


       ?>
        <div><h1>Parâmetros da Consulta</h1></div>
        <div>
            <div><span>SIAPE:</span> <?=$siape ?></div>
        </div>
        <br>

        <div>
            <table>
                <tr>
                  <th>Matrículas SIAPE</th>
                </tr>
                <?php
                $cursor = $collection->aggregate($pipeline, $options);
                foreach ($cursor as $document) {
                    echo "<tr>";
                    echo "<td>" . $document['siapeSingle'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </div>
    <br>

</body>
</hmtl>
