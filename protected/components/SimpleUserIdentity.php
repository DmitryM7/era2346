<?php

class SimpleUserIdentity extends UserIdentity
{
    var $_id=null;


    public function authenticate()
    {
        $user=MUser::model()->find(array('condition'=>'email=:email AND pass=MD5(:pass)',
                                         'params'=>array(':email'=>$this->username,
                                                         ':pass'=>$this->password)
                                        )
                                  );
        if ($user instanceof IUser) {
            $this->_id=$user->id;
            //todo Мне кажется, что так делать нельзя. В случае если будет включено запоминать состояние, то в куки вывалится сам объект user.
            $this->setState('userinfo',CJSON::encode($user));
            $this->errorCode=self::ERROR_NONE;
        } else {
            $this->errorCode=self::ERROR_UNKNOWN_USER;
        };
       return !$this->errorCode;
    }
}
