<?php
class Student extends Connection
{

    public function getStudents()
    {

        $conn = parent::getConnection();
        parent::setNames();

        $sql = "SELECT * FROM carrera ca INNER JOIN estudiantes es
                ON ca.codi_carr = es.codi_carr";

        $sql = $conn->prepare($sql);
        $sql->execute();
        $query = $sql->fetchAll(PDO::FETCH_ASSOC);

        $response = [];
        foreach ($query as $r) {
            $response[] = [
                "Codigo" => $r["CODI_UNIV"],
                "Apellidos" => $r["apellidos"],
                "Nombres"   => $r["nombres"],
                "Telefono"  => $r["telefono"],
                "Correo"    => $r["correo"],
                "Carrera"   => [
                    "Codigo" => $r["CODI_CARR"],
                    "Nombre" => $r["DESC_CARR"],
                    "Sede" => $r["CODI_SEDE"],
                ],
                "UltimoSemestre" => $r["SEME_ULTI"],
            ];
        }

        return $response;
    }

    public function getCoursesStudent($cod)
    {

        $conn = parent::getConnection();
        parent::setNames();

        $sql = "SELECT * FROM curricula cl INNER JOIN curso cs
                ON cl.codi_curs = cs.codi_curs
                WHERE CODI_CARR = ?
                AND acti_curr = ?";

        $sql = $conn->prepare($sql);
        $sql->bindValue(1, $cod);
        $sql->bindValue(2, 'S');
        $sql->execute();
        $query = $sql->fetchAll(PDO::FETCH_ASSOC);

        $pre_curs = array_unique(array_merge(
            array_column($query, "CODI_CURS"),
            array_column($query, "pre1_curs"),
            array_column($query, "pre2_curs"),
            array_column($query, "pre3_curs"),
            array_column($query, "pre4_curs"),
            array_column($query, "pre5_curs")
        ));

        $sql = "SELECT CODI_CURS, DESC_CURS FROM curso 
                WHERE CODI_CURS IN ('" . implode("', '", $pre_curs) . "')";
        $rpre_curs = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $cursos = array_column($rpre_curs, "DESC_CURS", "CODI_CURS");

        $response = [];

        foreach ($query as $r) {

            $prerequisitos = [];
            for ($i = 1; $i <= 5; $i++) {
                $campo_prerequisito = "pre{$i}_curs";
                $cod_prerequisito = $r[$campo_prerequisito];
                if ($cod_prerequisito != "") {
                    $prerequisitos[] = [
                        "codi_curs" => $cod_prerequisito,
                        "desc_curs" => $cursos[$cod_prerequisito]
                    ];
                }
            }

            $response[] = [
                "codi_carr" => $r["codi_carr"],
                "codi_curs" => $r["CODI_CURS"],
                "desc_curs" => $r["DESC_CURS"],
                "cred_curs" => $r["cred_curs"],
                "hrst_curs" => $r["hrst_curs"],
                "hrsp_curs" => $r["hrsp_curs"],
                "cicl_curs" => $r["cicl_curs"],
                "acti_curr" => $r["acti_curr"],
                "prer_curs" => $prerequisitos,
                "anyo_curr" => $r["anyo_curr"],
            ];
        }

        return $response;
    }

