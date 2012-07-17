<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dmaslov
 * Date: 17.07.12
 * Time: 9:05
 * Class provides methods for widgets.
 */
class WdgController extends Controller
{
    //todo Так товарисч не готится, необходимо вынести в родительский класс
    private $_user=null;

    public function getUser() {
        return CJSON::decode(Yii::app()->user->userinfo,FALSE);
    }

    protected function setUser($user) {
        $this->_user=$user;
    }

    /**
     * Method marks user as present.
     * @param $whoAmI
     */
    public function actionMarkUser($whoAmI) {
        $user=MUser::model()->find('email=:email',array(':email'=>$this->user->email));
        $user->dt=new CDbExpression('NOW()');
        $user->save();

        /**
         * Return working users.
         *
         */
        $users=MUser::model()->findAll('dt>=DATE_SUB(NOW(),INTERVAL 3 MINUTE)');
        echo CJSON::encode($users);
    }

}
