<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    
<head>    
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<link rel="stylesheet" href="/css/blueprint/screen.css" type="text/css" media="screen, projection"/>

<link rel="stylesheet" href="/css/blueprint/print.css" type="text/css" media="print"/> 

<!--[if lt IE 8]>
    <link rel="stylesheet" href="/css/blueprint/ie.css" type="text/css" media="screen, projection"/>
<![endif]-->`

</head>

<body>

<div class="container" id="page">        
	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->
        <div id="NavPanel" class="span-24"></div>
        <div>
                <?php echo $content; ?>
        </div>
        
        
	<?php //echo $content; ?>

</div><!-- page -->

</body>
</html>