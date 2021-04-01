<?php

  require_once MODELS . '/Conexao/Conexao.class.php';
  

  class UnidadesDao extends Conexao {

        public function __construct() {
            $this->Conecta();
            $this->tabela = "app_unidades";
            $this->tabela_dep = "app_unidades_dep";
            $this->data_atual = date('Y-m-d H:i:s');             
        }
                

      public function save($cod_empresa, $nome, $cep, $id_estado, $id_cidade, $endereco, $bairro, $numero, $complemento, $status) {
          
          $sql_cadastro = $this->mysqli->prepare("INSERT INTO `$this->tabela` (cod_empresa, nome, cep, id_estado, id_cidade, endereco, bairro, numero, complemento, status, data) VALUES ('$cod_empresa', '$nome', '$cep', '$id_estado', '$id_cidade', '$endereco', '$bairro', '$numero', '$complemento', '$status', '$this->data_atual')");
          $sql_cadastro->execute();
          
          $this->id_cadastro = $sql_cadastro->insert_id;
                                      
          return $this->id_cadastro;
      }   

      public function saveDeptoUnidade($id_unidade, $id_dep) {
        $sql_cadastro = $this->mysqli->prepare("INSERT INTO `$this->tabela_dep` (app_unidades_id, app_departamentos_id, data) VALUES ('$id_unidade', '$id_dep', '$this->data_atual')");
        $sql_cadastro->execute();
      }   


      public function update($id, $nome, $cep, $id_estado, $id_cidade, $endereco, $bairro, $numero, $complemento, $status) {
          
        $estados = new Estados();
        $estados->RetornaID($id_estado, $id_cidade);

        $sql_cadastro = $this->mysqli->prepare("UPDATE `$this->tabela` SET nome='$nome', cep='$cep', id_estado='$estados->id_estado', 
        id_cidade='$estados->id_cidade', endereco='$endereco', bairro='$bairro', numero='$numero', 
        complemento='$complemento', status='$status' 
        WHERE id='$id'"
        );
        $sql_cadastro->execute();
        
        $param['status'] = '01';
        $param['msg'] = 'Unidade atualizada';               
        
        return $param;
    }   

    public function deleteUnidadesDptos($id) {

        $sql_cadastro = $this->mysqli->prepare("DELETE FROM app_unidades_dep WHERE app_unidades_id='$id'");
        $sql_cadastro->execute();
    }   

    public function delete($id) {
      
        $sql_cadastro = $this->mysqli->prepare("DELETE FROM `$this->tabela` WHERE id='$id'");
        $sql_cadastro->execute();
        
        $param['status'] = '01';
        $param['msg'] = 'Unidade removida';               
        
        return $param;
    }   

      
      public function listAll($cod_empresa) {
                        
        $sql = $this->mysqli->prepare("
        SELECT id, nome, cep, id_estado, id_cidade, endereco, bairro, numero, complemento
        FROM `$this->tabela`
        WHERE cod_empresa='$cod_empresa'
        ORDER BY nome"
        );
        $sql->execute();
        $sql->bind_result($this->id, $this->nome, $this->cep, $this->id_estado, $this->id_cidade, $this->endereco, $this->bairro, $this->numero, $this->complemento);
        $sql->store_result();
        $rows = $sql->num_rows;

        if($rows == 0) {
            $Param['rows'] = $rows;
            $lista[] = $Param;
        }
        else{
            while($row =  $sql->fetch()){
          
              $estados = new Estados();
              $estados->RetornaNome($this->id_estado, $this->id_cidade);

              $Param['id'] = $this->id;
              $Param['nome'] = ucwords($this->nome);
              $Param['cep'] = $this->cep;
              $Param['estado'] = $estados->id_estado;
              $Param['cidade'] = $estados->id_cidade;
              $Param['endereco'] = $this->endereco;
              $Param['bairro'] = $this->bairro;
              $Param['numero'] = $this->numero;
              $Param['complemento'] = $this->complemento;              
              $Param['rows'] = $rows;
              $lista[] = $Param;
            }
        }
               
        return $lista;
    }     
      
     

       

        public function listId($id) {
                        
            $sql = $this->mysqli->prepare(" 
            SELECT id, nome, data_de, data_ate, obs
            FROM `$this->tabela`
            WHERE id='$id'"
            );
            $sql->execute();
            $sql->bind_result($this->id, $this->nome, $this->data_de, $this->data_ate, $this->obs);
            $sql->fetch();
            $sql->close();
            
            $Param['id'] = $this->id;
            $Param['nome'] = ucwords($this->nome);
            $Param['data_de'] = dataBR($this->data_de);
            $Param['data_ate'] =  dataBR($this->data_ate);
            $Param['days'] = $this->listDays($this->id);
            
            $lista[] = $Param;
           
            return $Param;
        }     
             
      }




 ?>
