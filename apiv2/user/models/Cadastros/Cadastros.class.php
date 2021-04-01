<?php

// require_once MODELS . '/Conexao/Conexao.class.php';
require_once MODELS . '/Secure/Secure.class.php';
require_once MODELS . '/ResizeFiles/ResizeFiles.class.php';
require_once MODELS . '/Emails/Emails.class.php';
require_once MODELS . '/Estados/Estados.class.php';
require_once DAOS . '/CadastrosDao.class.php';



class Cadastros  {

    public function __construct() {

        $this->dao = new CadastrosDao();

        $request = file_get_contents('php://input');
        $this->input = json_decode($request);

        $this->secure = new Secure();
    }

        
     
    public function saveUser() {
                      
        $this->secure->tokens_secure($this->input->token);     
       
        $result = $this->dao->saveUser($this->input->cod_empresa, $this->input->tipo, $this->input->nome, $this->input->email, $this->input->password, tiraCarac($this->input->cpf), $this->input->celular, $status=1);
        
        
        $resultArray[] = $result;
        $json = json_encode($resultArray);
        echo $json;
    }

    

    public function loginFace(){
        
        
        $this->email = $_POST['email'];
        $this->nome = $_POST['nome'];
        $this->avatar = renameUpload(basename($_FILES['avatar']['name']));
        $this->avatar_tmp = $_FILES['avatar']['tmp_name'];
        $this->id_categoria = $_POST['id_categoria'];
        $this->latitude = $_POST['latitude'];
        $this->longitude = $_POST['longitude'];
        $this->tipo = 1;

        $equalsResult = $this->dao->equalsEmail($this->email);

        if($equalsResult['id'] != "") {
            $equalsResult['msg'] = "Login efetuado com sucesso.";
            $this->CadastrosArray[] = $equalsResult;
        }
        else {
        
                if(!empty($this->avatar)) {

                    $this->avatar_final = $this->avatar;
                    move_uploaded_file($this->avatar_tmp, $this->pasta . "/" . $this->avatar_final);

                }
                else{
                    $this->avatar_final = "avatarm.png";
                }

                $enderecoCompleto = geraEndCompleto($this->latitude, $this->longitude);
                $estados = new Estados();
                // [0] => RS
                // [1] => Porto Alegre
                // [2] => Protásio Alves
                // [3] => Av. Protásio Alves
                // [4] => 91310
                $this->estado = $enderecoCompleto[0];
                $this->cidade = $enderecoCompleto[1];
                $this->bairro = $enderecoCompleto[2];
                $this->endereco = $enderecoCompleto[3];
                $this->cep = $enderecoCompleto[4];

                
                $estados->RetornaID($this->estado, $this->cidade);


                $resultSave = $this->dao->save($this->nome, $this->email, $this->documento=null, $this->data_nascimento=null, $this->password=null,
                                            $this->tipo, $this->id_categoria, $this->latitude,
                                            $this->longitude, $this->avatar, $this->status=1, $this->celular=null, $estados->id_estado, 
                                            $estados->id_cidade, $this->endereco, $this->bairro, $this->cep, $this->numero=0, $this->complemento=0
                                            );
                $resultSave['msg'] = "Login efetuado com sucesso.";

                $this->CadastrosArray[] = $resultSave;                          

        }
        $json = json_encode($this->CadastrosArray);
        echo $json;

    }
   


  public function loginEmpresa() {
       
        $this->secure->tokens_secure($this->input->token);   

        $result = $this->dao->loginEmpresa($this->input->email, $this->input->password);
             
        $resultArray[] = $result;
        $json = json_encode($resultArray);
        echo $json;
    }

    public function loginUser() {
       
        $this->secure->tokens_secure($this->input->token);   

        $result = $this->dao->loginUser($this->input->cpf, $this->input->password);
             
        $resultArray[] = $result;
        $json = json_encode($resultArray);
        echo $json;
    }

    public function getPasswordByCpf() {
       
        $this->secure->tokens_secure($this->input->token);   

        $result = $this->dao->getPasswordByCpf(tiraCarac($this->input->cpf));
             
        $resultArray[] = $result;
        $json = json_encode($resultArray);
        echo $json;
    }

    public function createPassword(){
        
        $this->secure->tokens_secure($this->input->token);   

        $result = $this->dao->savePassword(tiraCarac($this->input->cpf), $this->input->password);
             
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

    public function getEndereco($id_user) {
                       
        $result = $this->dao->listEndereco($id_user);        
        return $result;
    }

    

    public function recuperarsenha() {

        $request = file_get_contents('php://input');
        $input = json_decode($request);

        $this->email = strtolower($input->email);

        //VERIFICA SE JÁ EXISTE E-MAIL
        $sql = $this->mysqli->prepare("SELECT id, nome, token FROM `$this->tabela` WHERE email='$this->email'");
        $sql->execute();
        $sql->bind_result($this->id, $this->nome, $this->token);
        $sql->store_result();
        $rows = $sql->num_rows;
        $sql->fetch();

        if ($rows > 0) {

          //ENVIA E-MAIL RECUPERACAO DE SENHA
          $mail = new Emails();
          $mail->recuperarsenha($this->email, $this->nome, $this->token);

          $Param['status'] = '01';
          $Param['msg'] = 'As instruções para alteração de senha foram enviadas para o seu e-mail.';
          $lista[] = $Param;

          $json = json_encode($lista);
          echo $json;
        }
        if ($rows == 0) {
            $Param['status'] = '02';
            $Param['msg'] = 'Não encontramos o seu e-mail em nosso cadastro, favor, tente outros dados';
            $lista[] = $Param;
            $json = json_encode($lista);
            echo $json;
        }

    }
    
    

    public function updateEndereco() {

        $this->secure->tokens_secure($this->input->token); 
        
        $estados = New Estados();
        $estados->RetornaID($this->input->estado, $this->input->cidade);
        
        $result = $this->dao->updateEndereco($this->input->id, $estados->id_cidade, $estados->id_estado, $this->input->endereco, $this->input->numero, 
                                            $this->input->complemento, $this->input->bairro); 

        $resultArray[] = $result;
        $json = json_encode($resultArray);
        echo $json;
    }

    public function updateDados() {
            
        $this->secure->tokens_secure($this->input->token);  

        $result = $this->dao->updateDados($this->input->nome, $this->input->email, $this->input->celular, tiraCarac($this->input->cpf), $this->input->categoria, $this->input->id);

        $resultArray[] = $result;
        $json = json_encode($resultArray);
        echo $json;
    }


    public function updatepassword() {
       
        $this->secure->tokens_secure($this->input->token);  
        
        $result = $this->dao->updatePassword($this->input->password, $this->input->id);

        $resultArray[] = $result;
        $json = json_encode($resultArray);
        echo $json;
        
    }
    
   public function saveFcm() {
            
        $this->secure->tokens_secure($this->input->token); 

        $result = $this->dao->saveFcm($this->input->id, $this->input->type, $this->input->fcm);

        $json = json_encode($result);
        echo $json;
    }
}
