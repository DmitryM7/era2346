<?php

class PersonController extends Controller
{
	public function actionIndex()
	{
             
            //$this->render('index');
            
	}
        public function actionMind() {
            $cit=array(
     'Если на клетке слона прочтёшь надпись "буйвол", не верь глазам своим.',
    'Если у тебя есть фонтан, заткни его; дай отдохнуть и фонтану.',
    'Зри в корень!',
    'Бди!',
    'Что скажут о тебе другие, коли ты сам о себе ничего сказать не можешь?',
    'Полезнее пройти путь жизни, чем всю вселенную.',
    'Никто не обнимет необъятного.',
    'Опять скажу: никто не обнимет необъятного!',
    'Плюнь тому в глаза, кто скажет, что можно обнять необъятное!',
    'Если хочешь быть счастливым, будь им.',
    'Если хочешь быть красивым, вступи в гусары.',
    'Кто мешает тебе выдумать порох непромокаемый?',
    'Взирая на солнце, прищурь глаза свои, и ты смело разглядишь в нём пятна.',
    'Усердие всё превозмогает!',
    'Бывает, что усердие превозмогает и рассудок.',
    'Если у тебя спрошено будет: что полезнее, солнце или месяц? — ответствуй: месяц. Ибо солнце светит днём, когда и без того светло; а месяц — ночью.',    
    'Где начало того конца, которым оканчивается начало?',
    'Лучше скажи мало, но хорошо.',
    'Гони любовь хоть в дверь, она влетит в окно.',
    'Легче держать вожжи, чем бразды правления.',
    'Не всё стриги, что растет.',
    'Нет на свете государства свободнее нашего, которое, наслаждаясь либеральными политическими учреждениями, повинуется вместе с тем малейшему указанию власти.'
                );


            $details = Yii::app()->db2->createCommand()
                                  ->select('u.usr_email,iss_summary')
                                  ->from('eventum_issue i')
                                  ->join('eventum_issue_user iu','i.iss_id=iu.isu_iss_id')
                                  ->join('eventum_user u', 'u.usr_id=iu.isu_usr_id')
                                  ->join('eventum_status s','s.sta_id=i.iss_sta_id')
                                  //->where('u.usr_email=:email AND s.sta_title="В РАБОТЕ"', array(':email'=>$_GET['email']))
                                  ->where('u.usr_email=:email AND s.sta_title="В РАБОТЕ"', array(':email'=>$_GET['email']))
                                  ->queryAll();
            
            $i=rand(0,count($details));
            $i2=rand(0,count($cit));
            $topic=array();
            $topic['statusinfo']="<p style='font-weight:bold;'>".$cit[$i2]."</p>";

            if (count($details)>0) {            
                $topic['statusinfo'].="<ul type='square'>";
            };
            
            $ml=min(count($details),5);
            
            for($i=0;$i<$ml;$i++) {
                   $detail=$details[$i];
                   $topic['statusinfo'].="<li>".$detail['iss_summary']."</li>";                                      
            };
            
        if (count($details)>0) {            
                $topic[statusinfo].="</ul>";
            };
        if (count($details)>5 && count($details)>0) {
                $topic['statusinfo'].="<p style='font-weight:bold;'>и т. д....</p>";
            }
            
            $json=CJSON::encode($topic);
            
            
            header('Content-type: application/json');
            echo $_GET['callback'] . ' (' . $json . ');';
            Yii::app()->end();

            //echo CJSON::encode($aaa);
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
	*/
}