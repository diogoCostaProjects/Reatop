<?php

require_once MODELS . '/Residuos/Residuos.class.php';


class ResiduosController extends  Residuos {

    public function __construct() {

        $this->model = New  Residuos();
    }
   
    public function savegrupo() {
        $this->model->saveGrupo($this->model);
    }    
    public function listgrupos() {
        $this->model->listGrupos($this->model);
    }    
    public function updategrupo() {
        $this->model->updateGrupo($this->model);
    }  
    public function deletegrupo() {
        $this->model->deleteGrupo($this->model);
    }  
    public function savesubgrupo() {
        $this->model->saveSubgrupo($this->model);
    }    
    public function listsubgrupos() {
        $this->model->listSubgrupos($this->model);
    }    
    public function updatesubgrupo() {
        $this->model->updateSubgrupo($this->model);
    }  
    public function deletesubgrupo() {
        $this->model->deleteSubgrupo($this->model);
    }  
    public function savetipo() {
        $this->model->saveTipo($this->model);
    }    
    public function listtipos() {
        $this->model->listTipos($this->model);
    }    
    public function updatetipo() {
        $this->model->updateTipo($this->model);
    }  
    public function deletetipo() {
        $this->model->deleteTipo($this->model);
    }  
    
    
}
