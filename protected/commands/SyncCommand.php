<?php
class SyncCommand extends CConsoleCommand{

    public function getPath() {
        return "/home/imp-exp/docum";
    }
    
    public function getSPath() {
        return "/home/imp-exp/docum_s";
    }
        
    /**
         * Процедура импорта польователей из АД
         * Берем пользоателей из АД, 
         * Считаем хэш md5 его реквизитов.
         * Ищем пользователя с таким же именем
         * в users, если пользователь сущ. в таблице
         * users, то сравниваем хэши.
         * Если хэши не совпадают, то
         * делаем update.
         * Если пользователя нет, то добавляем его.
         * 
         */
    
    protected function doUserSync() {
            Yii::import('ext.adLDAP.*');
            require_once('src/adLDAP.php');

            $icount=0;
            try {
                $adldap=new adLDAP();
                
                $adldap->authenticate('dmaslov','Pa$$word3');
                                    
                
                $users=$adldap->user()->all();
  
                foreach ($users as $key=>$value) {                    
                    $res=$adldap->user()->infoCollection($value,array('*'));
                    
                    if ($res->userAccountControl==512 && $res->mail<>"" && $res->uid<>"") {
                        list($un,$domain) = split('@',$value);                        
                        $md5ad=md5($un.$res->mail.$res->uid.$res->telephoneNumber.$res->title);
                        

                        $user = MUser::model()->find(array('condition'=>'un=:un',
                                                           'params'=>array(":un"=>$un)
                                                           ));
                       
                        if (is_null($user)) {
                                $user = new MUser();                                
                        };                        
                        
                        $md5user=md5($user->un.$user->email.$user->un2.$user->phone.$user->position);

                        if ($md5ad <> $md5user) {
                              $user->email    = $res->mail;
                              $user->un       = $un;
                              $user->un2      = $res->uid;
                              $user->phone    = $res->telephoneNumber;
                              $user->position = $res->title;

                              list($user->surname,$user->name)=explode($res->name," ");
                              
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

    /**
     * Производит синхронизация таблицу
     * пользователей с AD.
     */        
    public function actionUsers() {                                    
           $res=$this->doUserSync();
           echo "$res records were updated!\n";        
    }
    
    /**
     * Синхронизируем файлы на диске с файлами в БД.
     * 1. По всем отрытым операционным дня:
     *   2. Очищаем папку с файлами;
     *   3. Получаем данные из БД;
     *   4. Пишем на диск;
     * 
     */
    /**
     *
     * @param string $type
     * @param date $day 
     */
    public function actionfiles($type,$day=null,$month=null,$year=null) {               
        switch ($type) {
            case "unsave":
                     $days=MOpdate::getUnSaveDays();
                    break;
            case "oneday":
                    $days=MOpdate::model()->findAll(array('condition'=>"opdate=:opdate",'params'=>array(":opdate"=>$day)));                    
                    break;
            case "onemonth":
                    $days=MOpdate::model()->findAll(array('condition'=>'MONTH(opdate)=:month AND YEAR(opdate)=:year',
                                                          'params'=>array(':month'=>$month,
                                                                           ':year'=>$year)
                                            ));
                    break;
        };        
        $this->_files($days);
    }
        
    
    protected function _files($opdates) {
        $days=$opdates;
        $path=$this->path;
       
        foreach ($days as $day) {
           list($y,$m,$d)=split('-',$day->opdate);
           $path=$this->path."/".$y."/".$m."/".$d."/md/%inspector%/%author%(%expn%)(%id%).%fext%";
           $spath=$this->spath."/".$y."/".$m."/".$d."/%st%/%author%(%expn%)(%pid%).%fext%.sgn";
           try {
           $docs=MDoc::getDocByOpDate($day->opdate);

           foreach ($docs as $doc) {     
               $path2save=str_replace(array("%inspector%","%author%","%expn%","%id%","%fext%"),
                                      array($doc['inspector'],$doc['author'],$doc['expn'],$doc['id'],$doc['fext']),
                                      $path);
                                             
               //echo $path2save."\n";
               $docinfo=MDoc::model()->findByPk($doc['id']);               

               $this->mkdirp($path2save);
               file_put_contents($path2save,base64_decode($docinfo->details));

               
               foreach ($docinfo->children as $child) {
                   $st=1;
                   if ($child->author!=$docinfo->author) {
                       $st=2;
                   }                    
               $spath2save=str_replace(array('%st%','%inspector%','%author%','%expn%','%pid%','%pid%','%fext%'),
                                       array($st,$docinfo->inspector,$docinfo->author,$docinfo->expn,$child->pid,$child->id,$docinfo->fext),
                                       $spath
                                        );
                $this->mkdirp($spath2save);
                file_put_contents($spath2save,base64_decode($child->details));
               
           };
           }; 
            //$day->issave=1;
            $day->save();
           } /* END TRY */
           catch (CException $e) {
               echo "Can't save one or more days\n";
           };
       }; /* END FOREACH $days */
    }

    public function actionTest() {
        echo $this->mkdirp('/home/maslov/111.txt')."\n";
    }
    /**
     * Создает каталог по файлу.
     * Ожидается, что последним будет файл
     * @param string $path2file 
     */
    protected function mkdirp($path2file) {
        $patha=explode('/',$path2file);
        $path="";        
        
        for ($i=0;$i<count($patha)-1;$i++) {            
            $path.=$patha[$i]."/";
        }
        
        if (!is_dir($path)) {
          mkdir($path,0777,true);
        };
    }
}
?>

