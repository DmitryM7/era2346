<?php
$this->breadcrumbs=array(
	'Memployees'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List MEmployee', 'url'=>array('index')),
	array('label'=>'Create MEmployee', 'url'=>array('create')),
	array('label'=>'View MEmployee', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage MEmployee', 'url'=>array('admin')),
);
?>

<h1>Update MEmployee <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>