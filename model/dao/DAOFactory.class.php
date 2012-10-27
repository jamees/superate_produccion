<?php

/**
 * DAOFactory
 * @author: http://phpdao.com
 * @date: ${date}
 */
class DAOFactory {

    /**
     * @return PersonasDAO
     */
    public static function getPersonasDAO() {
        return new PersonasMySqlDAO();
    }

    public static function getCursosDAO() {
        return new CursosMySqlDAO();
    }

    public static function getQuizDAO() {
        return new QuizMySqlDAO();
    }

    public static function getGruposDAO() {
        return new GruposMySqlDAO();
    }

    public static function getRankingDAO() {
        return new RankingMySqlDAO();
    }

    public static function getIntentosDAO() {
        return new IntentosMySqlDAO();
    }

    public static function getPreguntasDAO() {
        return new PreguntasMySqlDAO();
    }

    public static function getMatrizDAO() {
        return new MatrizMySqlDAO();
    }

    public static function getOtroRolDAO() {
        return new OtroRolMySqlDAO();
    }
    
    public static function getAdopcionDAO() {
        return new AdopcionMySqlDAO();
    }    

    public static function getMantenedorDAO() {
        return new MantenedorMySqlDAO();
    }

    public static function getLogDAO() {
        return new LogMySqlDAO();
    }

}

?>
