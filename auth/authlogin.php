<?php
require_once('../config.php');

if (isset($_POST['username']) && isset($_POST['password'])) {
    $cod_u = $_POST['username'];
    $passd = $_POST['password'];

    $url = BASE_URL . 'controller/students.php?op=login';
    $data = array('cod_univ' => $cod_u, 'passw' => $passd);

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

    if ($response['success']) {
        session_start();
        $_SESSION['cod_univ'] = $response['user'][0];

        $url_e = BASE_URL . 'controller/students.php?op=isEnrolledEst';
        $data_e = array('code' => $cod_u);

        $options_e = array(
            'http' => array(
                'method'  => 'POST',
                'content' => json_encode($data_e),
                'header' =>  "Content-Type: application/json\r\n" .
                    "Accept: application/json\r\n"
            ),
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false
            )
        );

        $context_e  = stream_context_create($options_e);
        $result_e = file_get_contents($url_e, false, $context_e);
        $response_e = json_decode($result_e, true);

        if ($response_e['success']) {
            header("Location: ../src/views/home/home.php");
            exit();
        } else {
            header("Location: ../src/views/enrolled/home.php");
            exit();
        }
    } else {
        $message = 'loginerror';
        header('Location: ../index.php?ms=' . urlencode($message));
        exit();
    }
}
