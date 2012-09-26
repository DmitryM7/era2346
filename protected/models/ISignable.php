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
    /**
     * Save sign to database.
     * @abstract
     * @param $author
     * @param $inspector
     * @param $details
     * @return mixed
     */
    public function addSign($author,$inspector,$details);


    /**
     * Method returns data that should be signed.
     * @abstract
     * @return String
     */
    public function getData2Sign();

    /*
    * Method returns all saved signs.
    */
    public function getSigns();
    public function hasErrorSign();
    public function hasSign($author,$inspector);
}
