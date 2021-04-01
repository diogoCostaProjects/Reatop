<?php

  require_once MODELS . '/Conexao/Conexao.class.php';

  class DepartamentosDao extends Conexao {

        public function __construct() {
            $this->Conecta();
            $this->tabela = "app_departamentos";
            $this->data_atual = date('Y-m-d H:i:s');
        }
       
        public function save($cod_empresa, $nome, $status) {
          
            $sql_cadastro = $this->mysqli->prepare("INSERT INTO `$this->tabela` (cod_empresa, nome, status, data) VALUES ('$cod_empresa', '$nome', '$status', '$this->data_atual')");
            $sql_cadastro->execute();
            
            $param['status'] = '01';
            $param['msg'] = 'Departamento adicionado';               
            
            return $param;
        }   

        public function update($id, $nome, $status) {
          
            $sql_cadastro = $this->mysqli->prepare("UPDATE `$this->tabela` SET nome='$nome', status='$status' WHERE id='$id'");
            $sql_cadastro->execute();
            
            $param['status'] = '01';
            $param['msg'] = 'Departamento atualizado';               
            
            return $param;
        }   

        public function delete($id) {
          
            $sql_cadastro = $this->mysqli->prepare("DELETE FROM `$this->tabela` WHERE id='$id'");
            $sql_cadastro->execute();
            
            $param['status'] = '01';
            $param['msg'] = 'Departamento removido';               
            
            return $param;
        }   

        public function deleteUnidadesDptos($id) {
          
            $sql_cadastro = $this->mysqli->prepare("DELETE FROM app_unidades_dep WHERE app_departamentos_id='$id'");
            $sql_cadastro->execute();
        }   

        public function listAll($cod_empresa){
            
            $sql_cadastro = $this->mysqli->prepare("SELECT id, nome, status FROM `$this->tabela` WHERE cod_empresa='$cod_empresa' ORDER BY nome");
            $sql_cadastro->execute();
            $sql_cadastro->bind_result($this->id, $this->nome, $this->status);  
            $sql_cadastro->store_result();
            $rows = $sql_cadastro->num_rows;

            if($rows == 0){
                $param['rows'] = $rows;
                $lista[] = $param;
            }
            else {
                while($row = $sql_cadastro->fetch()) {
                    $param['id'] = $this->id;
                    $param['nome'] = $this->nome;
                    $param['status'] = $this->status;
                    $param['rows'] = $rows;
                    $lista[] = $param;
                }
            }
            return $lista;
        }
}




 ?>
