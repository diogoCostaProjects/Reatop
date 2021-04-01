<?php

  require_once MODELS . '/Conexao/Conexao.class.php';
  

  class LocaisDao extends Conexao {

        public function __construct() {
            $this->Conecta();
            $this->tabela = "app_locais";
            $this->tabela_contatos = "app_locais_resp";
            $this->data_atual = date('Y-m-d H:i:s');       
        }
                

      public function save($cod_empresa, $id_unidade, $nome, $setor, $predio_bloco, $obs, $qrcode, $status) {
          
          $sql_cadastro = $this->mysqli->prepare("INSERT INTO `$this->tabela` (cod_empresa, app_unidades_id, nome, setor, predio_bloco, obs, data_cadastro, qrcode, status) VALUES ('$cod_empresa', '$id_unidade', '$nome', '$setor', '$predio_bloco', '$obs', '$this->data_atual', '$qrcode', '$status')");
          $sql_cadastro->execute();
          $novo_local = $sql_cadastro->insert_id;                  
          
          $param['status'] = '01';
          $param['id'] = $novo_local;
          $param['msg'] = 'Local adicionado';

          return $param;
      }   

      public function saveContatosLocal($id_local, $nome, $email, $resp, $data, $status) {
              
        $sql_cadastro = $this->mysqli->prepare("INSERT INTO `$this->tabela_contatos` (app_locais_id, nome, email, responsavel, data, status) VALUES ('$id_local', '$nome', '$email', '$resp', '$data', '$status')");
        $sql_cadastro->execute();
      }   


      public function update($id, $id_unidade, $nome, $setor, $predio_bloco, $obs, $status) {
        
        $sql_cadastro = $this->mysqli->prepare("UPDATE `$this->tabela` SET app_unidades_id='$id_unidade', nome='$nome', setor='$setor', predio_bloco='$predio_bloco', obs='$obs', status='$status' 
        WHERE id='$id'"
        );
        $sql_cadastro->execute();
        
        $param['status'] = '01';
        $param['msg'] = 'Local atualizado';               
        
        return $param;
    }   

    public function deleteContatosLocal($id) {

        $sql_cadastro = $this->mysqli->prepare("DELETE FROM app_locais_resp WHERE app_locais_id='$id'");
        $sql_cadastro->execute();
    }   

    public function delete($id) {
      
        $sql_cadastro = $this->mysqli->prepare("DELETE FROM `$this->tabela` WHERE id='$id'");
        $sql_cadastro->execute();
        
        $param['status'] = '01';
        $param['msg'] = 'Local removido';               
        
        return $param;
    }   

      
      public function listAll($id_unidade) {
                        
        $sql = $this->mysqli->prepare("
        SELECT id, nome, setor, predio_bloco, obs, data_cadastro, qrcode, status
        FROM `$this->tabela`
        WHERE app_unidades_id='$id_unidade'
        ORDER BY nome"
        );
        $sql->execute();
        $sql->bind_result($this->id, $this->nome, $this->setor, $this->predio_bloco, $this->obs, $this->data_cadastro, $this->qrcode, $this->status);
        $sql->store_result();
        $rows = $sql->num_rows;

        if($rows == 0) {
            $Param['rows'] = $rows;
            $lista[] = $Param;
        }
        else{
            while($row =  $sql->fetch()){
                        
              $Param['id'] = $this->id;
              $Param['nome'] = ucwords($this->nome);
              $Param['setor'] = $this->setor;
              $Param['predio_bloco'] = $this->predio_bloco;
              $Param['obs'] = $this->obs;
              $Param['data_cadastro'] = dataBR($this->data_cadastro);
              $Param['status'] = $this->status;              
              $Param['rows'] = $rows;
              $lista[] = $Param;
            }
        }
               
        return $lista;
    }     
      


    public function listId($id) {
                        
      $sql = $this->mysqli->prepare("
      SELECT id, nome, setor, predio_bloco, obs, data_cadastro, qrcode, status
      FROM `$this->tabela`
      WHERE id='$id'
      ORDER BY nome"
      );
      $sql->execute();
      $sql->bind_result($this->id, $this->nome, $this->setor, $this->predio_bloco, $this->obs, $this->data_cadastro, $this->qrcode, $this->status);
      $sql->store_result();
      $rows = $sql->num_rows;

      if($rows == 0) {
          $Param['rows'] = $rows;
          $lista[] = $Param;
      }
      else{
          while($row =  $sql->fetch()){
                      
            $Param['id'] = $this->id;
            $Param['nome'] = ucwords($this->nome);
            $Param['setor'] = $this->setor;
            $Param['predio_bloco'] = $this->predio_bloco;
            $Param['obs'] = $this->obs;
            $Param['data_cadastro'] = dataBR($this->data_cadastro);
            $Param['status'] = $this->status;              
            $Param['rows'] = $rows;
            $Param['contatos'] = $this->listContatos($this->id);      
            $lista[] = $Param;
          }
      }
             
      return $lista;
  }     
     

  public function listContatos($id) {
                        
    $sql = $this->mysqli->prepare("
    SELECT id, nome, email, responsavel, status
    FROM `$this->tabela_contatos`
    WHERE app_locais_id='$id'
    ORDER BY nome"
    );
    $sql->execute();
    $sql->bind_result($this->id_c, $this->nome_c, $this->email_c, $this->responsavel_c, $this->status_c);
    $sql->store_result();
    $rows = $sql->num_rows;

    if($rows == 0) {
        $Param['rows'] = $rows;
        $lista[] = $Param;
    }
    else{
        while($row =  $sql->fetch()){
                    
          $Param['id'] = $this->id_c;
          $Param['nome'] = ucwords($this->nome_c);
          $Param['email'] = $this->email;
          $Param['responsavel'] = $this->responsavel_c;
          $Param['status'] = $this->status_c;
          $Param['rows'] = $rows;
          $lista[] = $Param;
        }
    }
           
    return $lista;
}     
       

         
             
  }




 ?>
