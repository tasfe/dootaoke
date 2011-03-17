<?php
/**
*  奢侈品数据文件
*/
$channelKeys = array('summer'=>'sum_datas','txu'=>'txu_datas','car'=>'car_datas','subject'=>'subject_datas','luxury'=>'luxury_datas','fuzhuang'=>'teshufuzhuang_datas','winter'=>'winter_datas','toy'=>'toy_datas','shouji'=>'shouji_datas');
$channelMenu = array(
'winter_datas'=>array('name'=>'冬季家居','alias'=>'冬季热卖家居用品','logo_title'=>'冬季热卖家居商品选购','logo_src'=>'images/s-logo.png','index_title'=>'冬季热卖家居商品首页','channel_name'=>'冬季热卖家居商品','index'=>'winter','ids' => '200,201','dir_prefix'=>'win_'),
'luxury_datas'=>array('name'=>'奢侈品','alias'=>'淘宝网奢侈品','logo_title'=>'淘宝奢侈品导购','logo_src'=>'images/l-logo.png','index_title'=>'淘宝奢侈品导购首页','channel_name'=>'淘宝奢侈品','index'=>'luxury','ids' => '100,101','dir_prefix'=>'lux_'),
'txu_datas'=>array('name'=>'男女T恤','alias'=>'男女T恤','logo_title'=>'男女T恤选购','logo_src'=>'images/s-logo.png','index_title'=>'男女T恤首页','channel_name'=>'男女T恤','index'=>'txu','ids' => '200,203','dir_prefix'=>'txu_'),
'car_datas'=>array('name'=>'汽车用品','alias'=>'汽车用品','logo_title'=>'汽车用品选购','logo_src'=>'images/s-logo.png','index_title'=>'汽车用品首页','channel_name'=>'汽车用品','index'=>'car','ids' => '200,204','dir_prefix'=>'car_'),
'toy_datas'=>array('name'=>'玩具','alias'=>'玩具','logo_title'=>'玩具选购','logo_src'=>'images/s-logo.png','index_title'=>'玩具首页','channel_name'=>'玩具商品','index'=>'toy','ids' => '200,201','dir_prefix'=>'toy_'),
'shouji_datas'=>array('name'=>'手机','alias'=>'热卖手机','logo_title'=>'手机选购','logo_src'=>'images/s-logo.png','index_title'=>'手机导购首页','channel_name'=>'节日礼物','index'=>'shouji','ids' => '200,201','dir_prefix'=>'shouji_'),
'subject_datas'=>array('name'=>'特色商品','alias'=>'淘宝网特色商品','logo_title'=>'淘宝网特色商品选购','logo_src'=>'images/s-logo.png','index_title'=>'淘宝网特色商品首页','channel_name'=>'淘宝网特色商品','index'=>'subject','ids' => '200,201','dir_prefix'=>'sub_'),
'teshufuzhuang_datas'=>array('name'=>'特色服装','alias'=>'特色服装/民族服装','logo_title'=>'特色服装选购','logo_src'=>'images/s-logo.png','index_title'=>'特色服装首页','channel_name'=>'特色服装','index'=>'fuzhuang','ids' => '200,201','dir_prefix'=>'fuz_'),
'sum_datas'=>array('name'=>'夏季家居','alias'=>'夏季热卖家居用品','logo_title'=>'夏季热卖家居商品选购','logo_src'=>'images/s-logo.png','index_title'=>'夏季热卖家居商品首页','channel_name'=>'夏季热卖家居商品','index'=>'summer','ids' => '200,201','dir_prefix'=>'sum_'),
);

