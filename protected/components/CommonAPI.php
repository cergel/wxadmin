<?php

/**
 * @tutorial 接口相关,详情自己看
 * @author liulong
 */
class CommonAPI
{
	public static $v = '2015082601'; //版本号
	public static $appkey = 10; 	  //渠道号
	public static $from = 987654321;   //from
	public static $platForm = 1; 	  //
	public static $strSignSecret = 'jsIa9jL10Vxa9HMlEb9E4Fa15f'; //秘钥
	 /**
	 * @tutorial 请求接口
	 * @param unknown $url
	 * @param string $postdate
	 * @param string $type
	 * @return array
	 * @author liulong
	 */
	public static function curlopen($strFunction,$arrPostData='',$type='post',$noSign=[]){
		$url = CommonAPI::getBaseUrl($strFunction);
		if (empty($url))
			return [];
		//签名应该是算出来的
		$arrPostData = CommonAPI::makeSign($arrPostData,$noSign);
		//以上部分到时候摘出来就可以
		if ($type == 'get') {
			$url .='?'.http_build_query($arrPostData);
			$data = @file_get_contents($url);
		}else{
			$minPostData = is_array($arrPostData)?http_build_query($arrPostData):$arrPostData;
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			//CURLOPT_HTTPHEADER => $headers,
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
			curl_setopt($ch, CURLOPT_POSTFIELDS, $minPostData);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			$data = @curl_exec($ch);
			curl_close($ch);
		}
		$data = json_decode($data,true);
		return $data['ret'] == '0' ?$data['data']:false;
	}
	/**
	 * @tutorial 组合主要字段信息，并且增加签名
	 * @param array $arrPostData
	 * @return array
	 * @author liulong
	 */
	public static function makeSign($arrPostData = array(),$noSign=[])
	{
		//统一拼凑必传字段
		$arrPostData['appkey'] = self::$appkey;
		$arrPostData['v'] = self::$v;
		$arrPostData['from'] = self::$from;
		$arrPostData['t'] =time();
		$arrPostData['platForm'] = self::$platForm;
		//密码转化大写
		if(!empty($noSign)){
			foreach ($noSign as $key=>$value){
				if (isset($arrPostData[$value])){
					unset($noSign[$key]);
					$noSign[$value] = $arrPostData[$value];
					unset($arrPostData[$value]);
					$value = '';
				}else {
					$value = '';
				}
			}
			$noSign = array_filter($noSign);
	
		}
		//生成sign签名
		ksort($arrPostData);
		$strKey = urldecode(http_build_query($arrPostData));
		$strMd5 = MD5(self::$strSignSecret . $strKey);
		$arrPostData['sign'] =  strtoupper($strMd5);
		return array_merge($arrPostData,$noSign);
	}
	
	public static function getBaseUrl($interfaceName)
	{
		//$baseUrl = ['http://ioscgi.wepiao.com','http://androidcgi.wepiao.com'];
		$baseUrl = ['http://app.pre.wepiao.com','http://app.pre.wepiao.com'];
		$array = [//用户接口
				'isMobile'=>'/user/is-mobile-no', 						//手机号是否存在（T OR F）
				'mobileRegister'=>'/user/mobile-register',  			//手机号密码注册（arr）
				'userEdit'=>'/user/user-edit', 							//用户信息修改
				'uidUserinfo'=>'/user/get-userinfo-by-uid', 			//根据uid获取用户信息 (arr)
				'openidUserinfo'=>'/user/get-userinfo-by-openid', 		//根据openId获取用户信息（arr）
				'mobilenoUserinfo'=>'/user/get-userinfo-by-mobileno', 	//根据手机号获取用户信息（arr）
				'openRegister'=>'/user/open-register', 					//第三方注册（arr）
				'unbind'=>'/user/unbind',								//手机号解绑（arr）
				'login'=>'/user/login', 								//手机号密码登陆（arr）
				'bind'=>'/user/bind', 									//手机号绑定（arr）
				'editmobile'=>'/user/edit-mobile',						//修改手机号
				'editPassword'=>'/user/edit-password', 					//修改密码(arr)
				'reset'=>'/user/edit-reset', 							//密码重置(arr)
				'editPhoto'=>'/user/upload-image', 						//修改头像(arr)
				
				//动态信息接口
				'queryLocked'=>'/ticket/query-locked-seat', 			//获取不可售座位（新版,arr）
				'isNewUser'=>'/user/check-new-user', 					//查询新老用户
				'sendCheck'=>'/sms/send-check-code', 					//获取手机号验证码
				'verifyMobile'=>'/sms/verify-mobile', 					//手机号验证码验证
				'showBonus'=>'/bonus/show-bonus', 						//红包活动展示及获取用户当前活动的红包列表
				'getBonus'=>'/bonus/get-bonus', 						//获取红包
				'lockSeat'=>'/ticket/lock-seat', 						//锁坐
				'queryBonus'=>'/bonus/query-bonus', 					//获取优惠信息（“我的”）
				'queryUnpayment'=>'/ticket/query-unpayment-and-bonus', 	//获取未支付订单信息和优惠信息
				'unlockSeat'=>'/ticket/unlock-seat-and-bonus', 			//根据订单编号和排期编号，解锁座位和可用优惠
				'wandaCode'=>'/wanda/wanda-verify-code', 				//获取万达验证码
				'orderConfirm'=>'/order/order-confirm', 				//订单确认接口（预算使用)
				'orderQuery'=>'/order/order-query', 					//订单查询
				'queryBonus'=>'/bonus/query-bonus', 					//订单查询接口
				'userOrder'=>'/order/query-user-order', 				//获取所有订单接口
				
				//城市、影院、影片获取接口
				'cityList'=>'/city/list', 								//获取城市列表
				'movieList'=>'/movie/list', 							//城市影片列表
				'movieInfo'=>'/movie/info', 							//影片详情
				'cinemaInfo'=>'/cinema/info', 							//影院详情
				'cinemaList'=>'/cinema/list', 							//城市下影院列表
				'scheCinema'=>'/sche/cinema', 							//影院下所有影片的排期
				'movieCinema'=>'/sche/cinema-list-by-movie', 			//包含某个影片的影院ID列表:
				'roomMap'=>'/cinema/room', 								//影厅座位图:
				'movieMore'=>'/movie/info-bymax', 							//多个影片的影片详情
				
				//评论评分接口
				'movieWant'=>'/movie/want',                				 //想看，取消想看
				'movieScore'=>'/movie/score',                			 //给影片评分
				'movieUserScore '=>'/movie/get-score-and-comment',       //取得用户对某影片的评分和评论
				'movieCommentsAll'=>'/movie/get-comments',                //获取指定影片下的评论
				'movieCommentsHot'=>'/movie/get-hot-comments',            //获取指定影片下的热门评论
				'commentSave'=>'/comment/save',                				 //创建或者修改影片评论和评分
				'commentGetReplies'=>'/comment/get-replies',                //取得指定评论的回复列表
				'commentAddReply'=>'/comment/add-reply',                //给指定评论添加回复
				'commentFavor'=>'/comment/favor',                		//赞/取消赞-评论
				'userWants'=>'/user/wants',                				 //我想看的电影
				'userSeens'=>'/user/seens',                				 //我看过的电影
				'movieWantUser'=>'/movie/wants',                		 //想看指定影片的用户
				'bannerList'=>'/banner/list',                			 //Banner列表
				];
		return empty($array[$interfaceName]) ? false : $baseUrl[rand(0, 1)] . $array[$interfaceName];
	}
}
