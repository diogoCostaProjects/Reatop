<?php

  require_once MODELS . '/Conexao/Conexao.class.php';
  

  class CadastrosDao extends Conexao {

        public function __construct() {

            $this->Conecta();
            $this->tabela = "app_users";
            $this->tabela_fcm_usuario = "app_fcm";
            $this->data_atual = date('Y-m-d H:i:s');
            $this->tabela_cidades = "cidades";
            $this->tabela_estados = "estados";
        }

        public function getTipoNome($tipo){
          
          switch ($tipo) {
            case 1:
              return "Master";
            case 2:
              return "Gestor";
            case 3:
              return "App";
          }
        }

              

        // public function loginEmpresa($email, $password) {

         
        //       $sql = $this->mysqli->prepare("
        //       SELECT u.id, u.tipo, u.nome, u.email, u.password, u.cpf, u.celular, u.cod_empresa
        //       FROM `$this->tabela` as u 
        //       WHERE u.email = '$email' AND u.status='1'"
        //       );
        //       $sql->execute();
        //       $sql->bind_result($this->id, $this->tipo, $this->nome, $this->email, $this->password_hash, $this->cpf, $this->celular, $this->cod_empresa);
        //       $sql->fetch();
        //       $sql->close();

        //       if (crypt($password, $this->password_hash) === $this->password_hash) {

        //         $Param['status'] = '01';
        //         $Param['id'] = $this->id;
        //         $Param['tipo'] = $this->tipo;
        //         $Param['cod_empresa'] = $this->cod_empresa;
        //         $Param['tipo_nome'] = $this->getTipoNome($this->tipo);
        //         $Param['cpf'] = $this->cpf;
        //         $Param['celular'] = $this->celular;
        //         $Param['nome'] = $this->nome;
        //         $Param['email'] = $this->email;
                                
        //         $Param['msg'] = 'Login efetuado com sucesso!';
        //       }
        //       else {
        //           $Param['status'] = '02';
        //           $Param['msg'] = 'E-mail ou Senha incorretos, tente outros dados!';
        //       }

        //       return $Param;
        // }

        public function getPasswordByCpf($cpf){
          
          $sql = $this->mysqli->prepare("
          SELECT u.password
          FROM `$this->tabela` as u 
          WHERE u.cpf = '$cpf'"
          );
          $sql->execute();
          $sql->bind_result($this->password);
          $sql->fetch();
          
          if($this->password==""){
              $Param['status'] = '02';
              $Param['msg'] = 'Você ainda não possui uma senha cadastrada. Crie uma senha e acesse novamente.';      
          } else {
              $Param['status'] = '01';
              $Param['msg'] = 'OK';  
          }

          return $Param;
        }

        public function savePassword($cpf, $password){
        
              $this->custo = '08';
              $this->salt = geraSalt(22);
              $this->hash = crypt($password, '$2a$' . $this->custo . '$' . $this->salt . '$');

              $sql = $this->mysqli->prepare("UPDATE `$this->tabela` SET password='$this->hash' WHERE cpf='$cpf'");
              $sql->execute();
              $sql->close();

              $Param['status'] = '01';
              $Param['msg'] = 'Senha criada com sucesso.';  

              return $Param;

        }


        public function loginUser($cpf, $password) {
         
          $sql = $this->mysqli->prepare("
          SELECT u.id, u.tipo, u.nome, u.email, u.password, u.cpf, u.celular, u.cod_empresa
          FROM `$this->tabela` as u 
          WHERE u.cpf = '$cpf' AND u.status='1'"
          );
          $sql->execute();
          $sql->bind_result($this->id, $this->tipo, $this->nome, $this->email, $this->password_hash, $this->cpf, $this->celular, $this->cod_empresa);
          $sql->fetch();
          $sql->close();

          if (crypt($password, $this->password_hash) === $this->password_hash) {

            $Param['status'] = '01';
            $Param['id'] = $this->id;
            $Param['tipo'] = $this->tipo;
            $Param['cod_empresa'] = $this->cod_empresa;
            $Param['tipo_nome'] = $this->getTipoNome($this->tipo);
            $Param['cpf'] = $this->cpf;
            $Param['celular'] = $this->celular;
            $Param['nome'] = $this->nome;
            $Param['email'] = $this->email;
                            
            $Param['msg'] = 'Login efetuado com sucesso!';
          }
          else {
              $Param['status'] = '02';
              $Param['msg'] = 'CPF ou Senha incorretos, tente outros dados!';
          }

          return $Param;
    }
        

        public function saveFcm($id, $type, $fcm) {


            $sql_fcm = $this->mysqli->prepare("SELECT registration_id FROM `$this->tabela_fcm_usuario` WHERE app_users_id='$id'");
            $sql_fcm->execute();
            $sql_fcm->bind_result($this->fcm_salvo);

            $FCMIDs = array();
            while ($row = $sql_fcm->fetch()) {

                $this->fcm_id = $this->fcm_salvo;
                $FCMIDs[] = $this->fcm_id;
            }

            if(!in_array($fcm, $FCMIDs)) {

                $sql = $this->mysqli->prepare("INSERT INTO `$this->tabela_fcm_usuario`
                (app_users_id, type, registration_id)
                VALUES (?, ?, ?)");
                $sql->bind_param('iis', $id, $type, $fcm);
                $sql->execute();
                $sql->close();

                $lista = array();

                $Param['status'] = '01';
                $Param['msg'] = 'FCM salvo com sucesso!';
                $lista[] = $Param;

               return $lista;

            }
          }
   
                

        public function saveUser($cod_empresa, $tipo, $nome, $email, $password, $cpf, $celular, $status) {
        
            
          $sql = $this->mysqli->prepare("SELECT id FROM `$this->tabela` WHERE cpf='$cpf'");
          $sql->execute();
          $sql->bind_result($this->id);
          $sql->store_result();
          $sql->fetch();
          $rows = $sql->num_rows;

          if ($rows == 0) {  // não existe, então insere
                       
              $sql_cadastro = $this->mysqli->prepare("
              INSERT INTO `$this->tabela`
              (cod_empresa, tipo, nome, email, cpf, celular, status, data_cadastro ) VALUES ('$cod_empresa', '$tipo', '$nome', '$email', '$cpf', '$celular', '$status', '$this->data_atual')");
              
              $sql_cadastro->execute();
              $this->id_cadastro = $sql_cadastro->insert_id;
              
              $Param['status'] = '01';
              $Param['id'] = $this->id_cadastro;
              $Param['nome'] = $nome;
              $Param['email'] = $email;
              $Param['cpf'] = $cpf;
              $Param['msg'] = 'Usuário adicionado.';
        }

        else { 
            $Param['status'] = '02';
            $Param['msg'] = 'Já existe um cadastro com este CPF, tente outros dados.';
        }

        return $Param;
      }

        

        public function equalsEmail($email) {
              
            $sql = $this->mysqli->prepare("SELECT id, nome, email, password, avatar, tipo  FROM `$this->tabela` WHERE email = '$email' AND status='1'");
            $sql->execute();
            $sql->bind_result($this->id, $this->nome, $this->email, $this->password_hash, $this->avatar, $this->tipo);
            $sql->fetch();

            $Param['id'] = $this->id;
            $Param['nome'] = ucwords($this->nome);
            $Param['email'] = $this->email;
            $Param['password'] = $this->password_hash;
            $Param['avatar'] = $this->avatar;
            $Param['tipo'] = $this->tipo;
                

            return $Param;
        }

        public function listId($id) {

                        
            $sql = $this->mysqli->prepare("
            SELECT u.id, u.nome, u.email, u.celular, u.cpf, u.id_estado, u.id_cidade, u.endereco, u.bairro, u.numero, u.complemento, c.id, c.nome  
            FROM `$this->tabela`as u 
            INNER JOIN `$this->tabela_categorias` as c on c.id = u.app_users_categorias_id
            WHERE u.id='$id'
            ");
            $sql->execute();
            $sql->bind_result($this->id, $this->nome, $this->email, $this->celular, $this->cpf, $this->id_estado, $this->id_cidade, $this->endereco, $this->bairro, $this->numero, $this->complemento, $this->id_categoria, $this->nome_categoria);
            $sql->fetch();

            $lista = array();
            $estados = new Estados();
            $estados->RetornaNome($this->id_estado, $this->id_cidade);
           
            $Param['id'] = $this->id;
            $Param['nome'] = ucwords($this->nome);
            $Param['email'] = $this->email;
            $Param['celular'] = $this->celular;
            $Param['cpf'] = $this->cpf;
            $Param['estado'] = $estados->id_estado;
            $Param['cidade'] = $estados->id_cidade;
            $Param['cep'] = $this->cep;
            $Param['endereco'] = $this->endereco;
            $Param['numero'] = $this->numero;
            $Param['bairro'] = $this->bairro;
            $Param['complemento'] = $this->complemento;
            $Param['id_categoria'] = $this->id_categoria;
            $Param['nome_categoria'] = $this->nome_categoria;
           
            return $Param;
        }

        public function verificaEmail($email, $id) {

            $sql = $this->mysqli->prepare("SELECT email FROM `$this->tabela` WHERE email = '$email' and id <> '$id'");
            $sql->execute();
            $sql->bind_result($this->email_encontrado);
            $sql->fetch();

            if($this->email_encontrado != "") {
                $Param['status'] = '02';
                $Param['msg'] = 'Email já utilizado, tente outros dados!';

                $lista[] = $Param;

                $json = json_encode($lista);
                echo $json;

                exit;
            }
        }
        public function updateEndereco($id, $id_cidade, $id_estado, $endereco, $numero, $complemento, $bairro) {
            
            $sql = $this->mysqli->prepare("
            UPDATE `$this->tabela`
            SET id_estado = '$id_estado', id_cidade = '$id_cidade', bairro = '$bairro', endereco = '$endereco', numero = '$numero', complemento = '$complemento'
            WHERE id = '$id'
            ");
           
            $sql->execute();

            $Param['status'] = '01';
            $Param['msg'] = 'Endereço atualizado com sucesso.';

            return $Param;

        }

        public function updateDados($nome, $email, $celular, $cpf, $categoria, $id) {

            $this->verificaEmail($email, $id);

            $sql = $this->mysqli->prepare("
            UPDATE `$this->tabela`
            SET nome = ?, email = ?, celular = ?, cpf = ?, app_users_categorias_id = ?
            WHERE id = ?
            ");
            $sql->bind_param('ssssii', $nome, $email, $celular, $cpf, $categoria, $id);
            $sql->execute();

            $Param['status'] = "01";
            $Param['msg'] = "Dados alterados com sucesso.";

            return $Param;
        }

        public function updatePassword($password, $id){

                $this->custo = '08';
                $this->salt = geraSalt(22);

                // Gera um hash baseado em bcrypt
                $this->hash = crypt($password, '$2a$' . $this->custo . '$' . $this->salt . '$');

                $sql = $this->mysqli->prepare("UPDATE `$this->tabela` SET password = ? WHERE id = ? ");
                $sql->bind_param('si', $this->hash, $id);
                $sql->execute();

                $lista = array();

                if ($sql->affected_rows) {

                    $Param['status'] = '01';
                    $Param['msg'] = 'Senha alterada com sucesso!';
                }
                else {
                    $Param['status'] = '02';
                    $Param['msg'] = 'Erro ao alterar senha, tente novamente!';
                }
                return $Param;
            }
        }




 ?>
