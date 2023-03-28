<?php

class Courses extends Connection{

    public function getCourses(){
        $conn = parent::getConnection();
        parent::setNames();

        $sql = "SELECT * FROM curso";
        $sql = $conn->prepare($sql);
        $sql->execute();

        return $query = $sql->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getCourseById($id){
        $conn = parent::getConnection();
        parent::setNames();

        $sql = "SELECT * FROM curso WHERE CODI_CURS = ? ";
        $sql = $conn->prepare($sql);
        $sql->bindValue(1, $id);
        $sql->execute();

        return $query = $sql->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>