<?php
require_once("../config/conn.php");
require_once("../models/curricula.php");

$curricula = new Curricula();
$body = json_decode(file_get_contents("php://input"), true);

switch ($_GET["op"]) {
    case "getAll":
        $response = $curricula->getCurricula();
        echo json_encode($response);
        break;

    case "getByCodCarr":
        $response = $curricula->getCurriculaByCarr($body["codi_carr"]);
        echo json_encode($response);
        break;

    default:
        echo "error";
        break;
}
