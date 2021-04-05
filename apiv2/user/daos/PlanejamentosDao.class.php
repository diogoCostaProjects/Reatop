<?php

  require_once MODELS . '/Conexao/Conexao.class.php';
  

  class PlanejamentosDao extends Conexao {

        public function __construct() {
            $this->Conecta();
            $this->tabela = "app_planejamentos";
            // $this->tabela_locais = "app_Planejamentos_locais";
            $this->data_atual = date('Y-m-d H:i:s');            
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
