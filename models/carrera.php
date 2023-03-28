<?php 
class Carrera extends Connection{

    public function getCarrera(){

        $conn = parent::getConnection();
        parent::setNames();

        $sql = "SELECT * FROM carrera ca 
                JOIN curso cs on cu.codi_curs = cs.codi_curs
                WHERE cu.acti_curr = 'S'";

        $sql = $conn->prepare($sql);
        $sql->execute();
        $query = $sql->fetchAll(PDO::FETCH_ASSOC);

        $response = [];
        foreach($query as $r){
            $response[] = [
                "CODI_CURS" => $r["CODI_CURS"],
                "DESC_CURS" => $r["DESC_CURS"],
                "CURRICULA" => $r
            ];
        }

        return $response;

    }

}

?>