<?php
            class fltobj
{
};

class WfController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
        public function actionShow()
        {
            //$doc=MDoc->model()->findByPk($id);
            
            

            $this->render('index');
        }

        public function actionTestSOAP()
        {
            $soap = new SoapClient('http://localwww2/wf/def');
            $soap->addCustomDoc("sdfsdf","dsfsdf");

         /*   $b=array();
            $a=new StdClass();
            $a->surname="sfsf";
            $a->name="sfsf";
            $b[]=$a;
            echo CJSON::encode($b);*/
         
//addDoc($num,$opdate,$expn,$author,$inspector,$details)
            /*$doc = MDoc::model()->findByPk(54285);
            echo "<pre><tt>".base64_decode($doc->details)."</tt></pre>";
            file_put_contents('222.txt', base64_decode($doc->details));*/
           
            //$soap = new SoapClient('http://localwww2/wf/def');
            //$soap->addDoc('6151','2012-03-14','6151','MCI','ADMMDA','dfgdgfdgfdg');
            //echo $soap->getUserEmail("");
            /*$soap->addDoc('100500','2011-11-18','10050','ADMMDA','ADMMDA',base64_encode('test'));*/
            
            
            
            /*$fltobj=new StdClass();
            $fltobj->opdate='2011-10-03';
            $fltobj->author="BNK-CL";
            $fltjson=json_encode($fltobj);
            
            
            
            $fltobj=json_decode($fltjson);                       
            
            
            if (!is_null($fltobj->author)) {
                $docs=MDoc::getDocByOpDateUser($fltobj->opdate,$fltobj->author);
            }
            else {
                if (!is_null($fltobj->inspector)) {
                 $docs=MDoc::getDocByOpDateInspector($fltobj->opdate, $fltobj->inspector);
                }                
            }
            foreach ($docs as $doc)
            {
                $res[]=CJSON::encode($doc);                
            }
            print_r($res);
            /*
            $fltjson=json_encode($fltobj);
             $fltobj=json_decode($fltjson);                       
            
            $res=array();
            if (!is_null($fltobj->author)) {
                $docs=MDoc::getDocByOpDateUser($fltobj->opdate,$fltobj->author);
            }
            else {
                if (!is_null($fltobj->inspector)) {
                 $docs=MDoc::getDocByOpDateInspector($fltobj->opdate, $fltobj->inspector);
                }                
            }
            foreach ($docs as $doc)
            {
                $res[]=CJSON::encode($doc);                
            }
            
            print_r($res);
            //echo $soap->delDocByDateEn('2011-10-31','10000');
            //$soap->addDoc("4020","2011-10-06","4020","03073EGN","03071KOA","sdfjsdfsdfdsf");
            //echo CJSON::encode(MDoc::getDocByOpDateUser('2011-10-11','02050KEU'));
            /*$aaa=MDoc::getDocByOpDateUser("2011-09-08","02050KEU");
            foreach ($aaa as $key=>$value)
            {
                echo CJSON::encode($value);
            }*/
            //echo $this->getUser('dmaslov');
            
            
        }
        
    /**
     * Возвращает электронный адрес пользователя
     * @param string $un 
     * @return integer
     * @soap
     */    
        public function getUserEmail($un)
        {
    return 123456789;
}

        /**
         * Открываем день
         * @param  date $day
         * @return bool
         * @soap
         */
        
        public function openDay($day)
        {
            return MOpdate::open($day);
        }
        /**
         *
         * @param string $day
         * @return bool
         * @soap
         */
        public function isOpenDay($day)
        {
            return MOpdate::isClose($day);
        }
        /**
         * Закрываем день
         * @param date $day
         * @return bool
         * @soap
         */
        public function closeDay($day)
        {
            return MOpdate::close($day);
        }
        
        /**
         * @param string id
         * @return string
         * @soap
         */
        
        public function getDoc($fltjson)
        {
            $fltobj=json_decode($fltjson);
            
            $doc=MDoc::model()->findByPk($fltobj->id);
            
            
            if (!is_null($doc))
            {
                return $doc->details;
            }
            else
            {
                return 0;
            };
            
        }
        
        /**
         *
         * @param string $fltjson 
         * @return array
         * @soap
         */
        public function getDocIdList($fltjson)
        {
               $fltobj=json_decode($fltjson);                       
            
            $res=array();
            foreach (MDoc::getDocByOpDateUser($fltobj->opdate,$fltobj->author) as $doc)
            {
                $res[]=$doc->id;
            }
            return $res;
        }
        /**
         *
         * @param string $fltjson
         * @return array
         * @soap 
         */
        public function getDocList($fltjson)
        {
            $fltobj=json_decode($fltjson);                       
            
            $res=array();
            if (!is_null($fltobj->author)) {
                $docs=MDoc::getDocByOpDateUser($fltobj->opdate,$fltobj->author);
            }
            else {
                if (!is_null($fltobj->inspector)) {
                 $docs=MDoc::getDocByOpDateInspector($fltobj->opdate, $fltobj->inspector);
                }                
            }
            foreach ($docs as $doc)
            {
                $res[]=CJSON::encode($doc);                
            }
            return $res;
        }
        /**
         * @param string ni
         * @return bool
         * @soap
         */
        public function addDoc1($ni)
        {
          /*$doc = new MDoc();
            
            $doc->num=$num;
            $doc->author=$author;
            $doc->controler=$controler;
            $doc->details=$details;          
            */
            return true;
        }
        
        /**
         *
         * @param string $conf 
         * @return string
         * @soap
         */
        public function addConfDoc($conf) {
            $confobj=CJSON::decode($conf,false);
            
            if (isset($confobj->pid)) {
              $pdoc=MDoc::model()->findByPk($confobj->pid);
              $confobj->num=$pdoc->num;
              $confobj->opdate=$pdoc->opdate;              
              
            };
            
            
            $doc = new MDoc();
            
            $doc->num=$confobj->num;
            $doc->opdate=$confobj->opdate;
            $doc->expn=$confobj->expn;
            $doc->author=$confobj->author;
            $doc->inspector=$confobj->inspector;            
            $doc->details=$confobj->details;            
            $doc->class=$confobj->class;            
            $doc->fext=$confobj->ext;
            $doc->pid=$confobj->pid;
            
                       
            try {
                return $doc->save();
            }
            catch (CException $e) {
                return false;
            };      
                  
        }
        /**
         * @param string num
         * @param string opdate
         * @param string expn
         * @param string author
         * @param string inspector
         * @param string details                  
         * @return integer
         * @soap
         */
        public function addDoc($num,$opdate,$expn,$author,$inspector,$details)
        {
          $doc = new MDoc();
            
            $doc->num=$num;
            $doc->opdate=$opdate;
            $doc->expn=$expn;
            $doc->author=$author;
            $doc->inspector=$inspector;
            $doc->details=$details;
            $doc->class='doc';

            if ($doc->author=="BNK-CL") {
                $doc->fext="svg";
            }
            
            try 
            {
                return $doc->save();                
            }  
            catch (CException $e) {
                return 0;
            };
            return 0;
        }

        /**
         *
         * @param string $num
         * @param string $opdate
         * @param string $expn
         * @param string $author
         * @param string $inspector
         * @param string $details
         * @param string $fext
         * @return bool 
         * @soap
         */
        public function addDocWExt($num,$opdate,$expn,$author,$inspector,$details,$fext)
        {
            $doc = new MDoc();

            $doc->class='doc';                       
            $doc->num=$num;
            $doc->opdate=$opdate;
            $doc->expn=$expn;
            $doc->author=$author;
            $doc->inspector=$inspector;
            $doc->details=$details;            
            $doc->fext=$fext;
            
            try 
            {
                return $doc->save();
            }  
            catch (CException $e) {
                return false;
            };
        }
        
        /**
         *
         * @param string $num
         * @param string $opdate
         * @param string $expn
         * @param string $author
         * @param string $inspector
         * @param string $title
         * @param string $details
         * @param string $fext
         * @param string $classcode
         * @return bool 
         * @soap
         */
        public function addDocWExtClass($num,$opdate,$expn,$author,$inspector,$title,$details,$fext,$classcode)
        {
            $doc = new MDoc();

            $doc->class=$classcode;                       
            $doc->num=$num;
            $doc->opdate=$opdate;
            $doc->expn=$expn;
            $doc->author=$author;
            $doc->inspector=$inspector;
            $doc->title=$title;
            $doc->details=$details;            
            $doc->fext=$fext;
            
            try 
            {
                return $doc->save();
            }  
            catch (CException $e) {
                return false;
            };
        }
                
        
        /**
         *
         * @param int $pid
         * @param string $author
         * @param string $inspector
         * @param string $details 
         * @return bool
         * @soap
         */
        public function addSign($pid,$author,$inspector,$details)
        {
            return MDoc::addSign($pid, $author, $inspector, $details);
        }
        
        public function delDoc($fltjson)
        {
            return MDoc::del($fltjson);                        
        }
        /**
         *
         * @param string $opdate
         * @param string $expn
         * @return string
         * @soap
         */
        public function delDocByDateEn($opdate,$expn)
        {
            $fltobj = new StdClass();
            $fltobj->opdate=$opdate;
            $fltobj->expn=$expn;
            $fltjson=json_encode($fltobj);            
            return MDoc::del($fltjson);
            
        }
        /**
         * @return array a
         * @soap
         */
        public function getopd()
        {
            $a=array("1"=>"sdfdf","2"=>"dsfdsf");
                return $a;
        }

        /**
         * @param string json
         * @param string details
         * @return integer
         * @soap
         */
        public function addCustomDoc($json,$details) {

            $obj=CJSON::decode($json,false);

            $doc = new MDoc();

            $doc->class     = $obj->taxon;
            $doc->num       = $obj->num;
            $doc->opdate    = $obj->opdate;
            $doc->expn      = $obj->expn;
            $doc->author    = $obj->author;
            $doc->inspector = $obj->inspector;
            $doc->details   = $details;
            $doc->fext      = $obj->fext;
            try
            {
                $doc->save();
                return $doc->primaryKey;
            }
            catch (CException $e) {
                return false;
            };

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
         * 
*/        
        /**
         *
         * @param  string $un 
         * @return string
         * @soap
         */
        public function getUser($un)
        {
           $res = MUser::getByUn($un);           
           return CJSON::encode($res);
        }

    	public function actions()
    	{
		// return external action classes, e.g.:
		return array(
			'def'=>array('class'=>'CWebServiceAction'),
			);
		
	}
        

}