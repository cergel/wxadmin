<?php

class IconController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/main';

    public $movieNavLimit = '32';//电影导航的图标大小限制，单位KB

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            array( // 操作日志过滤器
                'application.components.ActionLog'
            ),
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'loading_index', 'movie_index', 'perform_index', 'loading_pic', 'loading_pre', 'loading_save', 'movieUpload', 'movieSave'),
                'users' => array('@'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }


    /**
     * 入口分类列表
     */
    public function actionIndex()
    {
        $this->render('index');
    }

    /**
     * 修改Loding的页面
     */
    public function actionLoading_index()
    {
        $arrData['word']='';
        $arrData['pic']=0;
        $jsonFile=Yii::app()->params['weixin_icon']['target_dir'].'/loading.json';
        if(file_exists($jsonFile)){
            $strJson=file_get_contents($jsonFile);
            $objJson=json_decode($strJson);
            $arrData['word']=$objJson->word;
            $arrData['pic']=$objJson->pic;
        }
        $this->render('loading_index',$arrData);
    }

    /**
     * 修改电影票导航图标
     */
    public function actionMovie_index()
    {
        $arrData = array();
        //先将各值初始化
        $arrData['movieNavLimit'] = $this->movieNavLimit;//图片大小
        $arrData['uploadFlag'] = 0;
        $arrData['movieStr'] = '';
        $arrData['cinemaStr'] = '';
        $arrData['findStr'] = '';
        $arrData['myStr'] = '';
        $arrData['checkColor'] = '';
        $arrData['noCheckColor'] = '';
        //如果存在json文件，则把它的内容拿出来，显示在页面的框框里

        $jsonFilePath=Yii::app()->params['weixin_icon']['target_dir'].'/movieNav.json';

        if (file_exists($jsonFilePath)) {
            $strJson=file_get_contents($jsonFilePath);
            $jsonObj=json_decode($strJson);
            foreach($jsonObj as $k=>$v){
                $arrData[$k]=$v;
            }
        }
        $arrTemplateData=$arrData;
        $arrTemplateData['movieNavLimit']=$this->movieNavLimit;
        #var_dump($arrTemplateData);die;
        $this->render('movie_index', $arrTemplateData);
    }

    /**
     * 点击上传按钮保存图片的接口
     */
    public function actionLoading_pic()
    {
        //定义路径信息
        $filePath = dirname(Yii::app()->basePath) . '/uploads/weixin_icon_tmp/loading';
        //上传后的文件路径
        $fileUrl = '/uploads/weixin_icon_tmp/loading/loading.gif';
        //定义返回值
        $return = array('success' => '', 'msg' => '', 'url' => '');

        if (!is_dir($filePath)) {
            @mkdir($filePath, 755, true);
        }
        if ($_FILES['UpLoadFile']['size'] / 1024 > 32) {
            return array('success' => '0', 'msg' => '上传失败,文件大小超过' . 32 . 'K');
        }
        if (move_uploaded_file(@$_FILES['UpLoadFile']['tmp_name'], $filePath . '/loading.gif')) {
            $return = array('success' => '1', 'msg' => '上传成功', 'url' => $fileUrl);
        } else {
            $return = array('success' => '0', 'msg' => '上传失败', 'url' => '');
        }

        echo json_encode($return);
    }

    /**
     * 电影票导航图标上传接口
     */
    public function actionMovieUpload()
    {
        //定义路径信息
        $filePath = dirname(Yii::app()->basePath) . '/uploads/weixin_icon_tmp';
        //上传后的文件路径
        $fileUrl = '/uploads/weixin_icon_tmp/movieNav.png';
        //定义返回值
        $return = array('success' => '', 'msg' => '', 'url' => '');

        if (!is_dir($filePath)) {
            @mkdir($filePath, 755, true);
        }
        if ($_FILES['UpLoadFile']['size'] / 1024 > $this->movieNavLimit) {
            return array('success' => '0', 'msg' => '上传失败,文件大小超过'.$this->movieNavLimit.'K');
        }
        if (move_uploaded_file(@$_FILES['UpLoadFile']['tmp_name'], $filePath . '/movieNav.png')) {
            $return = array('success' => '1', 'msg' => '上传成功', 'url' => $fileUrl);
        } else {
            $return = array('success' => '0', 'msg' => '上传失败', 'url' => '');
        }
        echo json_encode($return);
    }

    /**
     * 点击预览按钮临时生成css文件
     */
    public function  actionLoading_pre()
    {

    }

    /**
     * 点击保存按钮最后上传
     */
    public function actionLoading_save()
    {

        //$strUrl=Yii::app()->params['weixin_icon']['target_url'].'/loading.gif';  //测试时用的图片地址
        $strUrl = Yii::app()->params['weixin_icon']['final_url'] . '/loading.gif'; //todo 上线前修改为此地址
        $strWord = $_POST['word'];
        $intPic=$_POST['pic'];//0,1标识是否有图片
        $len = mb_strlen($strWord, 'utf8');
        if ($len > 14) {//最多14个字符
            $return = array('success' => '0', 'msg' => '上传失败，文案字数太多');
            echo json_encode($return);
            die;
        }
        $return = array('success' => '', 'msg' => '');
        //写入图片文件
        $cdnDir = Yii::app()->params['weixin_icon']['target_dir'];
        if (!is_dir($cdnDir)) {
            @mkdir($cdnDir, 755, true);
        }

        //生成json文件
        $jsonPath=$cdnDir.'/loading.json';
        $arrJson['word']=$strWord;
        $arrJson['pic']=$intPic;
        $strJson=json_encode($arrJson);
        file_put_contents($jsonPath,$strJson);

        //生成css串逻辑
        $strDisplay = '';//如果没传文案，要增加dispay:none
        if (empty($strWord)) {
            $strDisplay = "display: none!important;\n";
        }
        $strCss = "/**loading-begin**/\n.loading>div:before{\nbackground-image: url(" . $strUrl . '?' . date("y.m.d") . ")!important;\n}\n.loading>div:after{\n" . $strDisplay . "content: \"" . $strWord . "\"!important;\n}\n/**loading-end**/\n";
        $strPreg='/(\/\*\*loading-begin\*\*\/)(.*?)(\/\*\*loading-end\*\*\/\\n)/s';
        $css_r = $this->createMovieNavCssFile($strCss,$strPreg);


        $pic = file_get_contents($filePath = dirname(Yii::app()->basePath) . '/uploads/weixin_icon_tmp/loading/loading.gif');
        $pic_r = file_put_contents($cdnDir . '/loading.gif', $pic);
        if ($css_r && $pic_r) {
            $return = array('success' => '1', 'msg' => '保存成功');
        } else {
            $return = array('success' => '0', 'msg' => '保存失败,文件无法写入');
        }
        echo json_encode($return);
    }


    /**
     * 此函数为电影票导航修改,点击保存按钮后提交到地方
     * 入参：
     * uploadFlag,上传标志
     * movieStr：文案-电影
     * cinemaStr,文案-影院
     * findStr,文案-发现
     * myStr,文案-我的
     * checkColor,选中颜色
     * noCheckColor未选颜色
     */
    public function actionMovieSave()
    {
        $arrInput = array();
        $arrInput['uploadFlag'] = $_POST['uploadFlag'];

        $arrInput['checkColor'] = $_POST['checkColor'];
        $arrInput['noCheckColor'] = $_POST['noCheckColor'];

        $arrInput['movieStr'] = $_POST['movieStr'];
        $arrInput['cinemaStr'] = $_POST['cinemaStr'];
        $arrInput['findStr'] = $_POST['findStr'];
        $arrInput['myStr'] = $_POST['myStr'];

        $validationResult=$this->movieSaveValidation($arrInput);
        if($validationResult['success']==0){//如果参数验证没过，下面的逻辑就不继续了
            echo json_encode($validationResult);
            die;
        }
        //将传入的arrInput转成json文件存入磁盘
        $jsonInput=json_encode($arrInput);
        $jsonFilePath=Yii::app()->params['weixin_icon']['target_dir'].'/movieNav.json';
        file_put_contents($jsonFilePath,$jsonInput);//将json写入文件

        //生成本次的css内容
        $strCss = $this->createMovieNavCssStr($arrInput);
        $strPreg = '/(\/\*\*movieNav-begin\*\*\/)(.*?)(\/\*\*movieNav-end\*\*\/\\n)/s';//模式修正符用s,将全部字符串视为一行
        //替换或生成原有文件的css内容
        $boolFileResult = $this->createMovieNavCssFile($strCss,$strPreg);
        //如果图片有更新，要移动原来的图片
        $boolPicResult = true;
        if ($arrInput['uploadFlag']) {
            $boolPicResult = $this->moveMovieNavPic();
        }
        if ($boolFileResult && $boolPicResult) {
            $result = array('success' => '1', 'msg' => '保存成功');
        } else {
            $result = array('success' => '0', 'msg' => '保存失败');
        }

        echo json_encode($result);
    }

    /**
     * 此函数为actionMovieSaveValidation的参数验证
     * 入参：
     * uploadFlag,上传标志
     * movieStr：文案-电影
     * cinemaStr,文案-影院
     * findStr,文案-发现
     * myStr,文案-我的
     * checkColor,选中颜色
     * noCheckColor未选颜色
     */
    private function movieSaveValidation($arrInput){
        $return=array('success'=>1,'msg'=>'验证成功');
        //对图片检测
        $picPath=dirname(Yii::app()->basePath) . '/uploads/weixin_icon_tmp/movieNav.png';
        if($arrInput['uploadFlag']==1 && file_exists($picPath)){
            $imgInfo=getimagesize($picPath);
            if($imgInfo[0]!=114){
                $return['success']=0;
                $return['msg']='保存失败，图片宽度限定114px';
                return $return;
            }
            if($imgInfo[1]!=208){
                $return['success']=0;
                $return['msg']='保存失败，图片高度限定208px';
                return $return;
            }
            if($imgInfo[2]!=3){
                $return['success']=0;
                $return['msg']='保存失败，图片类型必须为png';
                return $return;
            }
        }

        //对输入的值检测
        $arrHex=array($arrInput['checkColor'],$arrInput['noCheckColor']);
        $pregHex="/^[0-9a-fA-F]+$/";
        foreach($arrHex as $k=>$v){
            if(empty($v)){
                continue;//如果是空的，就直接跳过，不用再做后面的验证
            }
            if(strlen($v)!=6){
                $return['success']=0;
                $return['msg']='hex颜色值为6位';
                break;
            }
            if(!preg_match($pregHex,$v)){
                $return['success']=0;
                $return['msg']='请输入正确的hex值，无需加#号';
                break;
            }
        }
        return $return;
    }

    /**
     * 将传入的参数拼装成css
     * @param $arrInput  传入的各个参数
     */
    private function createMovieNavCssStr($arrInput)
    {
        $str = '';
        $strUrl = Yii::app()->params['weixin_icon']['final_url'] . '/movieNav.png';
        //拼装url
        if ($arrInput['uploadFlag'] == 1) {
            $str.=$this->createMovieNavPicStr($strUrl);
        }
        //拼装未选中颜色
        if (!empty($arrInput['noCheckColor'])) {
            $str.=$this->createMovieNavNoCheckStr($arrInput['noCheckColor']);
        }
        //拼装选中颜色
        if (!empty($arrInput['checkColor'])) {
            $str.=$this->createMovieNavCheckStr($arrInput['checkColor']);
        }
        //拼装四个文案
        if (!empty($arrInput['movieStr']) || !empty($arrInput['cinemaStr']) || !empty($arrInput['findStr']) || !empty($arrInput['myStr'])) {
            $str.=$this->createMovieNavHasContent();
        }
        if (!empty($arrInput['movieStr'])) {
            $str.=$this->createMovieNavMovieContentStr($arrInput['movieStr']);
        }
        if (!empty($arrInput['cinemaStr'])) {
            $str.=$this->createMovieNavCinemaContentStr($arrInput['cinemaStr']);
        }
        if (!empty($arrInput['findStr'])) {
            $str.=$this->createMovieNavExplorContentStr($arrInput['findStr']);
        }
        if (!empty($arrInput['myStr'])) {
            $str.=$this->createMovieNavMyContentStr($arrInput['myStr']);
        }
        $str="/**movieNav-begin**/\n".$str."/**movieNav-end**/\n";
        return $str;
    }

    /**
     * 通过传入的css字符串，创建或替换css文件
     * @params  $strCss  要写入的css字符串
     * @params  $strPreg 传入的正则表达式，用于匹配css中的某段内容
     *
     * 1.如果此文件不存在，直接写入
     *
     * 2.如果此文件存在
     *  2.1如果匹配到了movieNav部分，则进行正则替换
     *  2.2如果未匹配到，从文件尾追加写入
     * @param $strCss
     * @return bool|int
     */
    private function createMovieNavCssFile($strCss,$strPreg)
    {
        $cdnDir = Yii::app()->params['weixin_icon']['target_dir'];
        if (!is_dir($cdnDir)) {
            @mkdir($cdnDir, 755, true);
        }
        $cssFileName = 'loadingAndNav.css';
        $cssFilePath = $cdnDir . '/' . $cssFileName;
        //真正要写入的字符串
        $strWrite = $strCss;
        if (file_exists($cssFilePath)) {//如果文件存在,正则匹配：1.匹配到就替换，2匹配不到在文件后面追加
            $strContent = file_get_contents($cssFilePath);
            $pregResult=preg_match($strPreg,$strContent);
            if ($pregResult) {//如果能匹配到，进行正则替换
                $pregResult = preg_replace($strPreg, $strCss , $strContent);
                $result = file_put_contents($cssFilePath, $pregResult);
            } else {//如果匹配不到，在文件后面追加
                $f=fopen($cssFilePath,'a+');
                $result = fwrite($f,"\n".$strWrite);
                fclose($f);
            }
        } else {//如果文件不存在直接写入
            $strWrite='@charset "UTF-8";' . "\n" .$strWrite;
            $result = file_put_contents($cssFilePath, $strWrite);
        }
        return $result;
    }

    /**
     * 将临时文件夹中的导航图片移动到正式文件夹中
     * @param $arrInput
     */
    private function moveMovieNavPic()
    {
        $cdnDir = Yii::app()->params['weixin_icon']['target_dir'];
        //写入图片文件
        $pic = file_get_contents($filePath = dirname(Yii::app()->basePath) . '/uploads/weixin_icon_tmp/movieNav.png');
        $pic_r = file_put_contents($cdnDir . '/movieNav.png', $pic);
        return $pic;
    }

    /**
     * 传入一个数组，为此数组每一行的结尾添加换行符，并拼接成字符串
     * @params  array  要换行的数组
     * @return string  拼接好的字符串
     */
    private function addLineBreak($arr)
    {
        $str = '';
        foreach ($arr as $v) {
            $str .= $v . "\n";
        }
        return $str;
    }

    /**
     * 通过模板，生成导航列表里的图片css部分
     * @param $url
     * @return string
     */
    private function createMovieNavPicStr($url)
    {
        $arrStr[] = '/* 图片 */';
        $arrStr[] = '.fixed-ft .nav-sub span[class^="ico-"] i{';
        $arrStr[] = '    background-image: url(' . $url . '?' . date("y.m.d") . ')!important;';
        $arrStr[] = '}';
        return $this->addLineBreak($arrStr);
    }

    /**
     * 通过模板，生成导航列表里的文案选中时css部分
     * @param $hexColor
     */
    private function createMovieNavCheckStr($hexColor)
    {
        $arrStr[] = '/* 文案选中时色彩 如不做更改为空 */';
        $arrStr[] = '.fixed-ft .nav-sub .current a,';
        $arrStr[] = '.fixed-ft .nav-sub .current span[class^="ico-"]:before{';
        $arrStr[] = '    color: #' . $hexColor . '!important;';
        $arrStr[] = '}';


        return $this->addLineBreak($arrStr);
    }

    /**
     * 通过模板，生成导航列表里的文案未选时css部分
     * @param $hexColor
     */
    private function createMovieNavNoCheckStr($hexColor)
    {
        $arrStr[] = '/* 文案未选时色彩 如不做更改为空 */';
        $arrStr[] = '.fixed-ft .nav-sub a,';
        $arrStr[] = '.fixed-ft .nav-sub span[class^="ico-"]:before{';
        $arrStr[] = '    color: #' . $hexColor . '!important;';
        $arrStr[] = '}';
        return $this->addLineBreak($arrStr);
    }

    /**
     *当有文案时生成
     */
    private function createMovieNavHasContent()
    {
        $arrStr[] = '/* 文案内容 */';
        $arrStr[] = '.fixed-ft .nav-sub span[class^="ico-"]:before{';
        $arrStr[] = '      display: block!important;/* 没有文案时，没时为空 */';
        $arrStr[] = '}';
        $arrStr[] = '/* 文案具体内容 */';
        return $this->addLineBreak($arrStr);
    }

    /**
     * 通过模板，生成导航列表里的文案-电影css部分
     * @param $strContent
     */
    private function createMovieNavMovieContentStr($strContent)
    {
        $arrStr[] = '.fixed-ft .nav-sub .ico-movie:before{ content: "' . $strContent . '"!important; }  /* 影片 */';
        return $this->addLineBreak($arrStr);
    }

    /**
     * 通过模板，生成导航列表里的文案-影院css部分
     * @param $strContent
     */
    private function createMovieNavCinemaContentStr($strContent)
    {
        $arrStr[] = '.fixed-ft .nav-sub .ico-cinema:before{ content: "' . $strContent . '"!important; }  /* 影院 */';
        return $this->addLineBreak($arrStr);
    }

    /**
     * 通过模板，生成导航列表里的文案-发现css部分
     * @param $strContent
     */
    private function createMovieNavExplorContentStr($strContent)
    {
        $arrStr[] = '.fixed-ft .nav-sub .ico-explor:before{ content: "' . $strContent . '"!important; }  /* 发现 */';
        return $this->addLineBreak($arrStr);
    }

    /**
     * 通过模板，生成导航列表里的文案-我的css部分
     * @param $strContent
     */
    private function createMovieNavMyContentStr($strContent)
    {
        $arrStr[] = '.fixed-ft .nav-sub .ico-my:before{ content: "' . $strContent . '"!important; }  /* 我的 */';
        return $this->addLineBreak($arrStr);
    }
}
