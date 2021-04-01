<?php

// require_once MODELS . '/Conexao/Conexao.class.php';
require_once MODELS . '/Secure/Secure.class.php';
require_once MODELS . '/Estados/Estados.class.php';
require_once DAOS . '/DepartamentosDao.class.php';



class Departamentos  {

    public function __construct() {

        $this->dao = new DepartamentosDao();

        $request = file_get_contents('php://input');
        $this->input = json_decode($request);

        $this->secure = new Secure();     
        $this->data_atual = date('Y-m-d H:i:s');
    }
    
    public function save() {
                       
        $this->secure->tokens_secure($this->input->token);     
              
        $result = $this->dao->save($this->input->cod_empresa, $this->input->nome, $status=1);   
                
        $resultArray[] = $result;
        $json = json_encode($resultArray);
        echo $json;
    }

    public function update() {
                       
        $this->secure->tokens_secure($this->input->token);     
              
        $result = $this->dao->update($this->input->id, $this->input->nome, $this->input->status);   
                
        $resultArray[] = $result;
        $json = json_encode($resultArray);
        echo $json;
    }

    public function delete() {
                       
        $this->secure->tokens_secure($this->input->token);     
              
        $this->dao->deleteUnidadesDptos($this->input->id); 
        $result = $this->dao->delete($this->input->id);   
                
        $resultArray[] = $result;
        $json = json_encode($resultArray);
        echo $json;
    }

    public function listAll() {
                       
        $this->secure->tokens_secure($this->input->token);     
              
        $result = $this->dao->listAll($this->input->cod_empresa);   
                
        $json = json_encode($result);
        echo $json;
    }
       
}
