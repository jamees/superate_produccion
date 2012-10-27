<?php

Class indexController Extends baseController {

    public function index() {
        $links_alumnos = array(
            'enrutador/index?params=' . $this->encrypter->encode('platform=universidad&username=18577341'),
            'enrutador/index?params=' . $this->encrypter->encode('platform=universidad&username=18382763'),
            'enrutador/index?params=' . $this->encrypter->encode('platform=universidad&username=18476003'),
            'enrutador/index?params=' . $this->encrypter->encode('platform=universidad&username=18914263')
        );
        $links_admin = array(
            'enrutador/index?params=' . $this->encrypter->encode('platform=instituto&username=16289354'),
            'enrutador/index?params=' . $this->encrypter->encode('platform=universidad&username=16485769')
        );
        $links_profesores = array(
            'enrutador/index?params=' . $this->encrypter->encode('platform=universidad&username=13020531'),
            'enrutador/index?params=' . $this->encrypter->encode('platform=universidad&username=sebastian.calzadillas')
        );

        $links_directores = array(
            'enrutador/index?params=' . $this->encrypter->encode('platform=universidad&username=marcelo.visconti'),
            'enrutador/index?params=' . $this->encrypter->encode('platform=universidad&username=monitoreousuario')
        );
        
        $this->registry->template->links_alumnos = $links_alumnos;
        $this->registry->template->links_admin = $links_admin;
        $this->registry->template->links_profesores = $links_profesores;
        $this->registry->template->links_directores= $links_directores;
        $this->registry->template->show('index/inicio_2');
    }

}

?>
