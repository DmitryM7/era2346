<?php

class GridForm extends CFormModel {
    public $page;
    public $rows;
    public $sidx;
    public $sord;
    
    
    public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('page, rows', 'required'),
			array('page, rows','numerical'),
                        array('sidx,sord','safe')
		);
	}
}