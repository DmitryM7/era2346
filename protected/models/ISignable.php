<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dmaslov
 * Date: 17.07.12
 * Time: 17:53
 * To change this template use File | Settings | File Templates.
 */
interface ISignable
{
    public function addSign($author,$inspector,$details);
}
