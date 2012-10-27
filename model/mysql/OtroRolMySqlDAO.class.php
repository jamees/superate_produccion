<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CursosMySqlDAO
 *
 * @author JorgePaz
 */
//put your code here
/* * SELECT C.id,C.fullname,C.shortname 
 * FROM prefix_role_assignments A, prefix_course C, prefix_context X 
 *  WHERE A.userid=19952 and A.contextid=X.id and X.instanceid=C.id
 */
class OtroRolMySqlDAO implements OtroRolDAO {

    public function queryBuscaPersonaOtroRolLocal($username, $password) {
        $sql = 'SELECT id, nombre_rol, username, institucion, nombres, apellidos, campo_institucion, password 
            FROM otros_roles WHERE username = ? AND password = ?';

        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($username);
        $sqlQuery->setString($password);
        return $this->getRow($sqlQuery);
    }

    public function queryBuscaPersonaOtroRolPlataforma($campo) {
        $sql = 'SELECT id, nombre_rol, username, institucion, nombres, apellidos, campo_institucion, password 
            FROM otros_roles WHERE username = ? OR id = ?';

        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($campo);
        $sqlQuery->set($campo);
        return $this->getRow($sqlQuery);
    }

    public function queryBuscaPersonaOtroRolTodos() {
        $sql = 'SELECT id, nombre_rol, username, institucion, nombres, apellidos, campo_institucion, password 
            FROM otros_roles ORDER BY institucion';
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    //inserta persona con otro rol distinto a los de moodle, password encriptada en md5
    public function queryInsertOtroRol($nombre_rol, $username, $institucion, $nombres, $apellidos, $campo_institucion, $password) {
        $sql = 'INSERT INTO otros_roles (nombre_rol, username, institucion, nombres, apellidos, campo_institucion, password) VALUES (?,?,?,?,?,?,?)';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($nombre_rol);
        $sqlQuery->setString($username);
        $sqlQuery->setString($institucion);
        $sqlQuery->setString($nombres);
        $sqlQuery->setString($apellidos);
        $sqlQuery->setString($campo_institucion);
        $sqlQuery->setString(md5($password));
        $id = $this->executeInsert($sqlQuery);
        return $id;
    }

    public function queryUpdateOtroRol($nombre_rol, $username, $institucion, $nombres, $apellidos, $campo_institucion, $password, $id) {
        $sql = "";
        if ($this->isNotEmpty($password) > 0) {
            $sql .= 'UPDATE otros_roles SET nombre_rol=?, username=?, institucion=?, nombres=?, apellidos=?, campo_institucion=?, password=? WHERE id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setString($nombre_rol);
            $sqlQuery->setString($username);
            $sqlQuery->setString($institucion);
            $sqlQuery->setString($nombres);
            $sqlQuery->setString($apellidos);
            $sqlQuery->setString($campo_institucion);
            $sqlQuery->setString(md5($password));
            $sqlQuery->setNumber($id);
        } else {
            $sql .= 'UPDATE otros_roles SET nombre_rol=?, username=?, institucion=?, nombres=?, apellidos=?, campo_institucion=? WHERE id = ?';
            $sqlQuery = new SqlQuery($sql);
            $sqlQuery->setString($nombre_rol);
            $sqlQuery->setString($username);
            $sqlQuery->setString($institucion);
            $sqlQuery->setString($nombres);
            $sqlQuery->setString($apellidos);
            $sqlQuery->setString($campo_institucion);
            $sqlQuery->setNumber($id);
        }
        return $this->executeUpdate($sqlQuery);
    }

    public function queryDeleteOtroRol($id) {
        $sql = 'DELETE FROM otros_roles WHERE id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($id);
        return $this->executeUpdate($sqlQuery);
    }

    public function isNotEmpty($input) {
        $strTemp = $input;
        $strTemp = trim($strTemp);
        $retorno = 0;
        if (strlen($strTemp) > 0) { //Also tried this "if(strlen($strTemp) > 0)"
            $retorno = 1;
            ;
        }

        return $retorno;
    }

    protected function readRow($row) {
        $otroRol = new OtroRol();
        $otroRol->id = $row['id'];
        $otroRol->usuario = $row['username'];
        $otroRol->nombre = $row['nombres'];
        $otroRol->apellido = $row['apellidos'];
        $otroRol->institucion = $row['institucion'];
        $otroRol->rolMoodle = $row['nombre_rol'];
        $otroRol->campo_institucion = $row['campo_institucion'];
        $otroRol->password = $row['password'];
        return $otroRol;
    }

    protected function getList($sqlQuery) {
        //$pasa.= $sqlQuery;
        $tab = QueryExecutorLocal::execute($sqlQuery);
        $ret = array();
        for ($i = 0; $i < count($tab); $i++) {
            $otroRol = $this->readRow($tab[$i]);
            $ret[$i] = $otroRol;
        }
        return $ret;
    }

    /**
     * Get row
     *
     * @return PersonasMySql 
     */
    protected function getRow($sqlQuery) {
        $tab = QueryExecutorLocal::execute($sqlQuery);
        if (count($tab) == 0) {
            return null;
        }
        return $this->readRow($tab[0]);
    }

    /**
     * Execute sql query
     */
    protected function execute($sqlQuery) {
        return QueryExecutorLocal::execute($sqlQuery);
    }

    /**
     * Execute sql query
     */
    protected function executeUpdate($sqlQuery) {
        return QueryExecutorLocal::executeUpdate($sqlQuery);
    }

    /**
     * Query for one row and one column
     */
    protected function querySingleResult($sqlQuery) {
        return QueryExecutorLocal::queryForString($sqlQuery);
    }

    /**
     * Insert row to table
     */
    protected function executeInsert($sqlQuery) {
        return QueryExecutorLocal::executeInsert($sqlQuery);
    }

}

?>
