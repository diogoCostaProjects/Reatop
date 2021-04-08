<?php

// require_once MODELS . '/Conexao/Conexao.class.php';
require_once MODELS . '/Secure/Secure.class.php';
require_once MODELS . '/Estados/Estados.class.php';
require_once MODELS . '/ResizeFiles/ResizeFiles.class.php';
require_once DAOS . '/ResiduosDao.class.php';



class Residuos  {

    public function __construct() {

        $this->dao = new ResiduosDao();

        $request = file_get_contents('php://input');
        $this->input = json_decode($request);

        $this->secure = new Secure();     
        $this->data_atual = date('Y-m-d H:i:s');
    }
        
    public function saveGrupo() {
        
        $this->secure->tokens_secure($this->input->token);   
        $result = $this->dao->saveGrupo($this->input->cod_empresa, $this->input->nome, $this->data_atual, $status=1);
        echo json_encode($result);
    }
    // GRUPOS

    public function listGrupos() {
        
        $this->secure->tokens_secure($this->input->token);   
        $result = $this->dao->listGrupos($this->input->cod_empresa);
        echo json_encode($result);
    }

    public function updateGrupo() {
                       
        $this->secure->tokens_secure($this->input->token);     
        $result = $this->dao->updateGrupo($this->input->id_grupo, $this->input->nome, $this->input->status);   
        echo json_encode($result);
    }

    public function deleteGrupo() {
                       
        $this->secure->tokens_secure($this->input->token);     
        $result = $this->dao->deleteGrupo($this->input->id_grupo);   
        echo json_encode($result);
    }

    //TIPOS

    public function saveTipo() {
        
        $this->secure->tokens_secure($this->input->token);   
        $result = $this->dao->saveTipo($this->input->id_subgrupo, $this->input->nome, $this->input->ncm, $this->input->unidade_medida, $this->input->app, $status=1);
        echo json_encode($result);
    }
   
    public function listTipos() {
        
        $this->secure->tokens_secure($this->input->token);   
        $result = $this->dao->listTipos($this->input->id_subgrupo);
        echo json_encode($result);
    }

    public function updateTipo() {
                       
        $this->secure->tokens_secure($this->input->token);     
        $result = $this->dao->updateTipo($this->input->id_tipo, $this->input->id_subgrupo, $this->input->nome, $this->input->ncm, $this->input->unidade_medida, $this->input->app, $this->input->status);   
        echo json_encode($result);
    }

    public function deleteTipo() {
                       
        $this->secure->tokens_secure($this->input->token);     
        $result = $this->dao->deleteTipo($this->input->id_tipo);   
        echo json_encode($result);
    }

    // SUBGRUPOS
    public function saveSubgrupo() {
        
        $this->secure->tokens_secure($this->input->token);   
        $result = $this->dao->saveSubgrupo($this->input->id_grupo, $this->input->nome, $this->data_atual, $status=1);
        echo json_encode($result);
    }
   
    public function listSubgrupos() {
        
        $this->secure->tokens_secure($this->input->token);   
        $result = $this->dao->listSubgrupos($this->input->id_grupo);
        echo json_encode($result);
    }

    public function updateSubgrupo() {
                       
        $this->secure->tokens_secure($this->input->token);     
        $result = $this->dao->updateSubgrupo($this->input->id_subgrupo, $this->input->id_grupo, $this->input->nome, $this->input->status);   
        echo json_encode($result);
    }

    public function deleteSubgrupo() {
                       
        $this->secure->tokens_secure($this->input->token);     
        $result = $this->dao->deleteSubgrupo($this->input->id_subgrupo);   
        echo json_encode($result);
    }
           
}
