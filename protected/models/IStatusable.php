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
    public function nextStatus($action);
}
