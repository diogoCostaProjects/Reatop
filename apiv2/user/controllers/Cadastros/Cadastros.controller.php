<?php

require_once MODELS . '/Cadastros/Cadastros.class.php';



class CadastrosController extends Cadastros {

    public function __construct() {

        $this->model = New Cadastros();
    }
   
    public function adicionaruser() {
        $this->model->saveUser($this->model);
    }     
    public function loginempresa() {
        $this->model->loginEmpresa($this->model);
    }
    public function loginuser() {
        $this->model->loginUser($this->model);
    }
    public function getpasswordbycpf() {
        $this->model->getPasswordByCpf($this->model);
    }
    public function createpassword() {
        $this->model->createPassword($this->model);
    }
    public function listappusers() {
        $this->model->listAppUsers($this->model);
    }
    public function listgestoresusers() {
        $this->model->listGestoresUsers($this->model);
    }
<<<<<<< HEAD
    
=======
>>>>>>> fc9b5aecd85408a582e2dfc43d267d78297568c7
    
    
    // public function loginface() {
    //     $this->model->loginFace($this->model);
    // }
    // public function listid() {
    //     $this->model->listId($this->model);
    // }
    // public function updatedados() {
    //     $this->model->updatedados($this->model);
    // }
    // public function updateuser() {
    //   $this->model->updateUser($this->model);
    // }
    // public function updateendereco() {
    //     $this->model->updateEndereco($this->model);
    // }
    // public function updatepassword() {
    //     $this->model->updatepassword($this->model);
    // }
    // public function listdados() {
    //     $this->model->listdadosID($this->model);
    // }
    // public function listendereco() {
    //     $this->model->getEndereco($this->model);
    // }
    // public function recuperarsenha() {
    //     $this->model->recuperarsenha($this->model);
    // }
    // public function fcm() {
    //     $this->model->saveFcm($this->model);
    // }
    // public function updatelocalizacao() {
    //     $this->model->updateLocation($this->model);
    // }
    // public function listcategorias() {
    //     $this->modelCategorias->listCategorias($this->model);
    // }
    // public function listpontos() {
    //     $this->modelPontos->listPontos($this->model);
    // }
   
}