    public function getCoursesbyId($id)
    {
        $conn = parent::getConnection();
        parent::setNames();

        $sql = "
        SELECT cl.codi_carr, cl.codi_curs, cs.DESC_CURS, cl.cred_curs, cl.hrst_curs, cl.hrsp_curs, cl.cicl_curs, cl.anyo_curr, SUM(cl.cred_curs) AS 'cred_total' FROM curricula cl INNER JOIN curso cs ON cl.codi_curs = cs.codi_curs WHERE cl.codi_curs IN (";

        if (empty($id)) {
            return ["success" => false];
        }

        for ($i = 0; $i < count($id); $i++) {
            $sql .= "?";
            if ($i < count($id) - 1) {
                $sql .= ", ";
            }
        }

        $sql .= ") AND cl.acti_curr = ? GROUP BY cl.codi_curs WITH ROLLUP";
        $sql = $conn->prepare($sql);
        foreach ($id as $i => $key) {
            $sql->bindValue($i + 1, $key);
        }
        $sql->bindValue(count($id) + 1, 'S');
        $sql->execute();
        $query = $sql->fetchAll(PDO::FETCH_ASSOC);

        $total_row = end($query);
        $total_cred = $total_row['cred_total'];

        $response = [];
        $cursos = [];
        $numRows = count($query);
        if ($total_cred >= 10 && $total_cred <= 18) {
            foreach ($query as $i => $r) {

                if ($i == $numRows - 1) {
                    continue;
                }

                $cursos[] = [
                    "codi_carr" => $r["codi_carr"],
                    "codi_curs" => $r["codi_curs"],
                    "desc_curs" => $r["DESC_CURS"],
                    "cred_curs" => $r["cred_curs"],
                    "hrst_curs" => $r["hrst_curs"],
                    "hrsp_curs" => $r["hrsp_curs"],
                    "cicl_curs" => $r["cicl_curs"],
                    "anyo_curr" => $r["anyo_curr"],
                ];
            }

            $response = [
                "total" => $total_cred,
                "courses" => $cursos

            ];
        } else {
            return $response[] = [
                "success" => false,
                "message" => "out of limit"
            ];
        }

        return $response;
    }

    public function authStudent($cod, $pass)
    {
        $conn = parent::getConnection();
        parent::setNames();

        $sql = "SELECT * FROM estudiantes WHERE CODI_UNIV = ? AND CLAV_UNIV = ?";
        $sql = $conn->prepare($sql);
        $sql->bindValue(1, $cod);
        $sql->bindValue(2, $pass);
        $sql->execute();

        $query = $sql->fetchAll(PDO::FETCH_ASSOC);

        $response = [];

        if ($query) {
            $response = [
                "success" => true,
                "user" => $query
            ];
        } else {
            $response = [
                "success" => false,
                "message" => "Usuario o contraseña incorrectos"
            ];
        }
        return $response;
    }

    public function getStId($cod)
    {
        $conn = parent::getConnection();
        parent::setNames();

        $sql = "SELECT CODI_CARR, CODI_SEDE FROM estudiantes WHERE CODI_UNIV = ?";
        $sql = $conn->prepare($sql);
        $sql->bindValue(1, $cod);

        $sql->execute();
        $query = $sql->fetch(PDO::FETCH_ASSOC);

        if (!$query) {
            throw new Exception("Student not found");
        }

        return $query;
    }

    public function isEnrolled($cod)
    {
        $conn = parent::getConnection();
        parent::setNames();

        try {
            $st = $this->getStId($cod);

            $sql = "SELECT COUNT(*) FROM `ematricula` WHERE `CODI_UNIV` = ?";
            $sql = $conn->prepare($sql);
            $sql->bindValue(1, $cod);
            $sql->execute();

            $query = $sql->fetch(PDO::FETCH_ASSOC);

            $response = [];

            if ($query['COUNT(*)'] > 0) {
                $response = [
                    "success" => false,
                    "message" => "Student is enrolled"
                ];
            } else {
                $response = [
                    "success" => true,
                    "message" => "enroll"
                ];
            }
        } catch (Exception $e) {
            return [
                "error" => "error",
                "message" => $e->getMessage()
            ];
        }
        return $response;
    }

    public function enrollDetail($cod, $cs)
    {
        $conn = parent::getConnection();
        parent::setNames();

        if (empty($cod) || empty($cs)) {
            throw new InvalidArgumentException('Los parámetros son requeridos');
        }

        $cs_detail = $this->getCoursesbyId($cs);

        if (isset($cs_detail["success"]) && !$cs_detail["success"]) {
            return $cs_detail;
        }
        $total = 0;
        foreach ($cs_detail["courses"] as $row) {
            $sql = "INSERT INTO `dmatricula` (`iddmatricula`, `CODI_UNIV`, `CICL_ACAD`, `CODI_CURS`, `CRED_CURS`, `HRST_CURS`, `HRSP_CURS`, `CICL_CURS`, `SECC_MATR`, `FECH_REGI`, `OBSE_CONV`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NULL)";

            $sql = $conn->prepare($sql);
            $sql->bindValue(1, $cod);
            $sql->bindValue(2, '20222');
            $sql->bindValue(3, $row['codi_curs']);
            $sql->bindValue(4, $row['cred_curs']);
            $sql->bindValue(5, $row['hrst_curs']);
            $sql->bindValue(6, $row['hrsp_curs']);
            $sql->bindValue(7, $row['cicl_curs']);
            $sql->bindValue(8, 'A');

            if (!$sql->execute()) {
                throw new Exception("Error inserting enroll detail: " . $sql->errorInfo()[2]);
            }

            $total += $row['cred_curs'];
        }
        return $total;
    }

    public function enrollStudent($cod, $cs, $tt)
    {
        $conn = parent::getConnection();
        parent::setNames();

        try {

            $st = $this->getStId($cod);
            $res = $this->isEnrolled($cod);

            if (!$res["success"]) {
                return $res;
            }

            $total = $this->enrollDetail($cod, $cs);
            if (isset($total["success"]) && !$total["success"]) {
                return $total;
            }
            $sql = "INSERT INTO `ematricula` (`CODI_UNIV`, `CICL_ACAD`, `CODI_SEDE`, `CODI_CARR`, `TOTA_CRED`, `FECH_MATR`) VALUES (?, ?, ?, ?, ?, NOW())";

            $sql = $conn->prepare($sql);
            $sql->bindValue(1, $cod);
            $sql->bindValue(2, '20222');
            $sql->bindValue(3, $st['CODI_SEDE']);
            $sql->bindValue(4, $st['CODI_CARR']);
            $sql->bindValue(5, $total);
            $sql->execute();

            $response = [];

            if ($sql->rowCount() > 0) {
                $response = [
                    "success" => true,
                    "cod_u" => $cod
                ];
            } else {
                $response = [
                    "success" => false,
                    "message" => "Ocurrió un error.."
                ];
            }
        } catch (Exception $e) {
            return [
                "success" => false,
                "message" => $e->getMessage()
            ];
        }

        return $response;
    }

    public function getEnrolledStudent($cod)
    {
        $conn = parent::getConnection();
        parent::setNames();


        $is_en = $this->isEnrolled($cod);
        $response = [];
        if (isset($is_en["success"]) && !$is_en["success"]) {
            $sql = "SELECT * FROM dmatricula dm INNER JOIN curso c ON dm.CODI_CURS = c.CODI_CURS WHERE CODI_UNIV = ?";

            $sql = $conn->prepare($sql);
            $sql->bindValue(1, $cod);
            $sql->execute();
            $query = $sql->fetchAll(PDO::FETCH_ASSOC);
            $stu = $this->getEnSD($cod);

            $response = [
                "student" => $stu,
                "courses" => $query
            ];
        } else {
            $response = $is_en;
        }

        return $response;
    }

    public function getEnSD($cod)
    {
        $conn = parent::getConnection();
        parent::setNames();

        $sql = "SELECT CODI_UNIV, CICL_ACAD, CODI_SEDE, CODI_CARR, TOTA_CRED, FECH_MATR FROM ematricula WHERE CODI_UNIV = ?";

        $sql = $conn->prepare($sql);
        $sql->bindValue(1, $cod);
        $sql->execute();
        $query = $sql->fetch(PDO::FETCH_ASSOC);

        return $query;
    }
}
