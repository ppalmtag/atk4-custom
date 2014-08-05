<?php

/**
 * Created by Konstantin Kolodnitsky
 * Date: 25.11.13
 * Time: 14:57
 */
class page_index extends Page {

    public $title='Dashboard';

    function init() {
        parent::init();
        $this->add('View_Box')
            ->setHTML('Welcome to your new Web App Project. Get started by opening '.
                '<b>admin/page/index.php</b> file in your text editor and '.
                '<a href="http://book.agiletoolkit.org/" target="_blank">Reading '.
                'the documentation</a>.');

        $gr=$this->add('MyGrid');
        $gr->setModel('Test');
        $gr->addColumn('actions','actions');

    }

}

class Model_Test extends Model {
    function init(){
        parent::init();

        $this->addField('name');

        $this->setSource('Array',['John','Peter','Joe','Steve']);
    }
}

class MyGrid extends Grid {

    function init_actions($field){
        $this->columns[$field]['tpl']=$this->add('GiTemplate')->loadTemplate('column/actions');

        $m=$this->model;

        $do_flag = $this->add('VirtualPage')->set(function($p)use($m){
            $name=$m->load($_GET['id'])['name'];

            // $m->flag();

            return $p->js()->univ()->alert('You have flagged '.$name)->execute();
        });


        $this->on('click','.do-set-default')->univ()->ajaxec([$do_flag->getURL(), 'id'=>$this->js()->_selectorThis()->closest('tr')->data('id')]);
    }
    function format_actions($field){
        $this->setTDParam($field, 'width', '100');
        $this->current_row_html[$field] = $this->columns[$field]['tpl']->render();


    }
}
