<?php
    require_once("../config/conn.php");
    require_once("../models/courses.php");

    $courses = new Courses();
    $body = json_decode(file_get_contents("php://input"), true);

    switch($_GET["op"]){
        case "getAll":
            $response = $courses->getCourses();
            echo json_encode($response);
        break;

        case "getAllDesc":
            $response = $courses->getCourses();
            echo json_encode($response);

        break;

        case "getById":
            $response = $courses->getCourseById($body["codi_curs"]);
            echo json_encode($response);
        break;

        default:
            echo "error";
            break;

    }

?>