$channel_data = array(
'luxury_datas' => array(
	'default' => array('start_price'=>'1000','end_price'=>'9900','start_credit'=>'1diamond','end_credit'=>'5goldencrown','start_commissionRate'=>'1000','end_commissionRate'=>'3000','start_commissionNum'=>'0','end_commissionNum'=>'99999','outer_code'=>'luxury','sort'=>'credit_desc','page_size'=>9,),
	'100' => array('name'=>'名表','alias'=>'名牌手表','cid' => '50005700',	'start_price'=>'3000',),
	'101' => array('name'=>'箱包','alias'=>'高档箱包','cid' => '50006842',),
	'102' => array('name'=>'ZIPPO','alias'=>'ZIPPO','cid' => '2908',),	
	'103' => array('name'=>'香水','alias'=>'高档香水','cid' => '50010815','start_price'=>'500',),	
	'104' => array('name'=>'女鞋','alias'=>'高档女鞋','cid' => '50006843','start_price'=>'500',),	
	'105' => array('name'=>'运动鞋','alias'=>'运动鞋','cid' => '50010388','start_price'=>'500',),	
	'106' => array('name'=>'摄影器材','alias'=>'摄影器材','cid' => '14','start_price'=>'3000','end_price'=>'99900','start_credit'=>'1heart','end_credit'=>'5goldencrown','start_commissionRate'=>'500','end_commissionRate'=>'3000',),	
	'107' => array('name'=>'户外','alias'=>'户外装备','cid' => '2203','start_price'=>'1000','start_commissionRate'=>'500',),	
	'108' => array('name'=>'珠宝','alias'=>'珠宝翡翠','cid' => '50011397','start_price'=>'2000','start_commissionRate'=>'500',),	
	'109' => array('name'=>'影音','alias'=>'影音电器','cid' => '50011972','start_price'=>'3000','start_credit'=>'1heart','start_commissionRate'=>'500',),
),
		
'subject_datas' => array(
	'default' => array('start_price'=>'100','end_price'=>'9900','start_credit'=>'1diamond','end_credit'=>'5goldencrown','start_commissionRate'=>'1000','end_commissionRate'=>'3000','start_commissionNum'=>'0','end_commissionNum'=>'99999','outer_code'=>'subject','sort'=>'credit_desc','page_size'=>9,),
	'200' => array('name'=>'减肥','alias'=>'减肥瘦身','keyword'=>'减肥','cid' => '1801',),	
	'201' => array('name'=>'孕妇装','alias'=>'孕妇装/托腹裤','cid' => '50012354',),
	'202' => array('name'=>'防辐射服','alias'=>'防辐射服','cid' => '50012374',),
	'203' => array('name'=>'床品','alias'=>'床品套件','cid' => '50008779',),	
	'204' => array('name'=>'内衣','alias'=>'女士内衣/男士内衣/家居服','cid' => '1625',),	
	'205' => array('name'=>'保健食品','alias'=>'保健食品','start_price'=>'50','cid' => '50012472',),	
	'206' => array('name'=>'雪纺衫','alias'=>'蕾丝衫/雪纺衫','cid' => '162116',),	
	'207' => array('name'=>'老年装','alias'=>'中老年服装','cid' => '50000852',),	
	'208' => array('name'=>'饰品','alias'=>'时尚饰品/流行首饰','start_price'=>'50','cid' => '50013864',),
	'209' => array('name'=>'奶粉','alias'=>'奶粉/辅食/营养品','start_price'=>'50','cid' => '35',),
),

'sum_datas' => array(
	'default' => array('start_price'=>'3','end_price'=>'9900','start_credit'=>'1diamond','end_credit'=>'5goldencrown','start_commissionRate'=>'300','end_commissionRate'=>'4000','start_commissionNum'=>'0','end_commissionNum'=>'99999','outer_code'=>'sum','sort'=>'credit_desc','page_size'=>9,),
	'200' => array('name'=>'空调被','alias'=>'空调被','keyword'=>'空调被','cid' => '21',),	
	'201' => array('name'=>'蚊帐','alias'=>'蚊帐','keyword'=>'蚊帐','cid' => '21',),
	'202' => array('name'=>'凉席','alias'=>'凉席','keyword'=>'凉席','cid' => '21',),
	'203' => array('name'=>'灭蚊用品','alias'=>'灭蚊用品','keyword'=>'灭蚊','cid' => '21',),	
	'204' => array('name'=>'凉拖鞋','alias'=>'凉拖鞋','keyword'=>'凉拖','cid' => '21',),	
	'205' => array('name'=>'遮阳伞','alias'=>'遮阳伞','keyword'=>'遮阳伞','cid' => '21',),	
	'206' => array('name'=>'电风扇','alias'=>'电风扇','cid' => '50008557',),	
	'207' => array('name'=>'防晒霜','alias'=>'防晒霜','keyword'=>'防晒 霜','cid' => '21',),	
	'208' => array('name'=>'散热器','alias'=>'笔记本散热器','keyword'=>'散热器','cid' => '50008090',),
	'209' => array('name'=>'刨冰机','alias'=>'刨冰机','keyword'=>'刨冰机','cid' => '21',),
),

'winter_datas' => array(
	'default' => array('start_price'=>'3','end_price'=>'9900','start_credit'=>'1diamond','end_credit'=>'5goldencrown','start_commissionRate'=>'300','end_commissionRate'=>'4000','start_commissionNum'=>'0','end_commissionNum'=>'99999','outer_code'=>'sum','sort'=>'credit_desc','page_size'=>9,),
	'200' => array('name'=>'取暖器','alias'=>'暖风机/取暖器','cid' => '350404',),	
	'201' => array('name'=>'保暖内衣','alias'=>'保暖内衣','cid' => '50008885',),
	'202' => array('name'=>'热水袋','alias'=>'热水袋','cid' => '50013960',),
	'203' => array('name'=>'暖手/脚宝','alias'=>'暖手宝/暖脚宝','cid' => '50013958',),	
	'204' => array('name'=>'暖宝宝','alias'=>'保暖贴/暖宝宝','cid' => '50013959',),	
	'205' => array('name'=>'拖鞋','alias'=>'家居拖鞋','keyword'=>'拖鞋 冬','cid' => '21',),	
	'206' => array('name'=>'床上用品','alias'=>'床上用品','keyword'=>'床 冬','cid' => '50008163',),	
	'207' => array('name'=>'怀炉','alias'=>'怀炉/怀炉用品','cid' => '50006966',),	
	'208' => array('name'=>'女-羽绒服','alias'=>'女士羽绒服','keyword'=>'羽绒','cid' => '50008899',),
	'209' => array('name'=>'男-羽绒服','alias'=>'男士羽绒服','keyword'=>'羽绒','cid' => '50011167',),
),
	
'teshufuzhuang_datas' => array(
	'default' => array('start_price'=>'3','end_price'=>'9900','start_credit'=>'1diamond','end_credit'=>'5goldencrown','start_commissionRate'=>'300','end_commissionRate'=>'4000','start_commissionNum'=>'0','end_commissionNum'=>'99999','outer_code'=>'sum','sort'=>'credit_desc','page_size'=>9,),
	'200' => array('name'=>'婚纱旗袍','alias'=>'婚纱/旗袍/礼服','cid' => '50011404',),	
	'201' => array('name'=>'唐装/民族服装','alias'=>'唐装/民族服装/舞台服装','cid' => '50008906',),
	'202' => array('name'=>'特大特小装','alias'=>'特大特小服装','cid' => '1629',),
	'203' => array('name'=>'中老年服装','alias'=>'中老年服装','cid' => '50000852',),	
	'204' => array('name'=>'民族服装','alias'=>'民族服装','cid' => '50001748',),	
	'205' => array('name'=>'特大码男鞋','alias'=>'特大码男鞋','keyword'=>'特大 鞋 男','cid' => '50011740',),	
	'206' => array('name'=>'特大码女鞋','alias'=>'特大码女鞋','keyword'=>'特大 鞋 女','cid' => '50006843',),	
	'207' => array('name'=>'职业女装','alias'=>'职业女装','keyword'=>'职业','cid' => '16',),	
	'208' => array('name'=>'职业男装','alias'=>'职业男装','keyword'=>'职业','cid' => '30',),
),

'txu_datas' => array(
	'default' => array('start_price'=>'3','end_price'=>'9900','start_credit'=>'1diamond','end_credit'=>'5goldencrown','start_commissionRate'=>'300','end_commissionRate'=>'4000','start_commissionNum'=>'0','end_commissionNum'=>'99999','outer_code'=>'sum','sort'=>'credit_desc','page_size'=>9,),
	'200' => array('name'=>'女-短袖T恤','alias'=>'女士短袖T恤','keyword'=>'短袖','cid' => '50000671',),	
	'201' => array('name'=>'女-长袖T恤','alias'=>'女士长袖T恤','keyword'=>'长袖','cid' => '50000671',),	
	'202' => array('name'=>'女-全棉T恤','alias'=>'女士全棉T恤','keyword'=>'全棉','cid' => '50000671',),
	'203' => array('name'=>'男-短袖T恤','alias'=>'男士短袖T恤','keyword'=>'短袖','cid' => '50000436',),
	'204' => array('name'=>'男-长袖T恤','alias'=>'男士长袖T恤','keyword'=>'长袖','cid' => '50000436',),
	'205' => array('name'=>'男-全棉T恤','alias'=>'男士全棉T恤','keyword'=>'全棉','cid' => '50000436',),
	'206' => array('name'=>'情侣T恤','alias'=>'情侣T恤','keyword'=>'情侣','cid' => '50000671',),
	'207' => array('name'=>'女-韩版T恤','alias'=>'女士韩版T恤','keyword'=>'韩版 女','cid' => '50000671',),
	'208' => array('name'=>'男-韩版T恤','alias'=>'男士韩版T恤','keyword'=>'韩版 男','cid' => '50000436',),
	'209' => array('name'=>'中老年','alias'=>'中老年T恤','keyword'=>'中老','cid' => '50000671',),
),
	
'car_datas' => array(
	'default' => array('start_price'=>'3','end_price'=>'9900','start_credit'=>'1diamond','end_credit'=>'5goldencrown','start_commissionRate'=>'300','end_commissionRate'=>'4000','start_commissionNum'=>'0','end_commissionNum'=>'99999','outer_code'=>'sum','sort'=>'credit_desc','page_size'=>9,),
	'200' => array('name'=>'凉垫','alias'=>'凉垫/夏季汽车座垫','keyword'=>'凉垫','cid' => '50014480',),	
	'201' => array('name'=>'GPS导航仪','alias'=>'汽车GPS导航仪','cid' => '50018720',),	
	'202' => array('name'=>'车载冰箱','alias'=>'车载冰箱','cid' => '261701',),
	'203' => array('name'=>'车载吸尘器','alias'=>'车载吸尘器','cid' => '261705',),
	'204' => array('name'=>'汽车音响','alias'=>'汽车音响','keyword'=>'汽车 音响','cid' => '26',),
	'205' => array('name'=>'车载MP3','alias'=>'车载MP3','keyword'=>'车载MP3','cid' => '26',),
	'206' => array('name'=>'车载DVD','alias'=>'车载DVD','keyword'=>'车载DVD','cid' => '26',),
	'207' => array('name'=>'汽车饰品','alias'=>'汽车饰品挂件','keyword'=>'饰品','cid' => '26',),
	'208' => array('name'=>'倒车雷达','alias'=>'倒车雷达','keyword'=>'雷达','cid' => '26',),
	'209' => array('name'=>'方向盘套','alias'=>'方向盘套','keyword'=>'方向盘套','cid' => '26',),
	'210' => array('name'=>'遮阳挡','alias'=>'遮阳挡','keyword'=>'遮阳挡','cid' => '26',),
),
	
'toy_datas' => array(
	'default' => array('start_price'=>'3','end_price'=>'9900','start_credit'=>'1diamond','end_credit'=>'5goldencrown','start_commissionRate'=>'150','end_commissionRate'=>'4000','start_commissionNum'=>'0','end_commissionNum'=>'99999','outer_code'=>'sum','sort'=>'credit_desc','page_size'=>9,),
	'200' => array('name'=>'高达模型','alias'=>'高达模型','keyword'=>'高达模型','cid' => '25',),	
	'201' => array('name'=>'芭比娃娃','alias'=>'芭比娃娃','keyword'=>'芭比娃娃','cid' => '25',),	
	'202' => array('name'=>'遥控车','alias'=>'遥控车','keyword'=>'遥控车','cid' => '25',),
	'203' => array('name'=>'遥控直升机','alias'=>'遥控直升机','keyword'=>'遥控直升机','cid' => '25',),
	'204' => array('name'=>'遥控飞机','alias'=>'遥控飞机','keyword'=>'遥控飞机','cid' => '25',),
	'205' => array('name'=>'变形金刚','alias'=>'变形金刚','keyword'=>'变形金刚','cid' => '25',),
	'206' => array('name'=>'毛绒玩具','alias'=>'毛绒玩具','keyword'=>'毛绒','cid' => '25',),
	'207' => array('name'=>'迪士尼玩具','alias'=>'迪士尼玩具','cid' => '50006192',),
	'208' => array('name'=>'创意玩具','alias'=>'创意玩具','start_commissionRate'=>'200','cid' => '50015992',),
	'209' => array('name'=>'合金车模','alias'=>'合金车模','keyword'=>'车模 合金','cid' => '25',),
	'210' => array('name'=>'火车玩具','alias'=>'托马斯火车玩具','keyword'=>'托马斯 火车 玩具','cid' => '25',),
),
	
'shouji_datas' => array(
	'default' => array('start_price'=>'1','end_price'=>'9900','start_credit'=>'1diamond','end_credit'=>'5goldencrown','start_commissionRate'=>'300','end_commissionRate'=>'4000','start_commissionNum'=>'0','end_commissionNum'=>'99999','outer_code'=>'sum','sort'=>'credit_desc','page_size'=>9,),
	'200' => array('name'=>'Nokia/诺基亚','alias'=>'Nokia/诺基亚手机','keyword'=>'Nokia 诺基亚','cid' => '1512',),	
	'201' => array('name'=>'Samsung/三星','alias'=>'Samsung/三星手机','keyword'=>'Samsung 三星 ','cid' => '1512',),	
	'202' => array('name'=>'Sony Ericsson/索爱 ','alias'=>'Sony Ericsson/索尼爱立信手机','keyword'=>'索尼 爱立信 ','cid' => '1512',),
	'203' => array('name'=>'Motorola/摩托罗拉 ','alias'=>'Motorola/摩托罗拉手机','keyword'=>'Motorola 摩托罗拉 ','cid' => '1512',),	
	'204' => array('name'=>'Huawei/华为','alias'=>'Huawei/华为手机','keyword'=>'Huawei 华为','cid' => '1512',),	
	'205' => array('name'=>'ZTE/中兴','alias'=>'ZTE/中兴手机','keyword'=>'ZTE 中兴 ','cid' => '1512',),	
	'210' => array('name'=>'Google/谷歌','alias'=>'Google/谷歌手机','keyword'=>'Google 谷歌','cid' => '1512',),	
),
);

?>