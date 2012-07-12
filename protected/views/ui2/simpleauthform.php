<?php
    Yii::app()->getClientScript()->registerPackage('frm');
?>

<div>
    <form method="get" action="<?php $this->createUrl('ui2/login') ?>">
        <dl>
            <dt><label>Имя пользователя:</label></dt>
            <dd><input type="text" model="login"></dd>

            <dt><label>Пароль:</label></dt>
            <dd><input type="text" model="pass"></dd>

           <input type="submit" name="go" value="Войти">
        </dl>
    </form>
</div>
</div>