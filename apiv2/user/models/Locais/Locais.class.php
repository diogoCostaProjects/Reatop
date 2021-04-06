<?php

// require_once MODELS . '/Conexao/Conexao.class.php';
require_once MODELS . '/Secure/Secure.class.php';
require_once MODELS . '/Estados/Estados.class.php';
require_once DAOS . '/LocaisDao.class.php';



class Locais  {

    public function __construct() {

        $this->dao = new LocaisDao();

        $request = file_get_contents('php://input');
        $this->input = json_decode($request);

        $this->secure = new Secure();     
        $this->data_atual = date('Y-m-d H:i:s');
    }
    
    public function save() {
                       
        $this->secure->tokens_secure($this->input->token);     
              
        $qrCode = randString(8);

        $result = $this->dao->save($this->input->cod_empresa, $this->input->id_unidade, $this->input->nome, $this->input->setor, $this->input->predio_bloco, $this->input->obs, $qrCode, $status=1);   
          
        $novo_local = $result['id'];

        for($i = 0; $i<count($this->input->contatos); $i++) {
           
            $this->dao->saveContatosLocal(
                $novo_local, 
                $this->input->contatos[$i]->nome_resp, 
                $this->input->contatos[$i]->email_resp, 
                $this->input->contatos[$i]->is_resp, 
                $this->data_atual, 
                $status=1
            );
        }
                
        $resultArray[] = $result;
        $json = json_encode($resultArray);
        echo $json;
    }

    public function update() {
            
        $this->secure->tokens_secure($this->input->token);  
       
        
        $result = $this->dao->update($this->input->id, $this->input->id_unidade, $this->input->nome, $this->input->setor, $this->input->predio_bloco, $this->input->obs, $this->input->status);
        
        $this->dao->deleteContatosLocal($this->input->id);
       
        for($i = 0; $i<count($this->input->contatos); $i++) {
           
            $this->dao->saveContatosLocal(
                $this->input->id, 
                $this->input->contatos[$i]->nome_resp, 
                $this->input->contatos[$i]->email_resp, 
                $this->input->contatos[$i]->is_resp, 
                $this->data_atual, 
                $this->input->contatos[$i]->status_resp
            );
        }

        $resultArray[] = $result;
        $json = json_encode($resultArray);
        echo $json;
    }

    public function delete() {
                       
        $this->secure->tokens_secure($this->input->token);     
              
        $this->dao->deleteContatosLocal($this->input->id); 
        $result = $this->dao->delete($this->input->id);   
                
        $resultArray[] = $result;
        $json = json_encode($resultArray);
        echo $json;
    }

    public function listAll() {
                       
        $this->secure->tokens_secure($this->input->token);     
              
        $result = $this->dao->listAll($this->input->id_unidade);   
                
        $json = json_encode($result);
        echo $json;
    }

    public function listId() {
                       
        $this->secure->tokens_secure($this->input->token);     
              
        $result = $this->dao->listId($this->input->id);   
                
        $json = json_encode($result);
        echo $json;
    }

    public function listByQr() {
                       
        $this->secure->tokens_secure($this->input->token);     
              
        $result = $this->dao->listByQr($this->input->qrcode);   
                
        $json = json_encode($result);
        echo $json;
    }
       
}
