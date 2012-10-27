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
class MantenedorMySqlDAO implements MantenedorDAO {


    
    public function queryBuscaImagenInstitucion($institucion) {
        $sql = 'SELECT idinstitucion,nombre,plataforma,imagen,campo_institucion
                FROM institucion
                WHERE nombre = ?';

        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($institucion);
        return $this->getRow($sqlQuery);
    }  

public function queryInsertInstitucion($nombre,$plataforma,$imagen,$campo_institucion) {
        $sql = 'INSERT INTO institucion (nombre,plataforma,imagen,campo_institucion) VALUES (?,?,?,?)';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($nombre);
        $sqlQuery->setString($plataforma);
        $sqlQuery->setString($imagen);
        $sqlQuery->setString($campo_institucion);
        $id = $this->executeInsert($sqlQuery);
        return $id;
    }
    
    public function queryUpdateInstitucion($nombre,$plataforma,$imagen,$campo_institucion,$institucion)  {
        $sql = 'UPDATE institucion SET nombre=?,plataforma=?,imagen=?,campo_institucion=? WHERE idinstitucion = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($nombre);
        $sqlQuery->setString($plataforma);
        $sqlQuery->setString($imagen);
        $sqlQuery->setString($campo_institucion);     
        $sqlQuery->setString($institucion);
        return $this->executeUpdate($sqlQuery);
    }    
    
    protected function readRow($row) {
        $mantenedor = new Mantenedor();
        $mantenedor->idinstitucion = $row['idinstitucion'];
        $mantenedor->nombre_institucion = $row['nombre'];
        $mantenedor->plataforma = $row['plataforma'];
        $mantenedor->imagen = $row['imagen'];
        $mantenedor->campo_institucion = $row['campo_institucion'];
        return $mantenedor;
    }

    protected function getList($sqlQuery) {
        //$pasa.= $sqlQuery;
        $tab = QueryExecutorLocal::execute($sqlQuery);
        $ret = array();
        for ($i = 0; $i < count($tab); $i++) {
            $mantenedor = $this->readRow($tab[$i]);
            $ret[$i] = $mantenedor;
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
