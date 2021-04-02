<?php

  require_once MODELS . '/Conexao/Conexao.class.php';
  

  class RotasDao extends Conexao {

        public function __construct() {
            $this->Conecta();
            $this->tabela = "app_rotas";
            $this->tabela_locais = "app_rotas_locais";
            $this->data_atual = date('Y-m-d H:i:s');            
        }
                

      public function save($cod_empresa, $id_unidade, $id_user, $nome, $data, $status) {
          // echo"INSERT INTO `$this->tabela` (cod_empresa, id_unidade, id_user, nome, data, status) VALUES ('$cod_empresa', '$id_unidade', '$id_user', '$nome', '$data', '$status')"; exit;
          $sql_cadastro = $this->mysqli->prepare("INSERT INTO `$this->tabela` (cod_empresa, app_unidades_id, id_user, nome, data, status) VALUES ('$cod_empresa', '$id_unidade', '$id_user', '$nome', '$data', '$status')");
          $sql_cadastro->execute();
          
          $this->id_cadastro = $sql_cadastro->insert_id;
                                      
          return $this->id_cadastro;
      }   

      public function saveRotasLocais($id_rota, $id_local, $id_atividade) {
        
          $sql_cadastro = $this->mysqli->prepare("INSERT INTO `$this->tabela_locais` (app_rotas_id, app_locais_id, app_atividades_id, data) VALUES ('$id_rota', '$id_local', '$id_atividade', '$this->data_atual')");
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

    public function deleteRotasDptos($id) {

        $sql_cadastro = $this->mysqli->prepare("DELETE FROM app_Rotas_dep WHERE app_Rotas_id='$id'");
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
      SELECT id, nome, cep, id_estado, id_cidade, endereco, bairro, numero, complemento
      FROM `$this->tabela`
      WHERE id='$id'
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
            $Param['departamentos'] = $this->listDepartamentosUnidade($this->id);  
            $lista[] = $Param;
          }
      }
             
      return $lista;
  }     
      
  public function listDepartamentosUnidade($id) {
          
      
          $sql = $this->mysqli->prepare("
          SELECT d.id, d.nome
          FROM app_departamentos as d 
          INNER JOIN app_Rotas_dep as ud on ud.app_departamentos_id = d.id
          WHERE ud.app_Rotas_id='$id'
          ORDER BY d.nome"
          );
          $sql->execute();
          $sql->bind_result($this->id_depto, $this->nome_depto);
          $sql->store_result();
          $rows = $sql->num_rows;

          if($rows == 0) {
              $Param['rows'] = $rows;
              $lista[] = $Param;
          }
          else{
              while($row =  $sql->fetch()){
                          
                $Param['id'] = $this->id_depto;
                $Param['nome'] = ucwords($this->nome_depto);
                $Param['rows'] = $rows;
                $lista[] = $Param;
              }
          }
                
          return $lista;
    }     

       

         
             
      }




 ?>
