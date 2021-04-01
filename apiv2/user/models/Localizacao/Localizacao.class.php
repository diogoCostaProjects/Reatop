<?php

// require_once MODELS . '/Conexao/Conexao.class.php';
require_once MODELS . '/Secure/Secure.class.php';
require_once MODELS . '/ResizeFiles/ResizeFiles.class.php';
require_once MODELS . '/Emails/Emails.class.php';
require_once MODELS . '/Estados/Estados.class.php';

require_once DAOS . '/LocalizacaoDao.class.php';


class Localizacao  {

    public function __construct() {

        $this->dao = new LocalizacaoDao();
    }

    public function add($id_user, $lat, $long) {

            $this->dao->add($id_user, $lat, $long);
    }

    public function update($id_user, $lat, $long) {

          $this->dao->update($id_user, $lat, $long);
    }

}
