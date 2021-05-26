<!DOCTYPE html>
<?php
error_reporting(E_ALL);
?>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Consulta por Múltiplos Valores</title>

<script>
function addField(){

    var form = document.getElementById("form");
    var paragraph = form.getElementsByTagName("p")[0];

    var newLine = document.createElement("br")

    // create an input field to insert
    var newField = document.createElement("input");

    // set input field data type to text
    newField.setAttribute("type", "text");

    // set input field name
    newField.setAttribute("name", "txtValor[]");

    // select last position to insert element before it
    var pos = form.childElementCount;
    // insert element
    form.insertBefore(newField, form.childNodes[pos]);

    var pos = form.childElementCount;
    form.insertBefore(newLine, form.childNodes[pos]);

}
</script>

</head>
<body>
    <div><h1>Consulta por Múltiplos Valores</h1></div>
    <br>

    <div>
        <form id="form" name="name" method="get" action="consulta6.php">
            <p>
                <label for="txtValor">
                    <select id="selectCampo" name="selectCampo">
                        <option value="id" selected>ID do Registro</option>
                        <option value="name">Nome do Servidor</option>
                        <option value="siape">Siape</option>
                        <option value="document.name">Nome do Arquivo</option>
                    </select>:
                </label>
                <input id="btnAdd" name="btnAdd" type="button" value="Adicionar Valor" onclick="addField()"><br>

                <input id="txtValor" name="txtValor[]" type="text" value=""><br>

            </p>

            <input type="submit" value="Consultar"><br>
        </form>
    </div>
    <br>

    <div>
        <?php
        require_once __DIR__ . "/vendor/autoload.php";

        if(!isset($_GET['txtValor'][0])) {
            exit;
        }

        $field = $_GET["selectCampo"];

        $valores = $_GET['txtValor'];

        if($field == "id") {
            unset($valorNumerico);
            foreach($valores as $v) {
                $valoresNumerico[] = intval($v);
            }
            $valores = $valoresNumerico;
        }

        $client = new MongoDB\Client("mongodb://localhost:27017");

        $db = $client->selectDatabase("registros-ufrgs");

        $collection = $db->selectCollection("ufrgs-records");

        $query = [
            $field => [
                '$in' => $valores
            ]
        ];

        //print_r($query); exit;

        $options = [];

       ?>
        <div><h1>Parâmetros da Consulta</h1></div>
        <div>
            <div><span><?=$field ?>:</span> <?=implode(", ", $valores) ?></div>
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
