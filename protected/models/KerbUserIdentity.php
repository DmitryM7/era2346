<?php

class KerbUserIdentity extends UserIdentity
{
    private $_id;
    
    /**
     * Выделяет из электронной почты имя
     * пользователя.
     * 
     * @return string 
     */
    private function getUser() {
        $unarray=split('@',$this->username);
        return $unarray[0];
    }
    
    public function authenticate() {
        if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9]+[a-zA-Z0-9_-]*)+$/",$this->username))
            {
                $un=$this->getUser();                
        
                $user=MUser::model()->find(array(
                                        "condition"=>"un=:un",
                                        "params"=>array(":un"=>$un)
                                            )
                                       );

                            if ($user instanceof IUser) {
                                        $this->_id=$user->id;
                                        $this->errorCode=self::ERROR_NONE;
                                        $this->setState('userinfo',CJSON::encode($user));
                            }
                            else {
                                    $this->errorCode=self::ERROR_UNKNOWN_USER;
                                 };
     } else {
         $this->errorCode=self::ERROR_USERNAME_INVALID;
     };
      return !$this->errorCode;
   }
}