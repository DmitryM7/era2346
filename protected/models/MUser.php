<?php
class MUser extends User
{
        public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}    
        public static function getByUn($un)
        {
            $cr=new CDbCriteria;
            $cr->condition='un=:un';
            $cr->params=array(':un'=>$un);
            return MUser::model()->find($cr);
        }
        
        public function appoint($userlist) {
            
            foreach ($userlist as $user) {
                try {
                    $command=Yii::app()->db->createCommand()->insert('is_boss_for',
                                                                     array('parent'=>$this->id,
                                                                           'child'=>$user)
                                                                    );
                }
                catch (CException $e) {
                    // Либо не удалось вставить из-за проблем с БД или из-за того,
                    // что запись существует
                };
            }
        }
        
        public function relieve($userlist) {
         foreach ($userlist as $user) {
            try {
                $command=Yii::app()->db->createCommand()->delete('is_boss_for',
                                                                 'parent=:parent AND child=:child',
                                                                 array(':parent'=>$this->id,':child'=>$user)
                                            );
            }
            catch (CException $e) {
            
            };        
         };
        }
        
        public static function getUserList($grid) {
            $dp=new CActiveDataProvider('MUser',array(
                                                      'pagination'=>array('pageSize'=>$grid->rows)   
            ));
            

            $res = new StdClass();
            $res->page=$grid->page;
            $res->records=$dp->totalItemCount;
            $res->total=ceil($res->records / $grid->rows);
            
            $users=$dp->getData();
            
            foreach ($users as $i=>$user) {
                $res->rows[$i]['id'] = $user['id'];
                $res->rows[$i]['cell'] = array($user['id'],$user['email'],$user['name'],$user['patronymic']);

            };
            return CJSON::encode($res);
        }
        
        public static function getChildList($grid,$pid) {
           
           $dp=new CActiveDataProvider('MUser',array('criteria'=>array(
                                                                        'condition'=>'t.id=:id',
                                                                        'params'=>array(':id'=>$pid),                                                                      
                                                                        'with'=>array('subordinate')
                                                          ),
                                                      'pagination'=>array('pageSize'=>$grid->rows)   
            ));
            
            $res = new StdClass();
            $res->page=$grid->page;
            $res->records=$dp->totalItemCount;
            $res->total=ceil($res->records / $grid->rows);
            
            $users=$dp->getData();
            
            foreach ($users as $i=>$user) {                
                foreach ($user->subordinate as $subordinate) {
                    $res->rows[$i]['id'] = $subordinate->id;
                    $res->rows[$i]['cell'] = array($subordinate->id,$subordinate->email,$subordinate->name,$subordinate->patronymic); 
                }                                
            };
            
            return CJSON::encode($res);
           
       }

        public function byUn2($un2) {
            $this->getDbCriteria()->mergeWith(array(
                'criteria'=>'un2=:un2',
                'params'=>array(
                                ':un2'=>$un2
                               ),
                ));
            return $this;
        }
        
}


?>
