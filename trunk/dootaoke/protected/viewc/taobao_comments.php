<?php
$comments = $data['comments'];
?>
<style>
#reviews .scroller,#reviews .scroller em,#reviews .c-value-no{background:url(http://img01.taobaocdn.com/tps/i1/T1MKh0Xl4cXXXXXXXX-400-239.png) no-repeat;}#bd h4.hd{height:30px;overflow:hidden;line-height:30px;text-indent:16px;background-repeat:repeat-x;background-position:0 -210px;}#reviews .show-rate-summary{margin:10px 0;padding:10px 20px;color:#808080;border:1px solid #c8c8c8;*zoom:1;}#reviews .personal-info{margin:10px 0;}#reviews .seller-rate-info{border:none;}#reviews .personal-info h4{font-weight:bold;font-size:12px;color:#404040;}#reviews .personal-info p{padding-left:20px;}#reviews .personal-info .score{margin:0 2px;font-weight:normal;font-size:2em;color:#f60;}#reviews .personal-info .rated{margin:0 2px;}#reviews .personal-info .rated strong{font-weight:normal;color:#f60;}#reviews .personal-info .text{width:185px;padding:5px 10px 5px 20px;text-align:left;vertical-align:top;border:none;}#reviews .personal-info .text em{font-weight:bold;color:black;}#reviews .personal-info .title{line-height:25px;color:black;}#reviews .personal-info .graph{padding:10px 5px 0;text-align:left;border:none;}#reviews .personal-info .desc div{padding:5px 0 5px 62px;overflow:hidden;background-color:#fff;*zoom:1;}#reviews .personal-info .desc li{display:inline;float:left;width:80px;text-align:center;}#reviews .scroller{display:block;width:430px;height:44px;margin:0 5px;text-align:left;background-position:15px 26px;}#reviews .scroller p{display:block;width:400px;height:22px;padding-left:30px;}#reviews .scroller span{display:block;width:0;height:22px;}#reviews .scroller em{display:inline;float:right;width:30px;height:22px;padding-top:1px;text-align:center;color:white;background-position:-71px -23px;}
#reviews .c-value-no{display:-moz-inline-box;display:inline-block;width:60px;height:16px;overflow:hidden;vertical-align:middle;}#reviews .c-value-no em{visibility:hidden;}#reviews .c-value-5{background-position:0 -25px;}#reviews .c-value-4d9,#reviews .c-value-4d8,#reviews .c-value-4d7,#reviews .c-value-4d6,#reviews .c-value-4d5,#reviews .c-value-4d4,#reviews .c-value-4d3,#reviews .c-value-4d2,#reviews .c-value-4d1{background-position:0 -41px;}#reviews .c-value-4{background-position:0 -57px;}#reviews .c-value-3d9,#reviews .c-value-3d8,#reviews .c-value-3d7,#reviews .c-value-3d6,#reviews .c-value-3d5,#reviews .c-value-3d4,#reviews .c-value-3d3,#reviews .c-value-3d2,#reviews .c-value-3d1{background-position:0 -74px;}#reviews .c-value-3{background-position:0 -91px;}#reviews .c-value-2d9,#reviews .c-value-2d8,#reviews .c-value-2d7,#reviews .c-value-2d6,#reviews .c-value-2d5,#reviews .c-value-2d4,#reviews .c-value-2d3,#reviews .c-value-2d2,#reviews .c-value-2d1{background-position:0 -108px;}#reviews .c-value-2{background-position:0 -124px;}#reviews .c-value-1d9,#reviews .c-value-1d8,#reviews .c-value-1d7,#reviews .c-value-1d6,#reviews .c-value-1d5,#reviews .c-value-1d4,#reviews .c-value-1d3,#reviews .c-value-1d2,#reviews .c-value-1d1{background-position:0 -140px;}#reviews .c-value-1{background-position:0 -156px;}#reviews .c-value-0d9,#reviews .c-value-0d8,#reviews .c-value-0d7,#reviews .c-value-0d6,#reviews .c-value-0d5,#reviews .c-value-0d4,#reviews .c-value-0d3,#reviews .c-value-0d2,#reviews .c-value-0d1{background-position:0 -172px;}#reviews .c-value-0{background-position:0 -188px;}
#reviews-list{margin:3px 0;padding:3px 0;text-align:right;}#reviews .show-rate-table{table-layout:fixed;}#reviews .show-rate-table th{padding:5px;text-align:center;background:#f8f8f8;border-bottom:1px solid #d5d5d5;}#reviews .show-rate-table th.ratee,#reviews .show-rate-table th.things,#reviews .show-rate-table th.tcomment{padding-left:10px;}#reviews .show-rate-table th.ratee,#reviews .show-rate-table th.things{text-align:left;}#reviews .show-rate-table td{padding:5px 10px;border-bottom:1px solid #d5d5d5;}#reviews .show-rate-table tfoot td{text-align:right;background:#f8f8f8;}#reviews .show-rate-table .date{color:#999;}#reviews .show-rate-table p.rate a,#reviews .show-rate-table p.rate .link{margin-right:5px;white-space:nowrap;}#reviews .show-rate-table p.rate{max-width:360px;margin-bottom:2px;line-height:18px;text-align:justify;overflow:hidden;word-wrap:break-word;*overflow:auto;}#reviews .show-rate-table .things{width:300px;}#reviews .show-rate-table .vip-icon{margin-left:4px;}#reviews .show-rate-table .vip-icon img{vertical-align:middle;}
</style>
<div id="reviews">
<div class="show-rate-summary" style="padding: 10px 20px;">
<div class="personal-info">
<table class="seller-rate-info">
<colgroup><col class="text"><col class="graph"></colgroup>
<tbody><tr><td class="text" rowspan="2"><span class="title"><h4>店铺的“宝贝与描述相符”得分</h4></span>
<div><strong class="score"><?php echo $comments['scoreInfo']['merchandisScore'];?></strong>分 <span class="c-value-no c-value-4d7 "><em>4.7 分</em></span><br>
<span class="rated">(共打分 <strong><?php echo $comments['scoreInfo']['merchandisTotal'];?></strong> 次)</span>
</div></td>
<td class="graph">
<div class="scroller"><p><span style="width: <?php echo $comments['scoreInfo']['width'];?>%;"><em><?php echo $comments['scoreInfo']['merchandisScore'];?></em></span></p></div>
</td>
</tr>
<tr><td class="desc"><div style="padding-left: 62px; background-color: white;">
<ul><li>1分<br>非常不满</li><li>2分<br>不满意</li><li>3分<br>一般</li><li>4分<br>满意</li><li>5分<br>非常满意</li></ul>
</div>
</td></tr></tbody>
</table>
</div>
</div>

<table class="show-rate-table" style="width: 100%;"><thead><tr><th class="comment" style="width: 80%;">评论</th>
<th class="ratee" style="width: 20%;">评价人</th></tr></thead>
<tbody>
<?php foreach($comments['rateListInfo']['rateList'] as $rate) {?>
<tr>
<td><p class="rate" style="text-align: left; max-width: 100%;"><?php echo $rate['rateContent'];?></p><span class="date">[<?php echo $rate['rateDate'];?>]</span>
</td>
<td>买家：<?php echo $rate['displayUserNick'];?>
<span><img align="absmiddle" src="http://pics.taobaocdn.com/newrank/<?php echo $rate['displayRatePic'];?>"></span>
</td>
</tr>
<?php } ?>
</tbody>

<tfoot>
<tr>
<td colspan="5">
<a href="javascript:void(0)" id="more_reviews">更多评论》》</a>
</td>
</tr>
</tfoot>
</table>
</div>