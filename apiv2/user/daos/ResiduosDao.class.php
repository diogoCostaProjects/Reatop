<?php

  require_once MODELS . '/Conexao/Conexao.class.php';
  

  class ResiduosDao extends Conexao {

      public function __construct() {
          $this->Conecta();
          $this->tabela = "app_residuos_tipos";
          $this->tabela_grupos = "app_residuos_grupos";
          $this->tabela_subgrupos = "app_residuos_subgrupos";
          $this->data_atual = date('Y-m-d H:i:s');            
      }
                  

      public function saveGrupo($cod_empresa, $nome, $data, $status) {
          
        $sql_cadastro = $this->mysqli->prepare("INSERT INTO `app_residuos_grupos`(`cod_empresa`, `nome`, `data`, `status`) VALUES ('$cod_empresa', '$nome', '$data', '$status')");
        $sql_cadastro->execute();
        
        $this->id_cadastro = $sql_cadastro->insert_id;
                                    
        $param['status'] = '01';
        $param['msg'] = 'Grupo de resíduos adicionado';
        $param['id'] = $this->id_cadastro;
        $lista[] = $param;

        return $lista;
      }   

      public function listGrupos($cod_empresa) {
        
        $sql = $this->mysqli->prepare("
        SELECT id, cod_empresa, nome, status
        FROM app_residuos_grupos
        WHERE cod_empresa='$cod_empresa'
        ORDER BY nome
        "
        );
        $sql->execute();
        $sql->bind_result($this->id, $this->cod_empresa, $this->nome, $this->status);
        $sql->store_result();
        $rows = $sql->num_rows;

        if($rows == 0) {
            $Param['rows'] = $rows;
            $lista[] = $Param;
        }
        else{
            while($row =  $sql->fetch()){
          
              $Param['id'] = $this->id;
              $Param['cod_empresa'] = $this->cod_empresa;
              $Param['nome'] = $this->nome;
              $Param['status'] = $this->status;
              $Param['rows'] = $rows;
              $lista[] = $Param;
            }
        }
              
        return $lista;
      }     

      public function updateGrupo($id_grupo, $nome, $status) {
        
        $sql_cadastro = $this->mysqli->prepare("UPDATE app_residuos_grupos SET nome='$nome', status='$status' WHERE id='$id_grupo'");
        $sql_cadastro->execute();
        
        $param['status'] = '01';
        $param['msg'] = 'Grupo atualizado.';
        $lista[] = $param;
              
        return  $lista;
      } 
          
      public function deleteGrupo($id_grupo) {
                    
        $sql_cadastro = $this->mysqli->prepare("DELETE FROM app_residuos_grupos WHERE id='$id_grupo'");
        $sql_cadastro->execute();
        
        $param['status'] = '01';
        $param['msg'] = 'Grupo removido';               
        
        return $param;
      }

      public function saveSubgrupo($id_grupo, $nome, $data, $status) {
              
        $sql_cadastro = $this->mysqli->prepare("INSERT INTO `app_residuos_subgrupos`(`app_residuos_grupos_id`, `nome`, `data`, `status`) VALUES ('$id_grupo', '$nome', '$data', '$status')");
        $sql_cadastro->execute();
        
        $this->id_cadastro = $sql_cadastro->insert_id;
                                    
        $param['status'] = '01';
        $param['msg'] = 'Subgrupo de resíduos adicionado';
        $param['id'] = $this->id_cadastro;
        $lista[] = $param;

        return $lista;
      }   

      public function listSubgrupos($id_grupo) {
                    
        $sql = $this->mysqli->prepare("
        SELECT s.id, s.nome, s.status, g.nome, g.id
        FROM app_residuos_subgrupos as s 
        INNER JOIN app_residuos_grupos as g on g.id = s.app_residuos_grupos_id
        WHERE s.app_residuos_grupos_id='$id_grupo'
        ORDER BY s.nome
        "
        );
        $sql->execute();
        $sql->bind_result($this->id, $this->nome, $this->status, $this->grupo,  $this->id_grupo);
        $sql->store_result();
        $rows = $sql->num_rows;

        if($rows == 0) {
            $Param['rows'] = $rows;
            $lista[] = $Param;
        }
        else{
            while($row =  $sql->fetch()){
          
              $Param['id'] = $this->id;
              $Param['nome'] = $this->nome;
              $Param['grupo'] = $this->grupo;
              $Param['id_grupo'] = $this->id_grupo;
              $Param['status'] = $this->status;
              $Param['rows'] = $rows;
              $lista[] = $Param;
            }
        }
        return $lista;
      } 

      public function updateSubgrupo($id_subgrupo, $id_grupo, $nome, $status) {
          
        $sql_cadastro = $this->mysqli->prepare("UPDATE app_residuos_subgrupos SET nome='$nome', status='$status', app_residuos_grupos_id='$id_grupo' WHERE id='$id_subgrupo'");
        $sql_cadastro->execute();
        
        $param['status'] = '01';
        $param['msg'] = 'Subgrupo atualizado.';
        $lista[] = $param;
              
        return  $lista;
      } 
        
      public function deleteSubgrupo($id_subgrupo) {
                
        $sql_cadastro = $this->mysqli->prepare("DELETE FROM app_residuos_tipos WHERE app_residuos_subgrupos_id='$id_subgrupo'");
        $sql_cadastro->execute();

        $sql_cadastro2 = $this->mysqli->prepare("DELETE FROM app_residuos_subgrupos WHERE id='$id_subgrupo'");
        $sql_cadastro2->execute();
        
        $param['status'] = '01';
        $param['msg'] = 'Subgrupo removido';               
        
        return $param;
      }   

      public function saveTipo($id_subgrupo, $nome, $ncm, $unidade_medida, $app, $status) {
                
        $sql_cadastro = $this->mysqli->prepare("INSERT INTO `app_residuos_tipos`(`app_residuos_subgrupos_id`, `nome`, `ncm`, `unidade_medida`, `app`, `status`) VALUES ('$id_subgrupo', '$nome', '$ncm', '$unidade_medida', '$app', '$status')");
        $sql_cadastro->execute();
        
        $this->id_cadastro = $sql_cadastro->insert_id;
                                    
        $param['status'] = '01';
        $param['msg'] = 'Tipo de resíduos adicionado';  
        $param['id'] = $this->id_cadastro;
        $lista[] = $param;

        return $lista;
      }   

      public function listTipos($id_subgrupo) {
                
        $sql = $this->mysqli->prepare("
        SELECT t.id, s.nome, t.nome, g.nome, t.ncm, t.unidade_medida, t.app, t.status
        FROM app_residuos_tipos as t
        INNER JOIN app_residuos_subgrupos as s on s.id = t.app_residuos_subgrupos_id
        INNER JOIN app_residuos_grupos as g on g.id = s.app_residuos_grupos_id
        WHERE t.app_residuos_subgrupos_id='$id_subgrupo'
        ORDER BY t.nome
        "
        );
        $sql->execute();
        $sql->bind_result($this->id, $this->subgrupo, $this->nome, $this->grupo, $this->ncm, $this->unidade_medida, $this->app, $this->status);
        $sql->store_result();
        $rows = $sql->num_rows;

        if($rows == 0) {
            $Param['rows'] = $rows;
            $lista[] = $Param;
        }
        else{
            while($row =  $sql->fetch()){
          
              $Param['id'] = $this->id;
              $Param['nome'] = $this->nome;
              $Param['grupo'] = $this->grupo;
              $Param['subgrupo'] = $this->subgrupo;
              $Param['ncm'] = $this->ncm;
              $Param['unidade-medida'] = $this->unidade_medida;
              $Param['app'] = $this->app;
              $Param['status'] = $this->status;
              $Param['rows'] = $rows;
              $lista[] = $Param;
            }
        }
              
        return $lista;
    }     

    public function updateTipo($id_tipo, $id_subgrupo, $nome, $ncm, $unidade_medida, $app, $status) {
      
      $sql_cadastro = $this->mysqli->prepare("UPDATE app_residuos_tipos SET nome='$nome', app_residuos_subgrupos_id='$id_subgrupo', ncm='$ncm', unidade_medida='$unidade_medida', app='$app', status='$status' WHERE id='$id_tipo'");
      $sql_cadastro->execute();
      
      $param['status'] = '01';
      $param['msg'] = 'Tipo de resíduo atualizado.';
      $lista[] = $param;
            
      return  $lista;
    } 
    
    public function deleteTipo($id_tipo) { // verificar pois está com restrição de foreign key e não deixa deletar!!!
            
      $sql_cadastro = $this->mysqli->prepare("DELETE FROM app_residuos_tipos WHERE id='$id_tipo'");
      $sql_cadastro->execute();
      
      $param['status'] = '01';
      $param['msg'] = 'Tipo de resíduo removido';               
      
      return $param;
    }             
}

 ?>
