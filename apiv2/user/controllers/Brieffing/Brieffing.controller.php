<?php

require_once MODELS . '/Brieffing/Brieffing.class.php';


class BrieffingController extends  Brieffing {

    public function __construct() {

        $this->model = New  Brieffing();
    }
   
    public function iniciar() {
        $this->model->iniciar($this->model);
    }    
    public function adicionarresiduos() {
        $this->model->saveResiduo($this->model);
    }    
    public function delete() {
        $this->model->delete($this->model);
    }  
    public function updateassinatura() {
        $this->model->updateAssinatura($this->model);
    }  
    public function updateconformidade() {
        $this->model->updateConformidade($this->model);
    }  
    public function finalizar() {
        $this->model->finalizar($this->model);
    } 
    public function listandamento() {
        $this->model->listAndamento($this->model);
    } 
    public function listall() {
        $this->model->listAll($this->model);
    } 
    public function listid() {
        $this->model->listId($this->model);
    } 
    
    
}
