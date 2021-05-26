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
              <input id="txtId" name="txtId" type="text" value="">
              <input type="checkbox" name="checkID" value="on"> Incluir na Consulta <br><br>
              <select name="Operacao">
                    <option value=""></option>
                    <option value="andOP">AND</option>
                    <option value="orOP">OR</option>
                </select>
                Operação <br><br>

              <label for="txtNome">Nome do Servidor: </label><br>
              <input id="txtNome" name="txtNome" type="text" value="">
              <input type="checkbox" name="checkNomeServidor" value="on"> Incluir na Consulta <br><br>
              <select name="Operacao2">
                    <option value=""></option>
                    <option value="andOP">AND</option>
                    <option value="orOP">OR</option>
                </select>
                Operação <br><br>

            </p>
            <input type="submit" value="Consultar">
        </form>
    </div>
    <br>

    <div>
        <?php
        require_once __DIR__ . "/vendor/autoload.php";

        if(isset($_POST['checkID'])){
          $id = intval($_GET["txtId"]);
        } else {
          $id = "";
        }

        if(isset($_POST['checkNomeServidor'])){
          $nome = $_GET["txtNome"];
        } else {
          $nome = "";
        }
        //
        // if(!isset($_GET["txtNomePortaria"]) || ($_GET["txtNomePortaria"]=="")) exit;
        // $nomePortaria = $_GET["txtNomePortaria"];

        $client = new MongoDB\Client("mongodb://localhost:27017");

        $db = $client->selectDatabase("registros-ufrgs");

        $collection = $db->selectCollection("ufrgs-records");


        $query = [
          'id' => $id,
          'name' => $nome
        ];

        $options = [];

       ?>
        <div><h1>Parâmetros da Consulta</h1></div>
        <div>
            <div><span>ID do Registro:</span> <?=$id ?></div>
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
