<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dmaslov
 * Date: 19.07.12
 * Time: 8:57
 * To change this template use File | Settings | File Templates.
 */
interface IStatusable
{
    /**
     * Method returns current status of object.
     * @return mixed
     */
    public function getStatus();

    /**
     * Method sets new status to object.
     * @param $status
     * @return mixed
     */
    public function setStatus($status);
}
