<?php

  require_once MODELS . '/Conexao/Conexao.class.php';
  

  class BrieffingDao extends Conexao {

        public function __construct() {
            $this->Conecta();
            $this->tabela = "app_briefieng";
            $this->tabela_residuos = "app_briefieng_residuos";
            $this->data_atual = date('Y-m-d H:i:s');            
        }
                

      public function save($id_planejamento, $id_local, $cod_empresa, $id_user, $data, $assinatura, $obs, $conformidade, $id_status) {
          
          $sql_cadastro = $this->mysqli->prepare("INSERT INTO `app_brifieng`(`app_planejamentos_id`, `id_local`, `cod_empresa`, `id_user`, `data`, `assinatura`, `obs`, `conformidade`, `app_brifieng_status_id`) VALUES ('$id_planejamento', '$id_local', '$cod_empresa', '$id_user', '$data', '$assinatura', '$obs', '$conformidade', '$id_status')");
          
          $sql_cadastro->execute();
          
          $this->id_cadastro = $sql_cadastro->insert_id;
                                      
          return $this->id_cadastro;
      }   

      public function saveResiduos($id_briefieng, $id_residuo_tipo, $kg, $sacos) {
        
        $sql_cadastro = $this->mysqli->prepare("INSERT INTO `app_brifieng_residuos`(`app_brifieng_id`, `app_residuos_tipos_id`, `kg`, `sacos`) VALUES ('$id_briefieng', '$id_residuo_tipo', '$kg', '$sacos')"
        );
        $sql_cadastro->execute();
      }   

      public function saveResiduosFotos($id_briefieng_residuo, $tipo, $url, $data) {
        
        $sql_cadastro = $this->mysqli->prepare("INSERT INTO `app_brifieng_fotos`(`app_brifieng_residuos_id`, `tipo`, `url`, `data`) VALUES ('$id_briefieng_residuo', '$tipo', '$url', '$data')"
        );
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

    public function deleteBrieffingDptos($id) {

        $sql_cadastro = $this->mysqli->prepare("DELETE FROM app_Brieffing_dep WHERE app_Brieffing_id='$id'");
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
          INNER JOIN app_Brieffing_dep as ud on ud.app_departamentos_id = d.id
          WHERE ud.app_Brieffing_id='$id'
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
