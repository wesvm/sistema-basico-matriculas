<?php
require_once('../../../config.php');
session_start();

if (!isset($_SESSION['cod_univ'])) {

    header("Location: ../../../index.php");
    session_destroy();
    exit();
} else {
    if (!isset($_SESSION['cursos'])) {
        header("Location: home.php");
        exit();
    }
    $student = $_SESSION['cod_univ'];
    $cu = $_SESSION['cursos'];

    $url = BASE_URL . 'controller/students.php?op=enroll';
    $data = array(
        "code" => $student['CODI_UNIV'],
        "cs" => $cu,
        "tt" => 10
    );

    $options = array(
        'http' => array(
            'method'  => 'POST',
            'content' => json_encode($data),
            'header' =>  "Content-Type: application/json\r\n" .
                "Accept: application/json\r\n"
        ),
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false
        )
    );

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $response = json_decode($result, true);

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Enrolled</title>
        <link rel="stylesheet" href="../../assets/css/style.css">
    </head>

    <body>
        <div class="">

            <div class="card-student">
                <p>
                    <?php echo "Welcome: " . $student['nombres'] . " " . $student['apellidos']; ?>
                </p>
                <div><a href="../navigation/nav.php"><button class="btn-c">Desconectar</button></a></div>
            </div>
            <?php if (!$response["success"]) {
                echo "error message: " . $response["message"];
            } else {
            ?>
                <div>

                    <h1><?php echo $response["cod_u"] . " se ha matriculado satisfactoriamente"; ?></h1>

                </div>
            <?php } ?>

        </div>

    </body>

    </html>

<?php

}
