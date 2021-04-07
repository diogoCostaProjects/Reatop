<?php

// require_once MODELS . '/Conexao/Conexao.class.php';
require_once MODELS . '/Secure/Secure.class.php';
require_once MODELS . '/Estados/Estados.class.php';
require_once MODELS . '/ResizeFiles/ResizeFiles.class.php';
require_once DAOS . '/BrieffingDao.class.php';



class Brieffing  {

    public function __construct() {

        $this->dao = new BrieffingDao();

        $request = file_get_contents('php://input');
        $this->input = json_decode($request);

        $this->secure = new Secure();     
        $this->data_atual = date('Y-m-d H:i:s');
    }
    
    public function iniciar() { // starta o brieffing para depois salvar as repostas, usar o id que retorna para dar inicio as demais estapas
                       
        $this->secure->tokens_secure($this->input->token);     
       
        $result = $this->dao->save($this->input->id_planejamento, $this->input->id_local, $this->input->cod_empresa, $this->input->id_user, $this->data_atual, $this->input->obs, $this->input->id_status);   
                
        $json = json_encode($result);
        echo $json;
    }

    public function saveResiduo() {

        $novoResiduo = $this->dao->saveResiduo($_POST['id_briefieng'], $_POST['id_residuo_tipo'], $_POST['kg'], $_POST['sacos']);
        
        $this->images = renameUpload(basename($_FILES['image']['name']));
        $this->images_tmp = $_FILES['image']['tmp_name'];
        $this->tipo = $_POST['tipo'];

        $this->i=0;

        foreach ($this->images_tmp as $key => $tmpName):
            $this->pasta = '../../brieffing';
            $this->file_name = RenameUpload($_FILES['image']['name'][$key]);

            $this->file_tmp = $_FILES['image']['tmp_name'][$key];
            $this->url_param_final = $this->file_name;
            
            move_uploaded_file($this->file_tmp, $this->pasta .'/'. $this->url_param_final);

            $this->dao->saveResiduoFotos($novoResiduo, $this->tipo[$this->i], $this->url_param_final, $this->data_atual);

            $this->i ++;
        endforeach;

        $param['status'] = '01';
        $param['msg'] = 'Resíduo adicionado.';

        $lista[] = $param;

        echo json_encode($lista);
    }

    public function delete() {
                       
        $this->secure->tokens_secure($this->input->token);     
                
        $result = $this->dao->delete($this->input->id);   
                
        $resultArray[] = $result;
        $json = json_encode($resultArray);
        echo $json;
    }

    public function updateAssinatura() {
                
        $this->images = renameUpload(basename($_FILES['file']['name']));
        $this->images_tmp = $_FILES['file']['tmp_name'];
        $this->id = $_POST['id'];
               
        $this->pasta = '../../assinaturas';
        $this->file_name = RenameUpload($_FILES['file']['name']);

        $this->file_tmp = $_FILES['file']['tmp_name'];
        $this->url_param_final = $this->file_name;
        
        move_uploaded_file($this->file_tmp, $this->pasta .'/'. $this->url_param_final);

        $result = $this->dao->updateAssinatura($this->id, $this->url_param_final, $this->data_atual);
                
        echo json_encode($result);
    }

    public function updateConformidade() {
                       
        $this->secure->tokens_secure($this->input->token);     
                
        $result = $this->dao->updateConformidade($this->input->id, $this->input->conformidade);   
                
        $resultArray[] = $result;
        $json = json_encode($resultArray);
        echo $json;
    }

    public function finalizar() {
                       
        $this->secure->tokens_secure($this->input->token);     
                
        $result = $this->dao->updateStatus($this->input->id, $status=2);   
                
        $resultArray[] = $result;
        $json = json_encode($resultArray);
        echo $json;
    }

    public function listAndamento() {
                       
        $this->secure->tokens_secure($this->input->token);     
                
        $result = $this->dao->listAndamento($this->input->id_user);   
                
        $json = json_encode($result);
        echo $json;
    }

    public function listAll() {
                       
        $this->secure->tokens_secure($this->input->token);     
                
        $result = $this->dao->listAll($this->input->id_planejamento);   
                
        $json = json_encode($result);
        echo $json;
    }

    public function listId() {
                       
        $this->secure->tokens_secure($this->input->token);     
                
        $result = $this->dao->listId($this->input->id);   
                
        $json = json_encode($result);
        echo $json;
    }


    // public function listAllGestor() {
                       
    //     $this->secure->tokens_secure($this->input->token);     
              
    //     $result = $this->dao->listAllGestor($this->input->cod_empresa, dataUS($this->input->data_de), dataUS($this->input->data_ate));   
                
    //     $json = json_encode($result);
    //     echo $json;
    // }

    // public function listAllApp() {
                       
    //     $this->secure->tokens_secure($this->input->token);     
              
    //     $result = $this->dao->listAllApp($this->input->id_user);   
                
    //     $json = json_encode($result);
    //     echo $json;
    // }

    // public function listFixasApp() {
                       
    //     $this->secure->tokens_secure($this->input->token);     
              
    //     $result = $this->dao->listFixasApp($this->input->id_user);   
                
    //     $json = json_encode($result);
    //     echo $json;
    // }

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

    

    

    // public function listId() {
                       
    //     $this->secure->tokens_secure($this->input->token);     
              
    //     $result = $this->dao->listId($this->input->id);   
                
    //     $json = json_encode($result);
    //     echo $json;
    // }
       
}
