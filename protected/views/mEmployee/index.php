<?php
$this->breadcrumbs=array(
	'Memployees',
);

$this->menu=array(
	array('label'=>'Create MEmployee', 'url'=>array('create')),
	array('label'=>'Manage MEmployee', 'url'=>array('admin')),
);
?>

<h1>Memployees</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
