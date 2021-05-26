<!DOCTYPE html>
<?php
error_reporting(E_ALL);
?>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Lista de Nomes de Arquivos de Portarias que possuem um Nome de Servidor relacionado com um Nome de Arquivo de Portaria informado</title>
</head>
<body>
    <div><h1>Lista de Nomes de Arquivos de Portarias que possuem um Nome de Servidor relacionado com um Nome de Arquivo de Portaria informado</h1></div>
    <br>

    <div>
        <form method="get" action="consulta-avancada-6.php">
          <label for="txtNomePortaria">Nome do Arquivo da Portaria: </label><br>
          <input id="txtNomePortaria" name="txtNomePortaria" type="text" value=""><br>
            <input type="submit" value="Consultar">
        </form>
    </div>
    <br>

    <div>
        <?php
        require_once __DIR__ . "/vendor/autoload.php";

        if(!isset($_GET["txtNomePortaria"]) || ($_GET["txtNomePortaria"]=="")) exit;
        $nomePortaria = $_GET["txtNomePortaria"];

        $client = new MongoDB\Client("mongodb://localhost:27017");

        $db = $client->selectDatabase("registros-ufrgs");

        $collection = $db->selectCollection("ufrgs-records");

        $options = [];

        $pipeline = [
        [
            '$match' => [
                'document.name' => $nomePortaria
            ]
        ],
        [
            '$project' => [
                '_id' => FALSE,
                'name' => TRUE
            ]
        ],
        [
            '$lookup' => [
                'from' => 'ufrgs-records',
                'localField' => 'name',
                'foreignField' => 'name',
                'as' => 'documentsOfName'
            ]
        ],
        [
            '$project' => [
                '_id' => FALSE,
                'documentsOfName' => TRUE
            ]
        ],
        [
            '$project' => [
                'documentName' => [
                    '$concatArrays' => [
                        '$documentsOfName.document.name'
                    ]
                ]
            ]
        ],
        [
            '$unwind' => [
                'path' => '$documentName',
                'preserveNullAndEmptyArrays' => FALSE
            ]
        ],
        [
            '$group' => [
                '_id' => '$documentName',
                'documentNameSingle' => [
                    '$first' => '$documentName'
                ]
            ]
        ],
        [
            '$project' => [
                '_id' => FALSE,
                'documentNameSingle' => TRUE
            ]
        ],
        [
            '$match' => [
                'documentNameSingle' => [
                    '$ne' => $nomePortaria
                ]
            ]
        ],
        [
            '$sort' => [
                'documentNameSingle' => -1.0
            ]
        ]
    ];


       ?>
        <div><h1>Parâmetros da Consulta</h1></div>
        <div>
            <div><span>Nome do Arquivo da Portaria:</span> <?=$nomePortaria ?></div>
        </div>
        <br>

        <div>
            <table>
                <tr>
                  <th>Nomes de Portarias</th>
                </tr>
                <?php
                $cursor = $collection->aggregate($pipeline, $options);
                foreach ($cursor as $document) {
                    echo "<tr>";
                    echo "<td>" . $document['documentNameSingle'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </div>
    <br>

</body>
</hmtl>
