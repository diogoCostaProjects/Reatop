<?php

  require_once MODELS . '/Conexao/Conexao.class.php';


  class LocalizacaoDao extends Conexao {

        public function __construct() {

            $this->Conecta();
            $this->tabela = "app_users_location";
            $this->data_atual = date('Y-m-d H:i:s');
            $this->pasta = '../../uploads/avatar';
        }

        public function add($id_user, $lat, $long) {

            $sql = $this->mysqli->prepare("INSERT INTO `$this->tabela`(app_users_id, latitude, longitude, data) VALUES ('$id_user', '$lat', '$long', '$this->data_atual')");
            $sql->execute();
        }
        
        public function update($id_user, $lat, $long) {

            $sql = $this->mysqli->prepare("UPDATE `$this->tabela` SET latitude = '$lat', longitude = '$long', data = '$this->data_atual' WHERE app_users_id = '$id_user'");
            $sql->execute();
        }
  }
 ?>
