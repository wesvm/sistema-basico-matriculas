<?php
require_once('../../../config.php');
session_start();

if (!isset($_SESSION['cod_univ'])) {

    header("Location: ../../../index.php");
    session_destroy();
    exit();
} else {
    if (!isset($_POST['cursos'])) {
        header("Location: home.php");
        exit();
    }

    $student = $_SESSION['cod_univ'];
    $url = BASE_URL . 'controller/students.php?op=getCurriculaByCourse';
    $data = array('codi_curs' => $_POST['cursos']);
    $_SESSION['cursos'] = $_POST['cursos'];

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
        <title>Confirmar</title>
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
            <?php if (isset($response["success"]) && !$response["success"]) {
                echo $response["message"];
            } else {
            ?>
                <div class="section-data">
                    <form method="POST" action="enrolled.php">
                        <div class="data-items">
                            <table class="c-table">
                                <thead class="text-center">
                                    <tr class="table-header">
                                        <th>Asignatura</th>
                                        <th>Creditos</th>
                                        <th>Ciclo</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    foreach ($response["courses"] as $row) {

                                    ?>
                                        <tr class="table-body font-semibold">
                                            <td><?php echo $row['desc_curs']; ?></td>
                                            <td class="text-center"><?php echo $row['cred_curs']; ?></td>
                                            <td class="text-center"><?php echo $row['cicl_curs']; ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr class="table-body font-semibold">
                                        <td>Creditos totales</td>
                                        <td colspan="2" class="text-center"><?php echo $response["total"]; ?></td>
                                    </tr>
                                </tbody>

                            </table>

                            <div class="">
                                <button class="btn-c" type="submit">Confirmar</button>
                                <a href="home.php">Regresar</a>
                            </div>
                        </div>
                    </form>
                </div>
            <?php } ?>

        </div>

    </body>

    </html>

<?php

}
