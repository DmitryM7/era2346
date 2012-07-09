<?php

class EmployeeController extends Controller
{
	public function actionIndex()
	{
            $this->render('index',array());              
	}
        
function actionAdd() {           
   
   if (isset($_POST['TMEmployee'])) {               
       $ne = new TMEmployee();
       $ne->attributes=$_POST['TMEmployee'];

      try {
         if ($ne->save()) {
             Yii::app()->user->setFlash('addRes','Данные сохранены');
             $this->redirect('index'); 
           } else {
                    echo "Ошибка сохранения";
                   };                      
          }
            catch(Exception $e) {
                echo "Ошибка! Данные не сохранены!";
            };
    } //if
    else {
        $model = new TMEmployee();
        $this->render('add',array('model'=>$model,));
    }
} //actionAdd
function actionUpdate($id) {
    $model=$this->loadModel($id);
    
    if(isset($_POST['MEmployee']))
	{
            $model->attributes=$_POST['MEmployee'];

            if($model->save()) {
		$this->redirect(array('view','id'=>$model->id));
	     } else {
                $this->render('update',array('model'=>$model,));                
             };
         }
}
/**
 * Выводит XML данные с сотрудниками
 */
function actionList() {
    $nc=new CDbCriteria();
    $nc->select=array("surname","name","patronymic");
    header('Content-Type: text/xml');
    TMEmployee::getInXml($nc);
}
function actionGrid() {
    $this->render('grid');
}
function actionXml(){
    $this->layout=false;
    header('Content-Type: text/xml');
    echo "<?xml version='1.0' encoding='utf-8'?>
<rows>
   <page>1</page>
   <total>2</total>
   <records>15</records >
   <row id = '15'>
      <cell>15</cell>
      <cell><![CDATA[Test15]]></cell>
      <cell>15</cell>
      <cell><![CDATA[o]]></cell>
      <cell><![CDATA[False]]></cell>
      <cell>15/03/2005 00:00:00</cell>
      <cell>15.15</cell>
   </row>
   <row id = '14'>
      <cell>14</cell>
      <cell><![CDATA[Test14]]></cell>
      <cell>14</cell>
      <cell><![CDATA[n]]></cell>
      <cell><![CDATA[False]]></cell>
      <cell>14/02/2004 00:00:00</cell>
      <cell>14.14</cell>
   </row><row id = '13'>
      <cell>13</cell>
      <cell><![CDATA[Test13]]></cell>
      <cell>13</cell>
      <cell><![CDATA[m]]></cell>
      <cell><![CDATA[True]]></cell>
      <cell>13/01/2003 00:00:00</cell>
      <cell>13.13</cell>
   </row>
   <row id = '12'>
      <cell>12</cell>
      <cell><![CDATA[Test12]]></cell>
      <cell>12</cell>
      <cell><![CDATA[l]]></cell>
      <cell><![CDATA[False]]></cell>
      <cell>12/12/2002 00:00:00</cell>
      <cell>12.12</cell>
   </row><row id = '11'>
      <cell>11</cell>
      <cell><![CDATA[Test11]]></cell>
      <cell>11</cell>
      <cell><![CDATA[k]]></cell>
      <cell><![CDATA[True]]></cell>
      <cell>11/11/2001 00:00:00</cell>
      <cell>11.11</cell>
   </row>
   <row id = '10'>
      <cell>10</cell>
      <cell><![CDATA[Test10]]></cell>
      <cell>10</cell>
      <cell><![CDATA[j]]></cell>
      <cell><![CDATA[False]]></cell>
      <cell>10/10/2000 00:00:00</cell>
      <cell>10.1</cell>        
   </row>
   <row id = '9'>
      <cell>9</cell>
      <cell><![CDATA[Test9]]></cell>
      <cell>9</cell>
      <cell><![CDATA[i]]></cell>
      <cell><![CDATA[False]]></cell>
      <cell>09/09/1999 00:00:00</cell>
      <cell>9.9</cell>
   </row>
   <row id = '8'>
       <cell>8</cell>
       <cell><![CDATA[Test8]]></cell>
       <cell>8</cell>
       <cell><![CDATA[h]]></cell>
       <cell><![CDATA[False]]></cell>
       <cell>08/08/1998 00:00:00</cell>
       <cell>8.8</cell>
   </row>
   <row id = '7'>
       <cell>7</cell>
       <cell><![CDATA[Test7]]></cell>
       <cell>7</cell>
       <cell><![CDATA[g]]></cell>
       <cell><![CDATA[True]]></cell>
       <cell>07/07/1997 00:00:00</cell>
       <cell>7.7</cell>
   </row>
   <row id = '6'>
       <cell>6</cell>
       <cell><![CDATA[Test6]]></cell>
       <cell>6</cell>
       <cell><![CDATA[f]]></cell>
       <cell><![CDATA[True]]></cell>
       <cell>06/06/1996 00:00:00</cell>
       <cell>6.6</cell>
   </row>
   <row id = '5'>
       <cell>5</cell>
       <cell><![CDATA[Test5]]></cell>
       <cell>5</cell>
       <cell><![CDATA[e]]></cell>
       <cell><![CDATA[False]]></cell>
       <cell>05/05/1995 00:00:00</cell>
       <cell>5.5</cell>
   </row>
    <row id = '4'>
       <cell>4</cell>
       <cell><![CDATA[Test4]]></cell>
       <cell>4</cell>
       <cell><![CDATA[d]]></cell>
       <cell><![CDATA[True]]></cell>
       <cell>04/04/1994 00:00:00</cell>
       <cell>4.4</cell>
   </row>
   <row id = '3'>
       <cell>3</cell>
       <cell><![CDATA[Test3]]></cell>
       <cell>3</cell>
       <cell><![CDATA[c]]></cell>
       <cell><![CDATA[False]]></cell>
       <cell>03/03/1993 00:00:00</cell>
       <cell>3.3</cell>
   </row>
   <row id = '2'>
       <cell>2</cell>
       <cell><![CDATA[Test2]]></cell>
       <cell>2</cell>
       <cell><![CDATA[b]]></cell>
       <cell><![CDATA[False]]></cell>
       <cell>02/02/1992 00:00:00</cell>
       <cell>2.2</cell>
   </row>
   <row id = '1'>
       <cell>1</cell>
       <cell><![CDATA[Test1]]></cell>
       <cell>1</cell>
       <cell><![CDATA[a]]></cell>
       <cell><![CDATA[True]]></cell>
       <cell>01/01/1991 00:00:00</cell>
       <cell>1.1</cell>
   </row>
</rows>
";
}
function loadModel() {
    $model=MEmployee::model()->findByPk($id);

    if($model===null) throw new CHttpException(404,'The requested page does not exist.');

    return $model;
}

function translitIt($str) 
{
    $tr = array(
        "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
        "Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
        "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
        "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
        "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
        "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
        "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
        "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
        "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
        "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
        "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
        "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
        "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"
    );
    return strtr($str,$tr);
}


};