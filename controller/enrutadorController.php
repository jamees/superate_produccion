<?php

Class enrutadorController Extends baseController {

    public function index() {
//        session_start();
//        session_destroy();
        $PARAMS = $this->encrypter->decodeURL($_GET['params']);

        // redireccionamos al 404 si no viene los datos esperados
        if (!isset($PARAMS['username'])) {
            $this->registry->template->blog_heading =
                    "Ha ocurrido con error que impide verificar tu cuenta. </br>
			Por favor informa este error a reportes@galyleo.net";
            $this->registry->template->show('error404');
            return;
        } else {
            $username = $PARAMS['username'];
            $otroRol = DAOFactory::getOtroRolDAO()->queryBuscaPersonaOtroRolPlataforma($username);
            if (isset($otroRol) && ($otroRol->rolMoodle == "director")) {
                session_start();
                $_SESSION['usuario_director'] = $otroRol;
                session_commit();
                $this->registry->template->ruta = "director/index";
                $this->registry->template->show('enrutador');
            } else {
                if (isset($otroRol) && ($otroRol->rolMoodle == "admin")) {
                    session_start();
                    $_SESSION['usuario_admin'] = $otroRol;
                    session_commit();
                    $this->registry->template->ruta = "admin/index_admin";
                    $this->registry->template->show('enrutador');
                } else {

                    if (isset($otroRol) && ($otroRol->rolMoodle == "monitoreo")) {
                        session_start();
                        $_SESSION['usuario_monitoreo'] = $otroRol;
                        session_commit();
                        $this->registry->template->ruta = "monitoreo/index_monitoreo";
                        $this->registry->template->show('enrutador');
                    } else {


                        /////
                        $usuario = DAOFactory::getPersonasDAO()->queryBuscaPersona($username);
                        if (isset($usuario)) {
                            if (!isset($usuario->rolMoodle)) {
                                $this->registry->template->blog_heading =
                                        "No se encontro su rol en la plataforma";
                                $this->registry->template->show('error404');
                                return;
                            }
                            if ((int) $usuario->rolMoodle == 5) {
                                session_start();
                                $_SESSION['usuario'] = $usuario;
                                session_commit();
                                $this->registry->template->ruta = "alumno/index";
                                $this->registry->template->show('enrutador');
                            } else {
                                if ((int) $usuario->rolMoodle == 4) {
                                    session_start();
                                    $_SESSION['usuario_profesor'] = $usuario;
                                    session_commit();
                                    $this->registry->template->ruta = "profesor/index";
                                    $this->registry->template->show('enrutador');
                                } else {
                                    //usuario con rol diferente en moodle (admin, gestor, etc)
                                    $this->registry->template->blog_heading =
                                            "No se encontraron sus registros en la plataforma";
                                    $this->registry->template->show('error404');
                                }
                            }
                        } else {
                            //Si el usuario no esta dentro de los registros 
                            $this->registry->template->blog_heading =
                                    "No se encontraron sus registros en la plataforma";
                            $this->registry->template->show('error404');
                        }
                    }
                    /////
                }
            }
        }
    }

}

?>