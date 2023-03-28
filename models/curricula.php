<?php
class Curricula extends Connection
{


    public function getCurricula()
    {

        $conn = parent::getConnection();
        parent::setNames();

        $sql = "SELECT * FROM curricula cu 
                JOIN curso cs on cu.codi_curs = cs.codi_curs
                WHERE cu.acti_curr = 'S'";

        $sql = $conn->prepare($sql);
        $sql->execute();
        $query = $sql->fetchAll(PDO::FETCH_ASSOC);

        $response = [];
        foreach ($query as $r) {
            $response[] = [
                "CODI_CURS" => $r["CODI_CURS"],
                "DESC_CURS" => $r["DESC_CURS"],
                "CURRICULA" => $r
            ];
        }

        return $response;
    }

    public function getCurriculaByCarr($cod_carr)
    {
        $conn = parent::getConnection();
        parent::setNames();

        $sql = "SELECT * FROM curricula 
                WHERE codi_carr = ? AND acti_curr = ? ";


        $sql = $conn->prepare($sql);
        $sql->bindValue(1, $cod_carr);
        $sql->bindValue(2, 'S');
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}
