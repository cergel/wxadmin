<?php
Yii::import('ext.RedisManager', true);

class UserTag extends CActiveRecord
{
    private $redis;//在初始化时，为此redis赋值
    const REDIS_KEY_INFO_TIME = 2592000;
    public function tableName()
    {
        return 't_user_kol';
    }

    public function rules()
    {
        return array(
            array('mobileNo,openId', 'required'),
            array('mobileNo,openId', 'unique'),
            array('id,mobileNo,nickname, openId,, summary,created, updated', 'safe'),
            array('id,mobileNo,nickname, openId, summary,created, updated', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'tag' => array(self::HAS_MANY, 'UserTagKol', 'k_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id'=>'主键id',
            'mobileNo' => '手机号',
            'openId' => 'openId',
            'nickname'=>'昵称',
            'summary' => '简介',
            'created' => '创建时间',
            'updated' => '更新时间',
        );
    }

    /**
     * @tutorial 查询
     * @author liulong
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('mobileNo', $this->mobileNo);
        $criteria->compare('openId', $this->openId);
        $criteria->compare('nickname', $this->nickname, true);
        $criteria->compare('summary', $this->summary, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination'=>array(
                'pageSize'=>20,
            ),
            'sort'=>array(
                'defaultOrder'=>'id DESC',
            ),
        ));
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getDbConnection()
    {
        return Yii::app()->db;
    }
    /**
     * @tutorial 获取type或者获取指定type所代表的汉字
     * @author liulong
     * @param string | int $iType
     * @return  <multitype:string , unknown>
     */
    public function getTagList($iType = 'list')
    {
        $arrType = ['' => '全部', '10001' => '编辑', '10002' => '作家', '10003' => '记者', '10004' => '写手',
            '10005'=>'影评人','10006'=>'主编','10007'=>'媒体人','10008'=>'自媒体','10009'=>'教授','10010'=>'副教授',
            '10011'=>'讲师','10012'=>'研究生','10013'=>'博士','10014'=>'硕士','10015'=>'院士','10016'=>'学者','10017'=>'官方机构',
        ];
        if($iType == 'list')
            unset($arrType['']);
        return empty($arrType[$iType])?$arrType:$arrType[$iType];
    }

    public function getUserTagByArray($arrData)
    {
        $tag = [];
        if(is_array($arrData)){
            foreach($arrData as $val){
                $tagName = $this->getTagList($val->tag_id);
                if (!empty($tagName) && is_string($tagName)) {
                    $tag[]= $tagName;
                }
            }
        }
        return implode(',',$tag);
    }


    /**
     * 执行sql，进行落地
     * @param $sql
     * @return mixed
     */
    public static function saveSql($sql)
    {
        return Yii::app()->db->createCommand($sql)->execute();
    }


    /**
     * 添加用户标签--调用用户中心
     */
    public function saveUserTag($id,$del =false)
    {
        $model = UserTag::model()->findByPk($id);
        $tags = $tag = [];
        if (is_array($model->tag)) {
            foreach ($model->tag as $result) {
                $tagName = $this->getTagList($result->tag_id);
                if (!empty($tagName) && is_string($tagName)) {
                    $tag[] = $tagName;
                }
            }
            $tags = ['tag' => $tag, 'summary' => $model->summary, 'is_star' => 2];
        }
        $arrData['id'] = $model->openId;
        $arrData['idType'] = 0;
        $arrData['tag'] = 'tag4dragon';
        if(empty($del))
            $arrData['tagVal'] = json_encode($tags);
        else
            $arrData['tagVal'] = json_encode([]);
        UCenter::saveUserTagToUCenter($arrData);
    }


}
