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

}
