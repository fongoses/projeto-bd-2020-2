<!DOCTYPE html>
<?php
error_reporting(E_ALL);
?>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Consulta por Múltiplos Campos</title>
</head>
<body>
    <div><h1>Consulta por Múltiplos Campos</h1></div>
    <br>

    <div>
        <form method="get" action="consulta5.php">
            <p>
                <label for="txtId">ID do Registro: </label><br>
                <input id="txtId" name="txtId" type="text" value=""><br>
                <input type="checkbox" name="checkId" value="on"> Incluir na Consulta <br>

                <label for="txtNome">Nome do Servidor: </label><br>
                <input id="txtNome" name="txtNome" type="text" value=""><br>
                <input type="checkbox" name="checkNome" value="on"> Incluir na Consulta <br>

                <label for="txtSiape">Siape: </label><br>
                <input id="txtSiape" name="txtSiape" type="text" value=""><br>
                <input type="checkbox" name="checkSiape" value="on"> Incluir na Consulta <br>

                <label for="txtArquivo">Nome do Arquivo: </label><br>
                <input id="txtArquivo" name="txtArquivo" type="text" value=""><br>
                <input type="checkbox" name="checkArquivo" value="on"> Incluir na Consulta <br>

                <br>
                Operação: <br>
                <select name="selectOperacao">
                    <option value="$and" selected>AND</option>
                    <option value="$or">OR</option>
                </select>

            </p>
            <input type="submit" value="Consultar">
        </form>
    </div>
    <br>

    <div>
        <?php
        require_once __DIR__ . "/vendor/autoload.php";

        if(!isset($_GET['checkId'])
        && !isset($_GET['checkNome'])
        && !isset($_GET['checkSiape'])
        && !isset($_GET['checkArquivo'])) {
            exit;
        }

        $fields = [];

        if(isset($_GET['checkId'])){
          $id = intval($_GET["txtId"]);
          $fields[] = [ 'id' => $id ];
        }

        if(isset($_GET['checkNome'])){
          $nome = $_GET["txtNome"];
          $fields[] = [ 'name' => $nome ];
        }

        if(isset($_GET['checkSiape'])){
          $siape = $_GET["txtSiape"];
          $fields[] = [ 'siape' => $siape ];
        }

        if(isset($_GET['checkArquivo'])){
          $arquivo = $_GET["txtArquivo"];
          $fields[] = [ 'document.name' => $arquivo ];
        }

        $operacao = $_GET["selectOperacao"];

        //print_r($fields); exit;

        // if(!isset($_GET["txtNomePortaria"]) || ($_GET["txtNomePortaria"]=="")) exit;
        // $nomePortaria = $_GET["txtNomePortaria"];

        $client = new MongoDB\Client("mongodb://localhost:27017");

        $db = $client->selectDatabase("registros-ufrgs");

        $collection = $db->selectCollection("ufrgs-records");


        $query = [
            $operacao => $fields
        ];

        //print_r($query); exit;

        $options = [];

       ?>
        <div><h1>Parâmetros da Consulta</h1></div>
        <div>
            <div><span>ID do Registro:</span> <?=$id ?></div>
            <div><span>Nome:</span> <?=$nome ?></div>
            <div><span>SIAPE:</span> <?=$siape ?></div>
            <div><span>Nome do Arquivo:</span> <?=$arquivo ?></div>
            <br>
            <div><span>Operação:</span> <?=$operacao ?></div>
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
