<?php
require_once('../../../config.php');
session_start();

if (!isset($_SESSION['cod_univ'])) {

    header("Location: ../../../index.php");
    session_destroy();
    exit();
} else {
    $student = $_SESSION['cod_univ'];
    $url = BASE_URL . 'controller/students.php?op=getEnrolledEst';
    $data = array('code' => $student["CODI_UNIV"]);

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

    if (!empty($response) && array_key_exists('error', $response)) {
        $r = $response["message"];
    } else {
        $r = "An unexpected error occurred.";
    }

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Detail</title>
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
            <?php if (isset($response["student"])) {
            ?>
                <div class="section-data">
                    <br>
                    <h2 class="text-center">Ya estas matriculado</h2>
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
                                            <td><?php echo $row['DESC_CURS']; ?></td>
                                            <td class="text-center"><?php echo $row['CRED_CURS']; ?></td>
                                            <td class="text-center"><?php echo $row['CICL_CURS']; ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr class="table-body font-semibold">
                                        <td>Creditos totales</td>
                                        <td colspan="2" class="text-center">
                                            <?php echo $response["student"]["TOTA_CRED"]; ?></td>
                                    </tr>
                                </tbody>

                            </table>


                        </div>
                    </form>
                </div>
            <?php
            } else {
            ?>
                <div>
                    <h1><?php echo "Ocurrio un error " . $r; ?></h1>
                </div>
            <?php } ?>

        </div>
    </body>

    </html>

<?php
}
?>