<?php

class DocsController extends Controller
{
    public function getCurrentUser() {
        return "ADMMDA";
    }
    public function getUser() {
        return CJSON::decode(Yii::app()->user->userinfo,FALSE);
    }

	public function actionIndex()
	{
		$this->render('index');
	}
    protected function date2Eng($date)   {
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

    /** Добавляем визы */
    public function actionAddPermitNote($pid)  {
        $this->addNote($pid,MDoc::visaPermit);
    }
    public function actionAddDenyNote($pid) {
        $this->addNote($pid,MDoc::visaDeny);
    }

    /** Добавляем информационные сообщения */
    public function actionStickNotice($pid) {
        $a=new StdClass();
        $a->type="Success";
        $a->text="ВСЕ ОК!";
        $this->addNote($pid,MDoc::stickNotice);
        echo CJSON::encode($a);
    }
    public function actionStickAlert($pid) {
        $this->addNote($pid,MDoc::stickAlert);
    }
    public function actionStickError($pid) {
        $a=new StdClass();
        $a->type="Success";
        $a->text="ВСЕ ОК!";
        $this->addNote($pid,MDoc::stickError);
        echo CJSON::encode($a);
    }


    public function addNote($pid,$classCode) {
        $pdoc=MFinDoc::model()->findByPk($pid);

        $newdoc=new MShortInfo();
        $newdoc->opdate=$pdoc->opdate;
        $newdoc->taxon=$classCode;
        $newdoc->author=$this->currentUser;
        $newdoc->pid=$pdoc->id;
        $newdoc->title=$_GET['title'];
        $newdoc->details=$_GET['details'];
        $newdoc->save();
    }

    public function actionGetChildren($pid,$type="*") {
        $res=array();
        $pdoc=MDoc::model()->findByPk($pid);
        foreach ($pdoc->getChildren($type) as $key=>$value) {
            if ($value->taxon(0)=='note') {
                $nc=new StdClass();
                $nc->id=$value->id;
                $nc->author=$value->author;
                $nc->dt=$value->dt;
                $nc->title=$value->title;
                $nc->details=$value->details;
                $nc->taxon=$value->class;
                $nc->isdelete=$value->isdelete;
                $res[]=$nc;
            }
        }
         echo CJSON::encode($res);
    }

    public function actionGetChildrenSign($pid,$type="*") {
        $res=array();
        $pdoc=MDoc::model()->findByPk($pid);
        foreach ($pdoc->getChildren($type) as $key=>$value) {
            if ($value->taxon(0)=='doc') {
                $nc=new StdClass();
                $nc->id=$value->id;
                $nc->author=$value->author;
                $nc->dt=$value->dt;
                $nc->title=$value->title;
                $nc->details=$value->details;
                $nc->taxon=$value->class;
                $nc->isdelete=$value->isdelete;
                $res[]=$nc;
            };
        };
        echo CJSON::encode($res);
    }

    public function actionDel($id) {
        $res=new StdClass();
        $res->type="success";
        $res->text="Запись успешно удалена!";
        $doc=MDoc::model()->findByPk($id);
        $doc->delOnlyIfChild($id);
       echo CJSON::encode($res);
    }

    public function actionTakeAuthor($id) {
        $doc=MFinDoc::model()->findByPk($id);
        $doc->takeAuthor($this->user)
            ->save();
    }
    public function actionTakeInspector($id) {
        $doc=MFinDoc::model()->findByPk($id);
        $doc->takeInspector($this->user)
            ->save();
    }

    public function actionShow() {

        if (Yii::app()->request->getPost('id')!="") {
            $id=Yii::app()->request->getPost('id');
        } else {
            $id=Yii::app()->request->getQuery("id");
        };
        $doc=MDoc::model()->find(array("condition"=>"id=:id","params"=>array(":id"=>$id)));



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
    }

    /**************************
            НОВЫЕ МЕТОДЫ
     *************************/

    /**
     * Returns list of documents by filter.
     */
    public function actionByFilter() {

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
*//*
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