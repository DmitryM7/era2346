<?php

class Ui2Controller extends Controller
{
    private $_user=null;

    public function getUser() {
    return CJSON::decode(Yii::app()->user->userinfo,FALSE);
}

    protected function setUser($user) {
        $this->_user=$user;
    }

    public function actionSimpleAuthForm() {
        $this->render('simpleauthform');
    }
    public function actionLogin() {

        switch (SysClass::getSetting('auth','type','simple')) {
            case "simple":
               if (!(empty($_GET['login']) && empty($_GET['pass']))) {
                 $auth=new SimpleUserIdentity($_GET['login'],$_GET['pass']);
               } else {
                   // Action didn't show form or user didn't fill required fields.
                   $this->redirect('simpleauthform');
               };
            break;
            case "ad":
                $auth=new KerbUserIdentity($_SERVER['REDIRECT_REMOTE_USER'],'');
            break;
        };
        if ($auth->authenticate()) {
            Yii::app()->user->login($auth);
            $this->redirect(Yii::app()->user->getReturnUrl('index'));
        } else {
            switch ($auth->errorCode)
            {
                case KerbUserIdentity::ERROR_UNKNOWN_USER:
                    echo "Пользователя нет во внутренней БД. Проведите синхронизация с AD.";
                    break;
                case KerbUserIdentity::ERROR_USERNAME_INVALID:
                    echo "Формат имени пользователя ошибочный. Что Вы мне подсунули???";
                    break;
            };
        };
    }
    public function actionLogOut() {
        Yii::app()->user->logout();
        $this->redirect('index');
    }
	public function actionIndex()
	{
		$this->render('index',array(
                                    'user'=>$this->user
                                    ));
	}

    public function actionGetDetailsInBase64() {
        $doc=MDoc::model()->find(array("condition"=>"id=:id","params"=>array(":id"=>Yii::app()->request->getPost('id'))));
        header('Content-Type:text/html;charset=utf-8');
        echo $doc->details;
    }


    public function actionWriteSign() {
        /** Преварительно
         *  выполняем проверку
         * "имеет ли текущий пользователь к документу"
         *  вообще какое-либо отношение!
         * Есть аналогичный метод, в модели
         * checkAndSign, но его надо хорошенько оттестить.
         */
        $pdoc=MDoc::model()->findByPk($_POST['id']);

        if ($pdoc->isResponsible($this->user)) {
         MDoc::addSign(Yii::app()->request->getPost('id'),
               $this->user->un2,
               $this->user->un2,
               base64_encode(Yii::app()->request->getPost('details'))
           );
        };
    }
    public function filters()
    {
        return array(
            'accessControl'
        );
    }
    public function accessRules() {
      return array(
                array('allow',
                      'users'=>array('@')
                     ),
                array('allow',
                      'actions'=>array('login','simpleauthform','logout'),
                      'users'=>array('*')),
            array('deny',
                  'users'=>array('*')
                 )
      );
    }

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
    */
	/*
    public function actions()
	{
		// return external action classes, e.g.:

		return array(
			'simpleauthform'=>'application.controllers.actions.SimpleAuthForm'
		);
	}
     */
}