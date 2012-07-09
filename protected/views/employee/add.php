        <?php
        Yii::app()->clientScript->registerScript(
                 'myHideEffect',
                 '$(".saveok").animate({opacity: 1.0}, 3000).fadeOut("slow");',
                  CClientScript::POS_READY);
            ?>
        <?php if(Yii::app()->user->hasFlash('addRes')): ?>

        <div class="saveok">                
                <?php echo Yii::app()->user->getFlash('addRes'); ?>
            </div>

        <?php endif; ?>
        
        <div class="form">
            <?php echo CHtml::beginForm("/employee/add"); ?>
            <?php echo CHtml::errorSummary($model); ?>
            
            <div class="row">
                <?php echo CHtml::activeLabel($model,'Фамилия'); ?>
                <?php echo CHtml::activeTextField($model,'surname'); ?>
            </div>
            
            <div class="row">
                <?php echo CHtml::activeLabel($model,'Имя'); ?>
                <?php echo CHtml::activeTextField($model,'name'); ?>                
            </div>
            
            <div class="row">
                <?php echo CHtml::activeLabel($model,'Отчество'); ?>
                <?php echo CHtml::activeTextField($model,'patronymic'); ?>                
            </div>
            
            <div class="row">
                <?php echo CHtml::activeLabel($model,'Муж/Жен'); ?>
                <?php echo CHtml::activeCheckBox($model,"sex"); ?>                
            </div>
            
                        
            <div class="row">
                <?php echo CHtml::activeLabel($model,'Работает'); ?>
                <?php echo CHtml::activeCheckBox($model,"active"); ?>                
            </div>
            
            <div class="row">
                <?php echo CHtml::activeLabel($model,'Мобильный'); ?>
                <?php echo CHtml::activeTextField($model,'mobphone'); ?>                
            </div>
            
            <div class="row">
                <?php echo CHtml::activeLabel($model,'Домашний'); ?>
                <?php echo CHtml::activeTextField($model,'homephone'); ?>                
            </div>
            
            <div class="row">
                <?php echo CHtml::activeLabel($model,'Внутренний'); ?>
                <?php echo CHtml::activeTextField($model,'inphone'); ?>                
            </div>
            
            <div class="row">
                <?php echo CHtml::activeLabel($model,'Электронная почта'); ?>
                <?php echo CHtml::activeTextField($model,'email'); ?>                
            </div>
            
            
            
            <?php echo CHtml::submitButton('Сохранить'); ?>
            <?php echo CHtml::endForm(); ?>
        </div>

