<?php
$this->breadcrumbs=array(
	'Memployees'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List MEmployee', 'url'=>array('index')),
	array('label'=>'Manage MEmployee', 'url'=>array('admin')),
);
?>

<h1>Create MEmployee</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>