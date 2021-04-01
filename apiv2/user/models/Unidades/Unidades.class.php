<?php

// require_once MODELS . '/Conexao/Conexao.class.php';
require_once MODELS . '/Secure/Secure.class.php';
require_once MODELS . '/Estados/Estados.class.php';
require_once DAOS . '/UnidadesDao.class.php';



class Unidades  {

    public function __construct() {

        $this->dao = new UnidadesDao();

        $request = file_get_contents('php://input');
        $this->input = json_decode($request);

        $this->secure = new Secure();             
    }
       
     
    public function save() {
                       
        $this->secure->tokens_secure($this->input->token);     

        $estados = new Estados();
        $estados->RetornaID($this->input->estado, $this->input->cidade);
       
        $nova_unidade = $this->dao->save($this->input->cod_empresa, $this->input->nome, $this->input->cep, $estados->id_estado, $estados->id_cidade, $this->input->endereco, $this->input->bairro, $this->input->numero, $this->input->complemento, $status=1);
               
        foreach($this->input->deps as $dep) {
            $this->dao->saveDeptoUnidade($nova_unidade, $dep);
        }
        $result['status'] = '01';
        $result['msg'] = 'Unidade adicionada';

        $resultArray[] = $result;
        $json = json_encode($resultArray);
        echo $json;
    }
   

    public function listId() {
       
        $this->secure->tokens_secure($this->input->token);   
        
        $result = $this->dao->listId($this->input->id);        

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
    

    public function update() {
            
        $this->secure->tokens_secure($this->input->token);  
       
        $result = $this->dao->update($this->input->id, $this->input->nome, $this->input->cep, $this->input->estado, $this->input->cidade, $this->input->endereco, $this->input->bairro, $this->input->numero, $this->input->complemento, $this->input->status);
        
        $this->dao->deleteUnidadesDptos($this->input->id);
       
        foreach($this->input->deps as $dep) {
            $this->dao->saveDeptoUnidade($this->input->id, $dep);
        }

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
       
}
