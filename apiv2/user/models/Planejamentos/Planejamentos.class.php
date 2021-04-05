<?php

// require_once MODELS . '/Conexao/Conexao.class.php';
require_once MODELS . '/Secure/Secure.class.php';
require_once MODELS . '/Estados/Estados.class.php';
require_once DAOS . '/PlanejamentosDao.class.php';



class Planejamentos  {

    public function __construct() {

        $this->dao = new PlanejamentosDao();

        $request = file_get_contents('php://input');
        $this->input = json_decode($request);

        $this->secure = new Secure();     
        $this->data_atual = date('Y-m-d H:i:s');
    }
    
    public function save() {
                       
        $this->secure->tokens_secure($this->input->token);     
                      
        $result = $this->dao->save($this->input->id_unidade, $this->input->id_rota, $this->input->cod_empresa, $this->input->id_de, $this->input->id_para, dataUS($this->input->data_de), dataUS($this->input->data_ate), $this->input->horario, $this->input->fixa, $this->data_atual, $status=1);   
                                  
        $resultArray[] = $result;
        $json = json_encode($resultArray);
        echo $json;
    }

    public function listAllGestor() {
                       
        $this->secure->tokens_secure($this->input->token);     
              
        $result = $this->dao->listAllGestor($this->input->cod_empresa, $this->input->data_de, $this->input->data_ate);   
                
        $json = json_encode($result);
        echo $json;
    }

    // public function update() {
            
    //     $this->secure->tokens_secure($this->input->token);  
       
        
    //     $result = $this->dao->update($this->input->id, $this->input->id_unidade, $this->input->nome, $this->input->setor, $this->input->predio_bloco, $this->input->obs, $this->input->status);
        
    //     $this->dao->deleteContatosLocal($this->input->id);
       
    //     for($i = 0; $i<count($this->input->contatos); $i++) {
           
    //         $this->dao->saveContatosLocal(
    //             $this->input->id, 
    //             $this->input->contatos[$i]->nome_resp, 
    //             $this->input->contatos[$i]->email_resp, 
    //             $this->input->contatos[$i]->is_resp, 
    //             $this->data_atual, 
    //             $this->input->contatos[$i]->status_resp
    //         );
    //     }

    //     $resultArray[] = $result;
    //     $json = json_encode($resultArray);
    //     echo $json;
    // }

    // public function delete() {
                       
    //     $this->secure->tokens_secure($this->input->token);     
              
    //     $this->dao->deleteContatosLocal($this->input->id); 
    //     $result = $this->dao->delete($this->input->id);   
                
    //     $resultArray[] = $result;
    //     $json = json_encode($resultArray);
    //     echo $json;
    // }

    

    // public function listId() {
                       
    //     $this->secure->tokens_secure($this->input->token);     
              
    //     $result = $this->dao->listId($this->input->id);   
                
    //     $json = json_encode($result);
    //     echo $json;
    // }
       
}
