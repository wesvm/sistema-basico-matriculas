<?php
require_once("../config/conn.php");
require_once("../models/student.php");

$students = new Student();
$body = json_decode(file_get_contents("php://input"), true);

function validateInput($body)
{
    $errors = [];

    if (!isset($body["code"]) || empty($body["code"])) {
        $errors[] = "missing student code";
    }

    if (!isset($body["cs"]) || empty($body["cs"])) {
        $errors[] = "missing courses";
    }

    if (!isset($body["tt"]) || empty($body["tt"])) {
        $errors[] = "error";
    }

    return $errors;
}

switch ($_GET["op"]) {
    case "login":
        $cod = $body["cod_univ"];
        $passd = $body["passw"];
        $response = $students->authStudent($cod, $passd);
        echo json_encode($response);
        break;

    case "getCurricula":
        $cod = $body["codi_carr"];
        $response = $students->getCoursesStudent($cod);
        echo json_encode($response);
        break;

    case "getCurriculaByCourse":
        $id = $body["codi_curs"];
        $response = $students->getCoursesbyId($id);
        echo json_encode($response);
        break;

    case "enroll";
        $errors = validateInput($body);
        if (count($errors) > 0) {
            $response = [
                "success" => false,
                "message" => "errors: " . implode(" ", $errors)
            ];
        } else {
            $cod = $body["code"];
            $cs = $body["cs"];
            $tt = $body["tt"];
            $response = $students->enrollStudent($cod, $cs, $tt);
        }
        echo json_encode($response);
        break;

    case "isEnrolledEst";
        $cod = $body["code"];
        $response = $students->isEnrolled($cod);
        echo json_encode($response);
        break;

    case "getEnrolledEst";
        $cod = $body["code"];
        $response = $students->getEnrolledStudent($cod);
        echo json_encode($response);
        break;

    default:
        echo "error";
        break;
}
