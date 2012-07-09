<?php

class UiController extends Controller
{	
        var $_user=null;
        var $_jqGrid=null;
                
        public function getUser() {
            return CJSON::decode(Yii::app()->user->userinfo,FALSE);
        }    

        protected function setUser($user) {
            $this->_user=$user;
        }
        
        protected function setJqGrid($value) {
            $params=$value;
                                    
            if (isset($params['page']) 
                && isset($params['rows']) 
                && isset($params['sidx']) 
                && isset($params['sord'])
                ) {
                $this->_jqGrid=new StdClass();
                $this->_jqGrid->page=$params['page'];
                $this->_jqGrid->rows=$params['rows'];
                $this->_jqGrid->sidx=$params['sidx'];
                $this->_jqGrid->sord=$params['sord'];
                $this->_jqGrid->pagination=new CPagination();
                $this->_jqGrid->pagination->currentPage=$page['page']-1;
                $this->_jqGrid->pagination->pageSize=$params['rows'];
            };
                
        }
        
        protected function getjqGridRes($dp) {
            $res=new StdClass();
             $res->total=ceil($dp->totalItemCount / $this->JqGrid->rows);
             $res->page=$this->JqGrid->pagination->currentPage+1;
             $res->records=$dp->totalItemCount;
             $mo=$dp->getData();
                $a=array();
                $i=0;
                foreach ($mo as $key=>$value) {
                    $a[$i]['id']=$value->id;
                    $a[$i]['cell']=array($value->id,$value->opdate,$value->isclose,$value->isblock);
                    $i++;
                }
                $res->rows=$a;
                return $res;
        }
        
        protected function getJqGrid() {
           return $this->_jqGrid;
        }

