<?php
require_once('../../../config.php');
session_start();

if (!isset($_SESSION['cod_univ'])) {

    header("Location: ../../../index.php");
    session_destroy();
    exit();
} else {

    $student = $_SESSION['cod_univ'];
    $url = BASE_URL . 'controller/students.php?op=getCurricula';
    $data = array('codi_carr' => $student['CODI_CARR']);

    $options = array(
        'http' => array(
            'method'  => 'POST',
            'content' => json_encode($data),
            'header' =>  "Content-Type: application/json\r\n" .
                "Accept: application/json\r\n"
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
        <title>Home</title>
        <link rel="stylesheet" href="../../assets/css/style.css">
    </head>

    <body>

        <div class="">
            <div class="card-student">
                <div>
                    <p>
                        <?php echo "Welcome: " . $student['nombres'] . " " . $student['apellidos']; ?>
                    </p>

                    <label for="cont">Numero de creditos:</label>
                    <p id="cont">0</p>
                </div>

                <div><a href="../navigation/nav.php"><button class="btn-c">Desconectar</button></a></div>
            </div>

            <div class="section-data">
                <form method="POST" action="confirm.php">
                    <div class="data-items">

                        <table class="c-table">
                            <thead class="text-center">
                                <tr class="table-header">
                                    <th>Asignatura</th>
                                    <th>Creditos</th>
                                    <th>Ciclo</th>
                                    <th>Seleccionar curso</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                foreach ($response as $row) {

                                ?>
                                    <tr class="table-body font-semibold">
                                        <td><?php echo $row['desc_curs']; ?></td>
                                        <td id="value-courses" class="text-center"><?php echo $row['cred_curs']; ?></td>
                                        <td class="text-center"><?php echo $row['cicl_curs']; ?></td>
                                        <td class="text-center">
                                            <input type="checkbox" name="cursos[]" value="<?php echo $row['codi_curs']; ?>">
                                            Eligir
                                        </td>
                                    </tr>
                                <?php } ?>

                            </tbody>

                        </table>


                        <button id="btn-c" type="submit" disabled class="btn-c">Continuar Matricula</button>

                    </div>



                </form>
            </div>

        </div>

    </body>
    <script src="../../js/validateCourses.js"></script>

    </html>
<?php

}
