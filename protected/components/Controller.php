<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/main',
	 * meaning using a single column layout. See 'protected/views/layouts/main.php'.
	 */
	public $layout='//layouts/main';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	//public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            array(
                'application.components.ActionLog'
            ),
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    //通用的commoncgi请求
    //对commoncgi发起请求更新缓存
    protected function requestCommoncgi($commonCgiKey,$arrData){
        $commonCgiUrl = Yii::app()->params['commoncgi'][$commonCgiKey];
        $commonCgiData = $arrData;
        Log::model()->logFile($commonCgiKey,$commonCgiUrl);
        Log::model()->logFile($commonCgiKey,json_encode($commonCgiData));
        $r=Https::getPost($commonCgiData, $commonCgiUrl);
        Log::model()->logFile($commonCgiKey,json_encode($r));
        $r=json_decode($r,true);

        if($r['ret']!=0 || $r['sub']!=0){
            $outData['msg']=$r['msg'];
        }else{
            $outData=[
                'succ'=>1,
                'msg'=>'成功'
            ];
        }
        return json_encode($outData);
    }

    //通用上传方法
    /**
     * @param $Path  路径，一般都是以\uploads来开头
     * @param $tmpFileName  临时文件名
     * @param $fileName   文件名
     * @return bool|string
     */
    protected function commonUploadFile($Path,$tmpFileName, $fileName)
    {
        //本地文件路径,上传后要将文件移动到的地方
        $localPath = dirname(Yii::app()->basePath) . $Path;
        if (!is_dir($localPath)) {
            mkdir($localPath, 0777, true);//第三个参数，递归创建
        }
        $extension = pathinfo($fileName);
        $extension = $extension['extension'];
        $fileName = md5(file_get_contents($tmpFileName));
        $fileName = $fileName . '.' . $extension;//文件名:  md5(文件内容).类型
        if (move_uploaded_file($tmpFileName, $localPath . '/' . $fileName)) {
            return $Path . '/' . $fileName;
        } else {
            return false;
        }
    }
}