        public function actionLogin() {

            $krbauth=new KerbUserIdentity($_SERVER['REDIRECT_REMOTE_USER'],'');
           
            
            if ($krbauth->authenticate()) {
                Yii::app()->user->login($krbauth);                                                
                $this->redirect('index');
            } else {
                switch ($krbauth->errorCode)
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
        
        public function actionlogout() {
            echo "До свидания!";
            Yii::app()->user->logout();
        }

        public function actionIndex()
	{               
            $statuses=MStatus::model()->findAll();
            $statuses=MStatus::model()->findAll();
            foreach ($statuses as $status) {
                $res[$status->id]=$status->name;
            }
            $this->render('index',array('statuses'=>$res,'user'=>$this->user));         
	}
        
        public function actionIndex1()
	{               
            $statuses=MStatus::model()->findAll();
            foreach ($statuses as $status) {
                $res[$status->id]=$status->name;
            }
            $this->render('index_1',array('statuses'=>$res,'user'=>$this->user));         
	}
        
        /***
         * Интерфейс просмотра/открытия/закрытия ОД
         */
       public function actionshwd() {
            $this->render('shwd');
        }
       public function actionaddAnswForm() {
            $soap = new SoapClient('http://localwww2/wf/def');
           
           $author=$this->user->un2;           
           $pid=Yii::app()->getRequest()->getPost('form_pid');
           
           $files=CUploadedFile::getInstancesByName('forms_answ');
           
           $conf=new StdClass();
           
           foreach ($files as $key=>$value) {               
               $conf->author=$author;
               $conf->inspector=$author;
               $conf->pid=$pid;
               $conf->details=base64_encode(file_get_contents($value->tempName));
               $conf->ext=$value->extensionName;
               $conf->class="docformansw";
               
echo $soap->addConfDoc(CJSON::encode($conf));
               /*if ($soap->addConfDoc(CJSON::encode($conf))) {
                   $icount++;
               };*/
           }                      
       }
       public function actionaddForm() {
           $soap = new SoapClient('http://localwww2/wf/def');
           
           $opdate=$this->date2Eng(Yii::app()->getRequest()->getPost("add_form_opdate"));
           $title=Yii::app()->getRequest()->getPost('add_form_type');
           $author=$this->user->un2;           
           
           $files=CUploadedFile::getInstancesByName('forms');
           
           foreach ($files as $key=>$value) {
               if ($soap->addDocWExtClass('1',$opdate,'10001',$author,Yii::app()->params->finspector,
                       $title,
                        base64_encode(file_get_contents($value->tempName)),$value->extensionName,"docform")) {
                   $icount++;
               }
           }
           Yii::app()->user->setFlash('succes',"$icount files were upload");
           $this->redirect('index');
        }
        public function actionaddDoc() {
            $soap = new SoapClient('http://localwww2/wf/def');            

            $opdate=$this->date2Eng(Yii::app()->getRequest()->getPost("opdate"));            
            $author=$this->user->un2;
            $inspector=Yii::app()->getRequest()->getPost("inspector");
            $icount=0;
            $files=CUploadedFile::getInstancesByName('docs');                        
            foreach ($files as $key=>$value) {
                if ($soap->addDocWExt('1',$opdate,'10000',$author,$inspector,base64_encode(file_get_contents($value->tempName)),$value->extensionName)) {
                   $icount++;   
                };
            };
            Yii::app()->user->setFlash('success',"$icount files were upload");
            $this->redirect('index');
        }
        
        public function actionUsers() {
            $this->render('users');
        }

        public function actiongetAnsw() {
                                                
            $pdoc=MDoc::model()->findByPk(Yii::app()->request->getPost('pid'));
                if ($pdoc->author=="BNK-CL") {
                    $chs="utf-8";
                }
                else {
                    if ($pdoc->class=="docform") {
                      $chs="cp1251";    
                    }
                    else {
                        $chs="ibm866";
                    }
                    
                };
            header('Content-Type:text/html;charset='.$chs);

            foreach ($pdoc->children as $doc) {
                echo "=========";
                echo "<p>Author:".$doc->author."</p>";
                echo "<p>TimeStamp:".$doc->dt."</p>";
                echo "<p>--------------------------</p>";
                echo '<div style="white-space:pre;"><tt><pre>'.str_replace(CHR(10),CHR(13).CHR(10),base64_decode($doc->details)).'</pre></tt></div>';
                echo "=========";
            };
        }

        public function actionajaxDelDoc($id) {
            $doc=MDoc::model()->findByPk($id);
            if ($this->user->un2=="ADMMDA") {
              $doc->delCurrent();
            };
            //$doc->delCurrent();
            /*if ($doc->author==$this->user->un2 || $doc->inspector==$this->user->un2) {
                if ($doc->delCurrent()) {
                    echo "Успешно удалено!";
               } else {
                   echo "Не удалось удалить!";
               };
            } else {
                echo "Запрещено удалять чужие докуметы";
            }*/
        }
        /**
         * Возвращает перечень ОД
         */
        public function actionajaxGetOd() {
            
        }

        public function actionajaxcrday()
        {
            $answ= new StdClass();            
            $answ->result=false;
            
            
            $opdate=new MOpdate();
            $opdate->opdate=Yii::app()->request->getQuery('day');
            
       try {
            $res=$opdate->save();
            
            if ($res) {                
                $answ->details=$opdate->opdate;
                $answ->result=true;    
            };                       
        }
        catch (CException $e)
        {
        
            
        };
            
            echo CJSON::encode($answ);
        }

        protected function date2Eng($date)
        {
            if (strpos($date,'.')>0) {
                    $day=substr($date,0,2);
                    $month=substr($date,3,2);
                    $year=substr($date,6,4);
                return "$year-$month-$day";
                }
                else {
                    return $date;
                }
        }
        
        public function actionajaxGetUser()
        {
            $users = MUser::model()->findAll('un2 like :search',
                                             array(':search'=>'%'.Yii::app()->request->getQuery("term").'%'));
            $res=array();
            foreach ($users as $user) {
                $res[]=$user->un2;
            }
            echo CJSON::encode($res);
        }
        
        
        public function actionajaxGetDocList()
        {
            $fltobj=new StdClass();
            $fltobj->opdate    = $this->date2Eng(Yii::app()->request->getQuery("opdate"));
            $fltobj->author    = Yii::app()->request->getQuery("author");
            $fltobj->inspector = Yii::app()->request->getQuery("inspector");
            $fltobj->expn      = Yii::app()->request->getQuery("expn");            
            $fltobj->status    = rtrim(Yii::app()->request->getQuery("status"),",");
            
            echo CJSON::encode(MDoc::getDocByFlt(CJSON::encode($fltobj)));
        }
        
        public function actionGetOdList() {
            $this->setJqGrid($this->getActionParams());
            
            if (isset($this->JqGrid)) {
                $dp=new CActiveDataProvider('MOpdate',array(
                    'criteria'=>array('order'=>$this->jqGrid->sidx." ".$this->jqGrid->sord),
                    'pagination'=>$this->JqGrid->pagination
                        ));                                
                
                echo CJSON::encode($this->getjqGridRes($dp));
                
            }
        }

        public function actionajaxGetInfo() {
            
          if (Yii::app()->request->getPost('id')!="") {
                $id=Yii::app()->request->getPost('id');
            } else {
                $id=Yii::app()->request->getQuery("id");
            };
            $doc=MDoc::model()->find(array("condition"=>"id=:id","params"=>array(":id"=>$id)));
            
            $showBig=Yii::app()->request->getPost("preview")==1?false:true;

            if (strlen($doc->details)<=5000000 || $showBig){
            
            if ($doc->fext!="pdf") {

                if ($doc->author=="BNK-CL" || $doc->author=="MCI") {
                    $chs="utf-8";
                }
                else {
                    $chs="ibm866";
                 };

                header('Content-Type:text/html;charset='.$chs);
                echo '<div style="white-space:pre;"><tt><pre>'.str_replace(CHR(10),CHR(13).CHR(10),base64_decode($doc->details)).'</pre></tt></div>';
            } else {
                header("Content-disposition: inline;filename=file.pdf");
                header('Content-Type:application/pdf');
                echo base64_decode($doc->details);
            };
            } else {
                echo "Слишком большое содержимое! Просмотрт не возможен!";
            }
                
                         
        }
        
        public function actionajaxGetInfoBase64() {

            $doc=MDoc::model()->find(array("condition"=>"id=:id","params"=>array(":id"=>Yii::app()->request->getPost('id'))));
            /*if ($doc->author=="BNK-CL") {
                $chs="utf-8";
            }
            else {
                $chs="ibm866";
            }*/
            header('Content-Type:text/html;charset=utf-8');
            echo $doc->details;
        }

        
        
       public function actiontest() {
            Yii::import('ext.adLDAP.*');
            require_once('src/adLDAP.php');

            $icount=0;
            try {
                $adldap=new adLDAP();
                
                $adldap->authenticate('dmaslov','Ty12#3123');   
                                    
                
                $users=$adldap->user()->all();
                
                foreach ($users as $key=>$value) {                    
                    $res=$adldap->user()->infoCollection($value,array('*'));

                    if ($res->userAccountControl==512 && $res->mail<>"" && $res->uid<>"") {
                        list($un,$domain) = split('@',$value);                        
                        $md5ad=md5($un.$res->mail.$res->uid);
                        

                        $user = MUser::model()->find(array('condition'=>'un=:un',
                                                           'params'=>array(":un"=>$un)
                                                           ));
                       
                        if (is_null($user)) {
                                $user = new MUser();                                
                        };                        
                        
                        $md5user=md5($user->un.$user->email.$user->un2);

                        if ($md5ad <> $md5user) {
                              $user->email=$res->mail;
                              $user->un   =$un;
                              $user->un2  =$res->uid;
                              
                              if ($user->save()) {
                                  $icount++;
                              }
                        };                        
                    };
                };                
            }
            catch (adLDAPException $e) {
                echo "Error! Can't connect to Active Directory!";
            }            
            return $icount;
        }

       public function actionajaxaddSign() {
           /** Преварительно
            *  выполняем проверку
            * "имеет ли текущий пользователь к документу"
            *  вообще какое-либо отношение!
            * Есть аналогичный метод, в модели
            * checkAndSign, но его надо хорошенько оттестить.
            */
           $pdoc=MDoc::model()->findByPk($_POST['id']);
            //if ($pdoc->isResponsible($this->user)) {
                MDoc::addSign(Yii::app()->request->getPost('id'),
                    $this->user->un2,
                    $this->user->un2,
                    base64_encode(Yii::app()->request->getPost('details'))
                );
           // };
        }

       public function actionaddSign2() {
            $doc=MDoc::model()->findByPk($_POST['id']);
            $doc->checkAndSign($this->user,$this->user->un2,base64_encode($_POST['details']));
       }

        public function actionajaxGetParentUsers() {
            
            $grid=new GridForm();
            $grid->attributes=$_GET;
            echo MUser::getUserList($grid);                        
        }
        
        public function actionajaxGetChildUsers() {
            $grid=new GridForm();
            $grid->attributes=$_GET;
            echo MUser::getChildList($grid,Yii::app()->request->getQuery('pid'));
        }
        
        public function actionajaxgetTable() {

            $page=Yii::app()->request->getQuery("page");
            $limit=Yii::app()->request->getQuery("rows");
            $sidx=Yii::app()->request->getQuery("sidx");
            $sord=Yii::app()->request->getQuery("sord");

              $status=Yii::app()->request->getQuery("status");
              $classcode=Yii::app()->request->getQuery("classcode");
            
            $fltobj=new StdClass();
            $fltobj->opdate    = $this->date2Eng(Yii::app()->request->getQuery("opdate"));
            $fltobj->author    = str_replace('*','%',Yii::app()->request->getQuery("author"));
            $fltobj->inspector = str_replace('*','%',Yii::app()->request->getQuery("inspector"));
            $fltobj->expn      = str_replace('*','%',Yii::app()->request->getQuery("expn"));
            $fltobj->status    = rtrim($status,",");
            $fltobj->limit     = $limit;
            $fltobj->sidx      = $sidx;
            $fltobj->sord      = $sord;
            $fltobj->page      = $page;
            $fltobj->classcode = rtrim($classcode,',');

            
            echo CJSON::encode(MDoc::getDocbyFlt(CJSON::encode($fltobj)));
        }
        
        public function actionajaxgetSubTable() {
            
            
            $fltobj=new StdClass();
            $fltobj->pid=Yii::app()->request->getQuery("id");                        
            echo CJSON::encode(MDoc::getChildByFlt(CJSON::encode($fltobj)));
        }

        /**
         * Помечает пользователя как присутствующего,
         * на сайте.
         * @param $whoAmI
         */
        public function actionMarkUser($whoAmI) {
            /**
             * Помечаем пользователя, как активного.
             */
            $user=MUser::model()->find('email=:email',array(':email'=>$this->user->email));
            $user->dt=new CDbExpression('NOW()');
            $user->save();

            /**
             * Возвращаем активных пользователей
             *
             */
            $users=MUser::model()->findAll('dt>=DATE_SUB(NOW(),INTERVAL 3 MINUTE)');
            echo CJSON::encode($users);
        }

        public function actionShowWall() {
            $events = Yii::app()->db2->createCommand()
                ->select('u.usr_email as email,iss_summary,iss_created_date as dt,iss_expected_resolution_date as expect')
                ->from('eventum_issue i')
                ->join('eventum_issue_user iu','i.iss_id=iu.isu_iss_id')
                ->join('eventum_user u', 'u.usr_id=iu.isu_usr_id')
                ->join('eventum_status s','s.sta_id=i.iss_sta_id')
                //->where('u.usr_email=:email AND (s.sta_title="В РАБОТЕ" OR s.sta_title="В ОЧЕРЕДИ")', array(':email'=>$this->user->email))
                ->where('s.sta_title="В РАБОТЕ" OR s.sta_title="В ОЧЕРЕДИ"')
                ->order('expect DESC')
                ->queryAll();
         echo CJSON::encode($events);
        }
	
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		/*return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);*/
            return array(
                    'accessControl'
                    );
	}

        public function accessRules() {
            return array(
                array('allow',
                      'actions'=>array('login','logout'),
                      'users'=>array('*')
                    ),
                array('allow',
                      'actions'=>array('index','shwd','ajaxGetDocList',
                                       'ajaxgetTable','getOdList','ajaxcrday','ajaxGetInfo','test',
                                       'addDoc','ajaxgetSubTable','ajaxgetUser','ajaxaddSign','ajaxGetInfoBase64','users',
                                       'ajaxGetParentUsers','ajaxGetChildUsers','addForm','addAnswForm','getAnsw','index1'
                                       ,'markUser','ShowWall'),
                      'users'=>array('@')
                     ),
                array('allow',
                       'actions'=>array(
                           'ajaxDelDoc',
                        'users'=>array('dmaslov@pirbank.ru')
                       )),
                array('deny',
                       'users'=>array('*')
                    )                                
            );            
        }
/*
	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
 * 
 */
}