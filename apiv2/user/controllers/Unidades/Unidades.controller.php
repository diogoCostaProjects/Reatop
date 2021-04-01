<?php

require_once MODELS . '/Unidades/Unidades.class.php';


class UnidadesController extends  Unidades {

    public function __construct() {

        $this->model = New  Unidades();
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
    public function listall() {
        $this->model->listAll($this->model);
    }     
    public function listid() {
        $this->model->listId($this->model);
    }  
    
}
