<?php

//request: domain/internal/index/index 或 i.domain/index/index

class IndexController extends AbstractController {
    
    public function indexAction(){
        $model = new SampleModel();
        $this->data['name1'] = $model->selectSample();
        $this->data['name2'] = Comm_Context::param('name', 'asd');
        
        $this->jsonResult();
        return $this->end();  
    }
    
}