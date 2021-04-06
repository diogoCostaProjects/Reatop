<?php

  require_once MODELS . '/Conexao/Conexao.class.php';
  

  class PlanejamentosDao extends Conexao {

        public function __construct() {
            $this->Conecta();
            $this->tabela = "app_planejamentos";
            // $this->tabela_locais = "app_Planejamentos_locais";
            $this->data_atual = date('Y-m-d H:i:s'); 
            $this->dia_atual =  date('Y-m-d');      
        }
                

      public function save($id_unidade, $id_rota, $cod_empresa, $id_de, $id_para, $data_de, $data_ate, $horario, $fixa, $data, $status) {
                  
          $sql_cadastro = $this->mysqli->prepare("
          INSERT INTO `app_planejamentos`(`app_unidades_id`, `app_rotas_id`, `cod_empresa`, `id_de`, `id_para`, `data_de`, `data_ate`, `horario`, `fixa`, `data`, `status`) VALUES ('$id_unidade', '$id_rota', '$cod_empresa', '$id_de', '$id_para', '$data_de', '$data_ate', '$horario', '$fixa', '$data', '$status')");
          
          $sql_cadastro->execute();
          
          $param['status'] = '01';
          $param['msg'] = 'Planejamento salvo.';               
          
          return $param;
      }  
      
    public function listAllGestor($cod_empresa, $data_de, $data_ate) {
        
        if($data_de != ""){
          $query_data = "and p.data between '$data_de' and '$data_ate'";
        }


        $sql = $this->mysqli->prepare("
        SELECT p.id, p.id_para, p.app_rotas_id, p.data, p.data_de, p.data_ate, p.horario, p.data, p.status, u.nome
        FROM `$this->tabela` as p
        INNER JOIN app_users as u on p.id_para = u.id
        WHERE p.cod_empresa='$cod_empresa' $query_data
        ORDER BY p.data DESC "
        );
        $sql->execute();
        $sql->bind_result($this->id, $this->id_para, $this->id_rota, $this->data, $this->data_de, $this->data_ate, $this->horario, $this->data, $this->status, $this->nome_para);
        $sql->store_result();
        $rows = $sql->num_rows;


        if($rows == 0) {
            $Param['rows'] = $rows;
            $lista[] = $Param;
        }
        else{
            while($row =  $sql->fetch()) {
                       
              $Param['id'] = $this->id;
              $Param['id_para'] = $this->id_para;
              $Param['nome_para'] = $this->nome_para;
              $Param['id_rota'] = $this->id_rota;
              $Param['data'] = dataBR($this->data);
              $Param['data_de'] = dataBR($this->data_de);
              $Param['data_ate'] = dataBR($this->data_ate);
              $Param['horario'] = horaMin($this->horario);
              $Param['status'] = $this->status==1 ? 'Em andamento' : 'Finalizado' ;
              $Param['rows'] = $rows;
              $lista[] = $Param;
            }
        }
               
        return $lista;
    }     


    public function listAllApp($id_user_app) {
              

      $sql = $this->mysqli->prepare("
      SELECT p.id, p.id_para, p.app_rotas_id, p.data, p.data_de, p.data_ate, p.horario, p.data, p.status, a.id, a.nome, l.id, l.nome, l.setor, l.predio_bloco, l.obs, u.id, u.nome, u.cep, u.id_estado, u.id_cidade, u.endereco, u.bairro, u.numero, u.complemento, r.nome
      FROM `$this->tabela` as p
      INNER JOIN app_rotas as r on r.id = p.app_rotas_id
      INNER JOIN app_rotas_locais as rl on rl.app_rotas_id = r.id
      INNER JOIN app_atividades as a on a.id = rl.app_atividades_id
      INNER JOIN app_locais as l on l.id = rl.app_locais_id
      INNER JOIN app_unidades as u on u.id = r.app_unidades_id
      WHERE p.id_para='$id_user_app' and (p.data_de <= '$this->dia_atual' and p.data_ate >= '$this->dia_atual') and p.fixa=2
      GROUP BY a.id
      ORDER BY p.id, p.data_de, p.horario"
      );
      $sql->execute();


      $sql->bind_result($this->id, $this->id_para, $this->id_rota, $this->data, $this->data_de, $this->data_ate, $this->horario, $this->data, $this->status, $this->id_atividade, $this->atividade, $this->id_local, $this->nome_local, $this->setor_local, $this->predio_local, $this->obs_local, $this->id_unidade, $this->nome_unidade, $this->cep_unidade, $this->estado_unidade, $this->cidade_unidade, $this->endereco_unidade, $this->bairro_unidade, $this->numero_unidade, $this->complemento_unidade, $this->nome_rota);
      $sql->store_result();
      $rows = $sql->num_rows;


      if($rows == 0) {
          $Param['rows'] = $rows;
          $lista[] = $Param;
      }
      else{
          while($row =  $sql->fetch()) {

            $estados = new Estados();
            $estados->RetornaNome($this->estado_unidade, $this->cidade_unidade);

            $Param['nome_unidade'] = $this->nome_unidade;
            $Param['nome_local'] = $this->nome_local;
            $Param['nome_rota'] = $this->nome_rota;
            $Param['nome_atividade'] = $this->atividade;
            $Param['data_de'] = dataBR($this->data_de);
            $Param['data_ate'] = dataBR($this->data_ate);
            $Param['horario'] = horaMin($this->horario);
            $Param['detalhes']['id_planejamento'] = $this->id;
            $Param['detalhes']['id_rota'] = $this->id_rota;
            $Param['detalhes']['id_unidade'] = $this->id_unidade;
            $Param['detalhes']['id_local'] = $this->id_local;
            $Param['detalhes']['id_atividade'] = $this->id_atividade;
            $Param['detalhes']['cep_unidade'] = $this->cep_unidade;
            $Param['detalhes']['cidade_unidade'] = $estados->id_cidade;
            $Param['detalhes']['estado_unidade'] = $estados->id_estado;
            $Param['detalhes']['endereco_unidade'] = $this->endereco_unidade;
            $Param['detalhes']['numero_unidade'] = $this->numero_unidade;
            $Param['detalhes']['bairro_unidade'] = $this->bairro_unidade;
            $Param['detalhes']['complemento_unidade'] = $this->complemento_unidade;
            $Param['detalhes']['predio_local'] = $this->predio_local;
            $Param['detalhes']['setor_local'] = $this->setor_local;
            $Param['detalhes']['obs_local'] = $this->obs_local;
            $Param['detalhes']['data'] = dataBR($this->data);
            $Param['detalhes']['status'] = $this->status==1 ? 'Em aberto' : 'Finalizado';
            $Param['rows'] = $rows;
            $lista[] = $Param;
          }
      }
             
      return $lista;
  }     


  public function listFixasApp($id_user_app) {
              

    $sql = $this->mysqli->prepare("
    SELECT p.id, p.id_para, p.app_rotas_id, p.data, p.data_de, p.data_ate, p.horario, p.data, p.status, a.id, a.nome, l.id, l.nome, l.setor, l.predio_bloco, l.obs, u.id, u.nome, u.cep, u.id_estado, u.id_cidade, u.endereco, u.bairro, u.numero, u.complemento, r.nome
    FROM `$this->tabela` as p
    INNER JOIN app_rotas as r on r.id = p.app_rotas_id
    INNER JOIN app_rotas_locais as rl on rl.app_rotas_id = r.id
    INNER JOIN app_atividades as a on a.id = rl.app_atividades_id
    INNER JOIN app_locais as l on l.id = rl.app_locais_id
    INNER JOIN app_unidades as u on u.id = r.app_unidades_id
    WHERE p.id_para='$id_user_app' and (p.data_de <= '$this->dia_atual' and p.data_ate >= '$this->dia_atual') and p.fixa=1
    GROUP BY a.id
    ORDER BY p.id, p.data_de, p.horario"
    );
    $sql->execute();


    $sql->bind_result($this->id, $this->id_para, $this->id_rota, $this->data, $this->data_de, $this->data_ate, $this->horario, $this->data, $this->status, $this->id_atividade, $this->atividade, $this->id_local, $this->nome_local, $this->setor_local, $this->predio_local, $this->obs_local, $this->id_unidade, $this->nome_unidade, $this->cep_unidade, $this->estado_unidade, $this->cidade_unidade, $this->endereco_unidade, $this->bairro_unidade, $this->numero_unidade, $this->complemento_unidade, $this->nome_rota);
    $sql->store_result();
    $rows = $sql->num_rows;


    if($rows == 0) {
        $Param['rows'] = $rows;
        $lista[] = $Param;
    }
    else{
        while($row =  $sql->fetch()) {

          $estados = new Estados();
          $estados->RetornaNome($this->estado_unidade, $this->cidade_unidade);
                   
          $Param['nome_unidade'] = $this->nome_unidade;
          $Param['nome_local'] = $this->nome_local;
          $Param['nome_rota'] = $this->nome_rota;
          $Param['nome_atividade'] = $this->atividade;
          $Param['data_de'] = dataBR($this->data_de);
          $Param['data_ate'] = dataBR($this->data_ate);
          $Param['horario'] = horaMin($this->horario);
          $Param['detalhes']['id_planejamento'] = $this->id;
          $Param['detalhes']['id_rota'] = $this->id_rota;
          $Param['detalhes']['id_unidade'] = $this->id_unidade;
          $Param['detalhes']['id_local'] = $this->id_local;
          $Param['detalhes']['id_atividade'] = $this->id_atividade;
          $Param['detalhes']['cep_unidade'] = $this->cep_unidade;
          $Param['detalhes']['cidade_unidade'] = $estados->id_cidade;
          $Param['detalhes']['estado_unidade'] = $estados->id_estado;
          $Param['detalhes']['endereco_unidade'] = $this->endereco_unidade;
          $Param['detalhes']['numero_unidade'] = $this->numero_unidade;
          $Param['detalhes']['bairro_unidade'] = $this->bairro_unidade;
          $Param['detalhes']['complemento_unidade'] = $this->complemento_unidade;
          $Param['detalhes']['predio_local'] = $this->predio_local;
          $Param['detalhes']['setor_local'] = $this->setor_local;
          $Param['detalhes']['obs_local'] = $this->obs_local;
          $Param['detalhes']['data'] = dataBR($this->data);
          $Param['detalhes']['status'] = $this->status==1 ? 'Em aberto' : 'Finalizado';
          $Param['rows'] = $rows;
          $lista[] = $Param;
        }
    }
           
    return $lista;
}     

  //     public function savePlanejamentosLocais($id_rota, $id_local, $id_atividade) {
        
  //         $sql_cadastro = $this->mysqli->prepare("INSERT INTO `$this->tabela_locais` (app_Planejamentos_id, app_locais_id, app_atividades_id, data) VALUES ('$id_rota', '$id_local', '$id_atividade', '$this->data_atual')");
  //         $sql_cadastro->execute();
  //     }   


  //     public function update($id, $nome, $cep, $id_estado, $id_cidade, $endereco, $bairro, $numero, $complemento, $status) {
          
  //       $estados = new Estados();
  //       $estados->RetornaID($id_estado, $id_cidade);

  //       $sql_cadastro = $this->mysqli->prepare("UPDATE `$this->tabela` SET nome='$nome', cep='$cep', id_estado='$estados->id_estado', 
  //       id_cidade='$estados->id_cidade', endereco='$endereco', bairro='$bairro', numero='$numero', 
  //       complemento='$complemento', status='$status' 
  //       WHERE id='$id'"
  //       );
  //       $sql_cadastro->execute();
        
  //       $param['status'] = '01';
  //       $param['msg'] = 'Unidade atualizada';               
        
  //       return $param;
  //   }   

  //   public function deletePlanejamentosDptos($id) {

  //       $sql_cadastro = $this->mysqli->prepare("DELETE FROM app_Planejamentos_dep WHERE app_Planejamentos_id='$id'");
  //       $sql_cadastro->execute();
  //   }   

  //   public function delete($id) {
      
  //       $sql_cadastro = $this->mysqli->prepare("DELETE FROM `$this->tabela` WHERE id='$id'");
  //       $sql_cadastro->execute();
        
  //       $param['status'] = '01';
  //       $param['msg'] = 'Unidade removida';               
        
  //       return $param;
  //   }   

      
  

 


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
  //         INNER JOIN app_Planejamentos_dep as ud on ud.app_departamentos_id = d.id
  //         WHERE ud.app_Planejamentos_id='$id'
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
