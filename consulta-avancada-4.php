<!DOCTYPE html>
<?php
error_reporting(E_ALL);
?>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Lista de Nomes de Servidor que aparecem em uma mesma portaria de um Nome de Servidor Informado</title>
</head>
<body>
    <div><h1>Lista de Nomes de Servidor que aparecem em uma mesma portaria de um Nome de Servidor Informado</h1></div>
    <br>

    <div>
        <form method="get" action="consulta-avancada-4.php">
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

        $options = [];

        $pipeline = [
        [
            '$match' => [
                'name' => $nome
            ]
        ],
        [
            '$lookup' => [
                'from' => 'ufrgs-records',
                'localField' => 'document.name',
                'foreignField' => 'document.name',
                'as' => 'documentsOfName'
            ]
        ],
        [
            '$project' => [
                '_id' => FALSE,
                'documentsOfName.name' => TRUE
            ]
        ],
        [
            '$unwind' => [
                'path' => '$documentsOfName'
            ]
        ],
        [
            '$replaceRoot' => [
                'newRoot' => '$documentsOfName'
            ]
        ],
        [
            '$group' => [
                '_id' => '$name',
                'nameSingle' => [
                    '$first' => '$name'
                ]
            ]
        ],
        [
            '$project' => [
                '_id' => FALSE,
                'nameSingle' => TRUE
            ]
        ],
        [
            '$match' => [
                'nameSingle' => [
                    '$ne' => $nome
                ]
            ]
        ],
        [
            '$sort' => [
                'nameSingle' => 1.0
            ]
        ]
    ];


       ?>
        <div><h1>Parâmetros da Consulta</h1></div>
        <div>
            <div><span>Nome:</span> <?=$nome ?></div>
        </div>
        <br>

        <div>
            <table>
                <tr>
                  <th>Nomes de Servidores</th>
                </tr>
                <?php
                $cursor = $collection->aggregate($pipeline, $options);
                foreach ($cursor as $document) {
                    echo "<tr>";
                    echo "<td>" . $document['nameSingle'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </div>
    <br>

</body>
</hmtl>
