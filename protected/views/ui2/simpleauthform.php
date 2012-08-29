<?php
    Yii::app()->getClientScript()->registerPackage('frm');
?>
<div>Внимание! Система находится в режиме защиты от сбоев!</div>
 <div class="authpanel simple">
    <form method="get" action="<?php echo $this->createUrl('ui2/login') ?>">
        <dl>
            <dt><label>Имя пользователя:</label></dt>
            <dd><input type="text" model="login" name="login"></dd>

            <dt><label>Пароль:</label></dt>
            <dd><input type="password" model="pass" name="pass"></dd>

           <input type="submit" name="go" value="Войти">
        </dl>
    </form>
 </div>
</div>