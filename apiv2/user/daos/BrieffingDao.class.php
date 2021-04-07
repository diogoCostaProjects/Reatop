<?php

  require_once MODELS . '/Conexao/Conexao.class.php';
  

  class BrieffingDao extends Conexao {

        public function __construct() {
            $this->Conecta();
            $this->tabela = "app_briefieng";
            $this->tabela_residuos = "app_briefieng_residuos";
            $this->data_atual = date('Y-m-d H:i:s');            
        }
                

      public function save($id_planejamento, $id_local, $cod_empresa, $id_user, $data, $obs, $id_status) {
          
          $sql_cadastro = $this->mysqli->prepare("INSERT INTO `app_brifieng`(`app_planejamentos_id`, `id_local`, `cod_empresa`, `id_user`, `data`,`obs`, `app_brifieng_status_id`) VALUES ('$id_planejamento', '$id_local', '$cod_empresa', '$id_user', '$data', '$obs', '$id_status')");
          
          $sql_cadastro->execute();
          
          $this->id_cadastro = $sql_cadastro->insert_id;
                                      
          $param['status'] = '01';
          $param['id'] = $this->id_cadastro;
          $lista[] = $param;

          return $lista;
      }   

      public function saveResiduo($id_briefieng, $id_residuo_tipo, $kg, $sacos) {
        
        $sql_cadastro = $this->mysqli->prepare("INSERT INTO `app_brifieng_residuos`(`app_brifieng_id`, `app_residuos_tipos_id`, `kg`, `sacos`) VALUES ('$id_briefieng', '$id_residuo_tipo', '$kg', '$sacos')"
        );
        $sql_cadastro->execute();
        $novo_residuo = $sql_cadastro->insert_id;
        
        return  $novo_residuo;
      }   

      public function saveResiduoFotos($id_briefieng_residuo, $tipo, $url, $data) {
        
        $sql_cadastro = $this->mysqli->prepare("INSERT INTO `app_brifieng_fotos`(`app_brifieng_residuos_id`, `tipo`, `url`, `data`) VALUES ('$id_briefieng_residuo', '$tipo', '$url', '$data')"
        );
        $sql_cadastro->execute();
      }   
   
      public function delete($id) {
              
        $sql_cadastro = $this->mysqli->prepare("DELETE FROM app_brifieng WHERE id='$id'");
        $sql_cadastro->execute();
        
        $param['status'] = '01';
        $param['msg'] = 'Brieffing removido';               
        
        return $param;
      }   

      public function updateAssinatura($id_briefieng, $assinatura, $data) {
      
        $sql_cadastro = $this->mysqli->prepare("UPDATE app_brifieng SET assinatura='$assinatura', data='$data' WHERE id='$id_briefieng'");
        
        $sql_cadastro->execute();
        
        $param['status'] = '01';
        $param['msg'] = 'Assinatura enviada.';
        $lista[] = $param;
               
        return  $lista;
      }   

      public function updateConformidade($id_briefieng, $conformidade) {
      
        $sql_cadastro = $this->mysqli->prepare("UPDATE app_brifieng SET conformidade='$conformidade' WHERE id='$id_briefieng'");
        
        $sql_cadastro->execute();
        
        $param['status'] = '01';
        $param['msg'] = 'Resgistro de conformidade atualizado.';
        $lista[] = $param;
               
        return  $lista;
      }   

      public function updateStatus($id_briefieng, $status) {
      
        $sql_cadastro = $this->mysqli->prepare("UPDATE app_brifieng SET app_brifieng_status_id='$status' WHERE id='$id_briefieng'");
        
        $sql_cadastro->execute();
        
        $param['status'] = '01';
        $param['msg'] = 'Coleta finalizada.';
        $lista[] = $param;
               
        return  $lista;
      }   

      
      public function listAndamento($id_user) {
                              
        // para verificar se tem algum briffieng incompleto, antes de começar outro

        $sql = $this->mysqli->prepare("
        SELECT id
        FROM app_brifieng
        WHERE id_user='$id_user' and app_brifieng_status_id=1
        LIMIT 1"
        );
        $sql->execute();
        $sql->bind_result($this->id);
        $sql->store_result();
        $rows = $sql->num_rows;

        if($rows == 0) {
            $Param['rows'] = $rows;
            $lista[] = $Param;
        }
        else{
            while($row =  $sql->fetch()){
          
              $Param['id'] = $this->id;
              $Param['rows'] = $rows;
              $lista[] = $Param;
            }
        }
               
        return $lista;
    }     


    public function listAll($id_planejamento) {
                              
      // para verificar se tem algum briffieng incompleto, antes de começar outro

      $sql = $this->mysqli->prepare("
      SELECT b.id, l.nome, b.cod_empresa, u.nome, b.data, b.conformidade, s.nome
      FROM app_brifieng as b
      INNER JOIN app_locais as l on l.id = b.id_local
      INNER JOIN app_users as u on u.id = b.id_user
      INNER JOIN app_brifieng_status as s on s.id = b.app_brifieng_status_id
      WHERE app_planejamentos_id='$id_planejamento'
      ORDER BY b.id DESC
      "
      );
      $sql->execute();
      $sql->bind_result($this->id, $this->local, $this->cod_empresa, $this->user, $this->data, $this->conformidade, $this->status);
      $sql->store_result();
      $rows = $sql->num_rows;

      if($rows == 0) {
          $Param['rows'] = $rows;
          $lista[] = $Param;
      }
      else{
          while($row =  $sql->fetch()){
        
            $Param['id'] = $this->id;
            $Param['local'] = $this->local;
            $Param['cod_empresa'] = $this->cod_empresa;
            $Param['user'] = $this->user;
            $Param['data'] = dataBR($this->data).' - '.horaMin(horarioBR($this->data));
            $Param['conformidade'] = $this->conformidade==1 ? 'Sim' : 'Não';
            $Param['status'] = $this->status;
            $Param['rows'] = $rows;
            $lista[] = $Param;
          }
      }
             
      return $lista;
  }     

  public function listId($id) {
                              
    // para verificar se tem algum briffieng incompleto, antes de começar outro
    // echo "SELECT b.id, l.nome, b.cod_empresa, u.nome, b.data, b.conformidade, s.nome, b.obs, b.assinatura, b.app_planejamentos_id
    // FROM app_brifieng as b
    // INNER JOIN app_locais as l on l.id = b.id_local
    // INNER JOIN app_users as u on u.id = b.id_user
    // INNER JOIN app_brifieng_status as s on s.id = b.app_brifieng_status_id
    // WHERE id='$id'
    // ORDER BY b.id DESC"; exit;


    $sql = $this->mysqli->prepare("
    SELECT b.id, l.nome, b.cod_empresa, u.nome, b.data, b.conformidade, s.nome, b.obs, b.assinatura, b.app_planejamentos_id
    FROM app_brifieng as b
    INNER JOIN app_locais as l on l.id = b.id_local
    INNER JOIN app_users as u on u.id = b.id_user
    INNER JOIN app_brifieng_status as s on s.id = b.app_brifieng_status_id
    WHERE b.id='$id'
    ORDER BY b.id DESC
    "
    );
    $sql->execute();
    $sql->bind_result($this->id, $this->local, $this->cod_empresa, $this->user, $this->data, $this->conformidade, $this->status, $this->obs, $this->assinatura, $this->id_planejamento);
    $sql->store_result();
    $rows = $sql->num_rows;

    if($rows == 0) {
        $Param['rows'] = $rows;
        $lista[] = $Param;
    }
    else{
        while($row =  $sql->fetch()){
      
          $Param['id'] = $this->id;
          $Param['local'] = $this->local;
          $Param['cod_empresa'] = $this->cod_empresa;
          $Param['user'] = $this->user;
          $Param['data'] = dataBR($this->data).' - '.horaMin(horarioBR($this->data));
          $Param['conformidade'] = $this->conformidade==1 ? 'Sim' : 'Não';
          $Param['status'] = $this->status;
          $Param['obs'] = $this->obs;
          $Param['assinatura'] = $this->assinatura;
          $Param['id_planejamento'] = $this->id_planejamento;
          $Param['residuos'] = $this->listResiduosBrifieng($this->id);
          
          $lista[] = $Param;
        }
    }
           
    return $lista;
}     

  public function listResiduosBrifieng($id_brifieng) {
                                
    // para verificar se tem algum briffieng incompleto, antes de começar outro

    $sql = $this->mysqli->prepare("
    SELECT bt.id, t.nome, bt.kg, bt.sacos
    FROM app_brifieng_residuos as bt
    INNER JOIN app_residuos_tipos as t on t.id = bt.app_residuos_tipos_id
    WHERE bt.app_brifieng_id='$id_brifieng'
    ORDER BY bt.id DESC
    "
    );
    $sql->execute();
    $sql->bind_result($this->id_residuo, $this->nome_residuo, $this->kg_residuo, $this->sacos_residuo);
    $sql->store_result();
    $rows = $sql->num_rows;

    if($rows == 0) {
        $Param['rows'] = $rows;
        $lista[] = $Param;
    }
    else{
        while($row =  $sql->fetch()){
      
          $Param['id'] = $this->id_residuo;
          $Param['nome'] = $this->nome_residuo;
          $Param['kg'] = $this->kg_residuo;
          $Param['sacos'] = $this->sacos_residuo;
          $Param['fotos'] = $this->listFotosResiduo($this->id_residuo);          
          $lista[] = $Param;
        }
    }
          
    return $lista;
  }     

  public function listFotosResiduo($id_residuo) {
                      
    // echo " SELECT id, tipo, url, data
    // FROM app_brifieng_fotos
    // WHERE app_brifieng_residuos_id='$id_residuo'
    // ORDER BY id DESC"; exit;

    $sql = $this->mysqli->prepare("
    SELECT id, tipo, url, data
    FROM app_brifieng_fotos
    WHERE app_brifieng_residuos_id='$id_residuo'
    ORDER BY id DESC
    "
    );
    $sql->execute();
    $sql->bind_result($this->id_foto, $this->tipo_foto, $this->url_foto, $this->data_foto);
    $sql->store_result();
    $rows = $sql->num_rows;

    if($rows == 0) {
        $Param['rows'] = $rows;
        $lista[] = $Param;
    }
    else{
        while($row =  $sql->fetch()){
      
          $Param['id'] = $this->id_foto;
          $Param['tipo'] = $this->tipo_foto==1?'Antes':'Depois';
          $Param['url'] = $this->url_foto;
          $Param['data'] = dataBR($this->data_foto).' - '.horarioBR($this->data_foto);
          $Param['rows'] = $rows;          
          $lista[] = $Param;
        }
    }
          
    return $lista;
  }     

  //   public function update($id, $nome, $cep, $id_estado, $id_cidade, $endereco, $bairro, $numero, $complemento, $status) {
          
  //     $estados = new Estados();
  //     $estados->RetornaID($id_estado, $id_cidade);

  //     $sql_cadastro = $this->mysqli->prepare("UPDATE `$this->tabela` SET nome='$nome', cep='$cep', id_estado='$estados->id_estado', 
  //     id_cidade='$estados->id_cidade', endereco='$endereco', bairro='$bairro', numero='$numero', 
  //     complemento='$complemento', status='$status' 
  //     WHERE id='$id'"
  //     );
  //     $sql_cadastro->execute();
      
  //     $param['status'] = '01';
  //     $param['msg'] = 'Unidade atualizada';               
      
  //     return $param;
  // }   


  //   public function listId($id) {
                        
  //     $sql = $this->mysqli->prepare("
  //     SELECT id, nome, cep, id_estado, id_cidade, endereco, bairro, numero, complemento
  //     FROM `$this->tabela`
  //     WHERE id='$id'
  //     ORDER BY nome"
  //     );
  //     $sql->execute();
  //     $sql->bind_result($this->id, $this->nome, $this->cep, $this->id_estado, $this->id_cidade, $this->endereco, $this->bairro, $this->numero, $this->complemento);
  //     $sql->store_result();
  //     $rows = $sql->num_rows;

  //     if($rows == 0) {
  //         $Param['rows'] = $rows;
  //         $lista[] = $Param;
  //     }
  //     else{
  //         while($row =  $sql->fetch()){
        
  //           $estados = new Estados();
  //           $estados->RetornaNome($this->id_estado, $this->id_cidade);

  //           $Param['id'] = $this->id;
  //           $Param['nome'] = ucwords($this->nome);
  //           $Param['cep'] = $this->cep;
  //           $Param['estado'] = $estados->id_estado;
  //           $Param['cidade'] = $estados->id_cidade;
  //           $Param['endereco'] = $this->endereco;
  //           $Param['bairro'] = $this->bairro;
  //           $Param['numero'] = $this->numero;
  //           $Param['complemento'] = $this->complemento;              
  //           $Param['rows'] = $rows;
  //           $Param['departamentos'] = $this->listDepartamentosUnidade($this->id);  
  //           $lista[] = $Param;
  //         }
  //     }
             
  //     return $lista;
  // }     
      
  // public function listDepartamentosUnidade($id) {
          
      
  //         $sql = $this->mysqli->prepare("
  //         SELECT d.id, d.nome
  //         FROM app_departamentos as d 
  //         INNER JOIN app_Brieffing_dep as ud on ud.app_departamentos_id = d.id
  //         WHERE ud.app_Brieffing_id='$id'
  //         ORDER BY d.nome"
  //         );
  //         $sql->execute();
  //         $sql->bind_result($this->id_depto, $this->nome_depto);
  //         $sql->store_result();
  //         $rows = $sql->num_rows;

  //         if($rows == 0) {
  //             $Param['rows'] = $rows;
  //             $lista[] = $Param;
  //         }
  //         else{
  //             while($row =  $sql->fetch()){
                          
  //               $Param['id'] = $this->id_depto;
  //               $Param['nome'] = ucwords($this->nome_depto);
  //               $Param['rows'] = $rows;
  //               $lista[] = $Param;
  //             }
  //         }
                
  //         return $lista;
  //   }     

       

         
             
      }




 ?>
