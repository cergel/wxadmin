<?php

/**
 * This is the model class for table "local_order_info".
 *
 * The followings are the available columns in table 'local_order_info':
 * @property string $id
 * @property string $fix_order_id
 * @property string $order_id
 * @property integer $schedule_id
 * @property integer $cinema_no
 * @property integer $channel_id
 * @property string $seat
 * @property integer $local_seat_status
 * @property string $tp_order_id
 * @property string $tp_ext_order_id
 * @property integer $price_record_id
 * @property string $ticket_code
 * @property string $voucher_code
 * @property integer $source
 * @property string $mobile
 * @property integer $lock_timeout
 * @property string $lock_time
 * @property string $show_time
 * @property string $created_time
 * @property string $refund_start_time
 * @property string $sale_start_time
 * @property string $modified_time
 */
class GoodsCenterLocalOrder extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'local_order_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('schedule_id, cinema_no, channel_id, local_seat_status, price_record_id, source, created_time, refund_start_time, sale_start_time, modified_time', 'required'),
			array('schedule_id, cinema_no, channel_id, local_seat_status, price_record_id, source, lock_timeout', 'numerical', 'integerOnly'=>true),
			array('fix_order_id, order_id, tp_ext_order_id', 'length', 'max'=>32),
			array('seat, tp_order_id', 'length', 'max'=>50),
			array('ticket_code, voucher_code', 'length', 'max'=>64),
			array('mobile', 'length', 'max'=>20),
			array('lock_time, show_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, fix_order_id, order_id, schedule_id, cinema_no, channel_id, seat, local_seat_status, tp_order_id, tp_ext_order_id, price_record_id, ticket_code, voucher_code, source, mobile, lock_timeout, lock_time, show_time, created_time, refund_start_time, sale_start_time, modified_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'fix_order_id' => 'Fix Order',
			'order_id' => 'Order',
			'schedule_id' => 'Schedule',
			'cinema_no' => 'Cinema No',
			'channel_id' => 'Channel',
			'seat' => 'Seat',
			'local_seat_status' => 'Local Seat Status',
			'tp_order_id' => 'Tp Order',
			'tp_ext_order_id' => 'Tp Ext Order',
			'price_record_id' => 'Price Record',
			'ticket_code' => 'Ticket Code',
			'voucher_code' => 'Voucher Code',
			'source' => 'Source',
			'mobile' => 'Mobile',
			'lock_timeout' => 'Lock Timeout',
			'lock_time' => 'Lock Time',
			'show_time' => 'Show Time',
			'created_time' => 'Created Time',
			'refund_start_time' => 'Refund Start Time',
			'sale_start_time' => 'Sale Start Time',
			'modified_time' => 'Modified Time',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('fix_order_id',$this->fix_order_id,true);
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('schedule_id',$this->schedule_id);
		$criteria->compare('cinema_no',$this->cinema_no);
		$criteria->compare('channel_id',$this->channel_id);
		$criteria->compare('seat',$this->seat,true);
		$criteria->compare('local_seat_status',$this->local_seat_status);
		$criteria->compare('tp_order_id',$this->tp_order_id,true);
		$criteria->compare('tp_ext_order_id',$this->tp_ext_order_id,true);
		$criteria->compare('price_record_id',$this->price_record_id);
		$criteria->compare('ticket_code',$this->ticket_code,true);
		$criteria->compare('voucher_code',$this->voucher_code,true);
		$criteria->compare('source',$this->source);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('lock_timeout',$this->lock_timeout);
		$criteria->compare('lock_time',$this->lock_time,true);
		$criteria->compare('show_time',$this->show_time,true);
		$criteria->compare('created_time',$this->created_time,true);
		$criteria->compare('refund_start_time',$this->refund_start_time,true);
		$criteria->compare('sale_start_time',$this->sale_start_time,true);
		$criteria->compare('modified_time',$this->modified_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function getAllOrder($startTime, $endTime)
    {
        $orderCount = [];
        $lockParam = [
            ':startTime' => $startTime,
            ':endTime' => $endTime,
            #':status' => 0,
        ];
        $saleParam = [
            ':startTime' => $startTime,
            ':endTime' => $endTime,
            #':status' => 3,
        ];
        $refundParam = [
            ':startTime' => $startTime,
            ':endTime' => $endTime,
            #':status' => 6,
        ];
        $model = new self();
        $orderCount['lock'] = $model->count("created_time >= :startTime and created_time <= :endTime ", $lockParam);
        $orderCount['sale'] = $model->count("local_seat_status in (3,4) and created_time >= :startTime and created_time <= :endTime", $saleParam);
        $orderCount['refund'] = $model->count("local_seat_status = 6 and created_time >= :startTime and created_time <= :endTime", $refundParam);
        $orderCount['notSale'] = $orderCount['lock'] - $orderCount['sale'];//未卖出
        $orderCount['badRefund'] = $model->count("local_seat_status = 7 and created_time >= :startTime and created_time <= :endTime", $refundParam);//退票失败
        $orderCount['onSale'] = $model->count("local_seat_status in (0,1,2) and created_time >= :startTime and created_time <= :endTime", $refundParam);
        return $orderCount;
    }

    public function getOrderByCinema($startTime, $endTime, $cinemaNo)
    {
        $orderCount = [];
        $lockParam = [
            ':startTime' => $startTime,
            ':endTime' => $endTime,
            ':status' => 0,
            #':cinemaNo' => $cinemaNo,
        ];
        $saleParam = [
            ':startTime' => $startTime,
            ':endTime' => $endTime,
            ':status' => 3,
            #':cinemaNo' => $cinemaNo,
        ];
        $refundParam = [
            ':startTime' => $startTime,
            ':endTime' => $endTime,
            ':status' => 6,
            #':cinemaNo' => $cinemaNo,
        ];
        $model = new self();
        $orderCount['lock'] = $model->count("cinema_no = :cinemaNo  and lock_time >= :startTime and lock_time <= :endTime ", $lockParam);
        $orderCount['sale'] = $model->count("cinema_no = :cinemaNo and local_seat_status in (3,4) and lock_time >= :startTime and lock_time <= :endTime", $saleParam);
        $orderCount['refund'] = $model->count("cinema_no = :cinemaNo and  local_seat_status = 6 and lock_time >= :startTime and lock_time <= :endTime", $refundParam);
        $orderCount['notSale'] = $orderCount['lock'] - $orderCount['sale'];//未卖出
        $orderCount['badRefund'] = $model->count("local_seat_status = 7 and created_time >= :startTime and created_time <= :endTime", $refundParam);//退票失败
        $orderCount['onSale'] = $model->count("local_seat_status in (0,1,2) and created_time >= :startTime and created_time <= :endTime", $refundParam);
        return $orderCount;
    }

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->db_goods_center;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GoodsCenterLocalOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    //查询订单详情
    public function getOrderInfo($startTime, $endTime )
    {
        $params = [
            ':startTime' => $startTime,
            ':endTime' => $endTime,
        ];
        $condition = "created_time >= :startTime and created_time < :endTime";
        $model = new self();
        $r=$model->findAll($condition,$params);
        return $r;
    }
}
