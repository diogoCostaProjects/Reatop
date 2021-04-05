<?php

require_once MODELS . '/Planejamentos/Planejamentos.class.php';


class PlanejamentosController extends  Planejamentos {

    public function __construct() {

        $this->model = New  Planejamentos();
    }
   
    public function adicionar() {
        $this->model->save($this->model);
    }     
    public function delete() {
        $this->model->delete($this->model);
    }  
    public function update() {
        $this->model->update($this->model);
    }  
    public function listallgestor() {
        $this->model->listAllGestor($this->model);
    }     
    public function listid() {
        $this->model->listId($this->model);
    }  
    
}
