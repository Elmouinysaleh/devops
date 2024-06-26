<?php
class Actions{
    private $conn;
    
    function __construct(){
        require_once(realpath(__DIR__.'/../db-connect.php'));
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    /**
     * Class Actions
     */
    public function getNumberOfClasses() {
        $query = "SELECT COUNT(*) as count FROM class_tbl";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count;
    }

    public function getNumberOfStudents() {
        $query = "SELECT COUNT(*) as count FROM students_tbl";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count;
    }

    public function save_class(){
        foreach($_POST as $k => $v){
            if(!is_array($_POST[$k]) && !is_numeric($_POST[$k]) && !empty($_POST[$k])){
                $_POST[$k] = addslashes(htmlspecialchars($v));
            }
        }
        extract($_POST);

        if(!empty($id)){
            $check = $this->conn->query("SELECT id FROM `class_tbl` where `name` = '{$name}' and `id` != '{$id}' ");
            $sql = "UPDATE `class_tbl` set `name` = '{$name}' where `id` = '{$id}'";
        }else{
            $check = $this->conn->query("SELECT id FROM `class_tbl` where `name` = '{$name}' ");
            $sql = "INSERT `class_tbl` set `name` = '{$name}'";
        }
        if($check->num_rows > 0){
            return ['status' => 'error', 'msg' => 'Le nom de la classe existe déjà!'];
        }else{
            $qry = $this->conn->query($sql);
            if($qry){
                if(empty($id)){
                    $_SESSION['flashdata'] = [ 'type' => 'success', 'msg' => "Une nouvelle classe a été ajoutée avec succès!" ];
                }else{
                    $_SESSION['flashdata'] = [ 'type' => 'success', 'msg' => "Les données de la classe ont été mises à jour avec succès!" ];
                }
                return [ 'status' => 'success'];
            }else{
                if(empty($id)){
                    return ['status' => 'error', 'msg' => 'Une erreur est survenue lors de l\'enregistrement de la nouvelle classe!'];
                }else{
                    return ['status' => 'error', 'msg' => 'Une erreur est survenue lors de la mise à jour des données de la classe!'];
                }
            }
        }
    }

    public function delete_class(){
        extract($_POST);
        $delete = $this->conn->query("DELETE FROM `class_tbl` where `id` = '{$id}'");
        if($delete){
            $_SESSION['flashdata'] = [ 'type' => 'success', 'msg' => "La classe a été supprimée avec succès!" ];
            return [ "status" => "success" ];
        }else{
            $_SESSION['flashdata'] = [ 'type' => 'danger', 'msg' => "La suppression de la classe a échoué pour une raison inconnue!" ];
            return [ "status" => "error", "msg" => "La suppression de la classe a échoué!" ];
        }
    }

    public function list_class(){
        $sql = "SELECT * FROM `class_tbl` order by `name` ASC";
        $qry = $this->conn->query($sql);
        return $qry->fetch_all(MYSQLI_ASSOC);
    }

    public function get_class($id=""){
        $sql = "SELECT * FROM `class_tbl` where `id` = '{$id}'";
        $qry = $this->conn->query($sql);
        $result = $qry->fetch_assoc();
        return $result;
    }

    /**
     * Student Actions
     */
    
    public function save_student(){
        foreach($_POST as $k => $v){
            if(!is_array($_POST[$k]) && !is_numeric($_POST[$k]) && !empty($_POST[$k])){
                $_POST[$k] = addslashes(htmlspecialchars($v));
            }
        }
        extract($_POST);

        if(!empty($id)){
            $check = $this->conn->query("SELECT id FROM `students_tbl` where `name` = '{$name}' and `class_id` = '{$class_id}' and `id` != '{$id}' ");
            $sql = "UPDATE `students_tbl` set `name` = '{$name}', `class_id` = '{$class_id}' where `id` = '{$id}'";
        }else{
            $check = $this->conn->query("SELECT id FROM `students_tbl` where `name` = '{$name}' and `class_id` = '{$class_id}' ");
            $sql = "INSERT `students_tbl` set `name` = '{$name}', `class_id` = '{$class_id}'";
        }
        if($check->num_rows > 0){
            return ['status' => 'error', 'msg' => 'Le nom de l\'étudiant existe déjà!'];
        }else{
            $qry = $this->conn->query($sql);
            if($qry){
                if(empty($id)){
                    $_SESSION['flashdata'] = [ 'type' => 'success', 'msg' => "Un nouvel étudiant a été ajouté avec succès!" ];
                }else{
                    $_SESSION['flashdata'] = [ 'type' => 'success', 'msg' => "Les données de l'étudiant ont été mises à jour avec succès!" ];
                }
                return [ 'status' => 'success'];
            }else{
                if(empty($id)){
                    return ['status' => 'error', 'msg' => 'Une erreur est survenue lors de l\'enregistrement de la nouvelle classe!'];
                }else{
                    return ['status' => 'error', 'msg' => 'Une erreur est survenue lors de la mise à jour des données de l\'étudiant!'];
                }
            }
        }
    }

    public function delete_student(){
        extract($_POST);
        $delete = $this->conn->query("DELETE FROM `students_tbl` where `id` = '{$id}'");
        if($delete){
            $_SESSION['flashdata'] = [ 'type' => 'success', 'msg' => "L'étudiant a été supprimé avec succès!" ];
            return [ "status" => "success" ];
        }else{
            $_SESSION['flashdata'] = [ 'type' => 'danger', 'msg' => "La suppression de l'étudiant a échoué pour une raison inconnue!" ];
            return [ "status" => "error", "msg" => "La suppression de l'étudiant a échoué!" ];
        }
    }

    public function list_student(){
        $sql = "SELECT `students_tbl`.*, `class_tbl`.`name` as `class` FROM `students_tbl` inner join `class_tbl` on `students_tbl`.`class_id` = `class_tbl`.`id` order by `students_tbl`.`name` ASC";
        $qry = $this->conn->query($sql);
        return $qry->fetch_all(MYSQLI_ASSOC);
    }

    public function get_student($id=""){
        $sql = "SELECT `students_tbl`.*, `class_tbl`.`name` as `class` FROM `students_tbl` inner join `class_tbl` on `students_tbl`.`class_id` = `class_tbl`.`id` where `students_tbl`.`id` = '{$id}'";
        $qry = $this->conn->query($sql);
        $result = $qry->fetch_assoc();
        return $result;
    }

    public function attendanceStudents($class_id = "", $class_date = ""){
        if(empty($class_id) || empty($class_date))
            return [];
        $sql = "SELECT `students_tbl`.*, COALESCE((SELECT `status` FROM `attendance_tbl` where `student_id` = `students_tbl`.id and `class_date` = '{$class_date}' ), 0) as `status` FROM `students_tbl` where `class_id` = '{$class_id}' order by `name` ASC";
        $qry = $this->conn->query($sql);
        $result = $qry->fetch_all(MYSQLI_ASSOC);
        return $result;
    }
    
    public function attendanceStudentsMonthly($class_id = "", $class_month = ""){
        if(empty($class_id) || empty($class_month))
            return [];
        $sql = "SELECT `students_tbl`.* FROM `students_tbl` where `class_id` = '{$class_id}' order by `name` ASC";
        $qry = $this->conn->query($sql);
        $result = $qry->fetch_all(MYSQLI_ASSOC);
        foreach($result as $k => $row){
            $att_sql = "SELECT `status`, `class_date` FROM `attendance_tbl` where `student_id` = '{$row['id']}' ";
            $att_qry = $this->conn->query($att_sql);
            foreach($att_qry as $att_row){
                $result[$k]['attendance'][$att_row['class_date']] = $att_row['status'];
            }
        }
        return $result;
    }

    public function save_attendance(){
        extract($_POST);

        $sql_values = "";
        $errors = "";
        foreach($student_id as $k => $sid){
            $stat = $status[$k] ?? 3;

            $check = $this->conn->query("SELECT id FROM `attendance_tbl` where `student_id` = '{$sid}' and `class_date` = '{$class_date}'");
            if($check->num_rows > 0){
                $result = $check->fetch_assoc();
                $att_id = $result['id'];

                try{
                    $update = $this->conn->query("UPDATE `attendance_tbl` set `status` = '{$stat}' where `id` = '{$att_id}'");
                }catch(Exception $e){
                    if(!empty($errors)) $errors .= "<br>";
                    $errors .= $e->getMessage();
                }
            }else{
                if(!empty($sql_values)) $sql_values .= ", ";
                $sql_values .= "( '{$sid}', '{$class_date}', '{$stat}' )";
            }
        }
        if(!empty($sql_values))
        {
            try{
                $sql =  $this->conn->query("INSERT INTO `attendance_tbl` ( `student_id`, `class_date`, `status` ) VALUES {$sql_values}");
            }catch(Exception $e){
                if(!empty($errors)) $errors .= "<br>";
                $errors .= $e->getMessage();
            }
        }
        if(empty($errors)){
            $resp['status'] = "success";
            $_SESSION['flashdata'] = [ "type" => "success", "msg" => "Les données de présence de la classe ont été enregistrées avec succès." ];
        }else{
            $resp['status'] = "error";
            $resp['msg'] = $errors;
        }

        return $resp;
    }

    function __destruct()
    {
        if($this->conn)
        $this->conn->close(); 
    }
}
?>
