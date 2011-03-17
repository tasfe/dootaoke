//jquery styleSelect 特色下拉框
(function(b){b.fn.styleSelect=function(c){var d=1;var e=b.extend({},b.fn.styleSelect.defaults,c);b("input,select,textarea,button").each(function(){var f=b(this);if(!f.attr("tabindex")){f.attr("tabindex",d);d++}});return this.each(function(){mainSelect=b(this);var j=mainSelect.attr("name");var i=mainSelect.attr("tabindex");var g=new Date;var h="selectbox_"+j+g.getTime();mainSelect.hide();var f=b('<div tabindex="'+i+'"></div>').css({position:"relative"}).addClass(e.styleClass).attr("id",h).insertBefore(mainSelect);var l=b("<ul></ul>").css({position:"absolute","z-index":"100",top:e.optionsTop,left:e.optionsLeft}).appendTo(b(f)).hide();var k="";mainSelect.find("option").each(function(){k+='<li id="'+b(this).val()+'"';if(b(this).attr("class")){k+=' class="'+b(this).attr("class")+'" '}k+=">";k+='<span style="display: block;"';if(b(this).attr("selected")){k+=' class="selected" '}k+=">";k+=b(this).text();k+="</span>";k+="</li>"});l.append(k);a(e.styleClass,e.optionsWidth);b("#"+h).click(function(){b(this).find("ul").slideToggle(e.speed)});b("#"+h+" li").click(function(){m(b(this))});b("#"+h).keydown(function(n){var o=b(this).find(".selected").parent();if(n.keyCode==40||n.keyCode==39){m(o.next())}if(n.keyCode==37||n.keyCode==38){m(o.prev())}if(n.keyCode==13||n.keyCode==0){b(this).find("ul").slideToggle(e.speed)}if(n.keyCode==9){b(this).find("ul").hide(e.speed)}});var m=function(n){n.siblings().find("span").removeClass("selected");n.find("span").addClass("selected");var o=n.attr("id");var p=b('select[name="'+j+'"]');p.siblings().selected=false;p.find('option[value="'+o+'"]').attr("selected","selected");p.trigger(e.selectTrigger);a(e.styleClass,e.optionsWidth)};b("#"+h).click(function(n){n.stopPropagation()});b(document).click(function(){b("#"+h+" ul").hide()})})};function a(d,c){b("."+d).each(function(){var f=b(this).find("ul");b(this).find("span").each(function(){var g=b(this).attr("class");if(g=="passiveSelect"||g=="activeSelect"){b(this).remove()}});var e=b(this).find(".selected");b("<span></span>").text(e.text()).attr("id",e.parent().attr("id")).addClass("passiveSelect").appendTo(b(this));if(c===0){b(this).css({width:f.width()})}});b("."+d+" span").each(function(){if(b(this).attr("id")){b(this).removeClass();b(this).addClass("activeSelect")}})}b.fn.styleSelect.defaults={optionsTop:"26px",optionsLeft:"0px",optionsWidth:0,styleClass:"selectMenu",speed:0,selectTrigger:"change"}})(jQuery);
//淘宝图片处理
$(document).ready(function(){if(typeof(top_imgs)=="undefined") return;for(i=0;i<top_imgs.length;i++){if(top_imgs[i]!=""){var a=top_imgs[i].split("|");if(a.length==2){$("#"+a[0]).attr("src",a[1])}}}});
// 添加到收藏夹、设为主页
var HomepageFavorite={Homepage:function(){if(document.all){document.body.style.behavior="url(#default#homepage)";document.body.setHomePage(window.location.href)}else{if(window.sidebar){if(window.netscape){try{netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect")}catch(b){alert("该操作被浏览器拒绝，如果想启用该功能，请在地址栏内输入 about:config,然后将项 signed.applets.codebase_principal_support 值该为true");history.go(-1)}}var a=Components.classes["@mozilla.org/preferences-service;1"].getService(Components.interfaces.nsIPrefBranch);a.setCharPref("browser.startup.homepage",window.location.href)}}},Favorite:function Favorite(b,a){try{window.external.addFavorite(b,a)}catch(c){try{window.sidebar.addPanel(a,b,"")}catch(c){alert("加入收藏失败,请手动添加.")}}}};$(function(){$("#set_home").bind("click",function(){HomepageFavorite.Homepage()});$("#add_fav").bind("click",function(){HomepageFavorite.Favorite(window.location.href,document.title)})});
// 循环广告
if(typeof jQuery!='undefined'){jQuery(function($){$.fn.extend({loopedSlider:function(options){var settings=$.extend({},$.fn.loopedSlider.defaults,options);return this.each(function(){if($.fn.jquery<'1.3.2'){return}var $t=$(this);var o=$.metadata?$.extend({},settings,$t.metadata()):settings;var distance=0;var times=1;var slides=$(o.slides,$t).children().size();var width=$(o.slides,$t).children().outerWidth();var position=0;var active=false;var number=0;var interval=0;var restart=0;var pagination=$("."+o.pagination+" li a",$t);if(o.addPagination&&!$(pagination).length){var buttons=slides;$($t).append("<ul class="+o.pagination+">");$(o.slides,$t).children().each(function(){if(number<buttons){$("."+o.pagination,$t).append("<li><a rel="+(number+1)+" href=\"#\" >"+(number+1)+"</a></li>");number=number+1}else{number=0;return false}$("."+o.pagination+" li a:eq(0)",$t).parent().addClass("active")});pagination=$("."+o.pagination+" li a",$t)}else{$(pagination,$t).each(function(){number=number+1;$(this).attr("rel",number);$(pagination.eq(0),$t).parent().addClass("active")})}if(slides===1){$(o.slides,$t).children().css({position:"absolute",left:position,display:"block"});return}$(o.slides,$t).css({width:(slides*width)});$(o.slides,$t).children().each(function(){$(this).css({position:"absolute",left:position,display:"block"});position=position+width});$(o.slides,$t).children(":eq("+(slides-1)+")").css({position:"absolute",left:-width});if(slides>3){$(o.slides,$t).children(":eq("+(slides-1)+")").css({position:"absolute",left:-width})}if(o.autoHeight){autoHeight(times)}$(".next",$t).click(function(){if(active===false){animate("next",true);if(o.autoStart){if(o.restart){autoStart()}else{clearInterval(sliderIntervalID)}}}return false});$(".previous",$t).click(function(){if(active===false){animate("prev",true);if(o.autoStart){if(o.restart){autoStart()}else{clearInterval(sliderIntervalID)}}}return false});if(o.containerClick){$(o.container,$t).click(function(){if(active===false){animate("next",true);if(o.autoStart){if(o.restart){autoStart()}else{clearInterval(sliderIntervalID)}}}return false})}$(pagination,$t).click(function(){if($(this).parent().hasClass("active")){return false}else{times=$(this).attr("rel");$(pagination,$t).parent().siblings().removeClass("active");$(this).parent().addClass("active");animate("fade",times);if(o.autoStart){if(o.restart){autoStart()}else{clearInterval(sliderIntervalID)}}}return false});if(o.autoStart){sliderIntervalID=setInterval(function(){if(active===false){animate("next",true)}},o.autoStart);function autoStart(){if(o.restart){clearInterval(sliderIntervalID);clearInterval(interval);clearTimeout(restart);restart=setTimeout(function(){interval=setInterval(function(){animate("next",true)},o.autoStart)},o.restart)}else{sliderIntervalID=setInterval(function(){if(active===false){animate("next",true)}},o.autoStart)}}}function current(times){if(times===slides+1){times=1}if(times===0){times=slides}$(pagination,$t).parent().siblings().removeClass("active");$(pagination+"[rel='"+(times)+"']",$t).parent().addClass("active")};function autoHeight(times){if(times===slides+1){times=1}if(times===0){times=slides}var getHeight=$(o.slides,$t).children(":eq("+(times-1)+")",$t).outerHeight();$(o.container,$t).animate({height:getHeight},o.autoHeight)};function animate(dir,clicked){active=true;switch(dir){case"next":times=times+1;distance=(-(times*width-width));current(times);if(o.autoHeight){autoHeight(times)}if(slides<3){if(times===3){$(o.slides,$t).children(":eq(0)").css({left:(slides*width)})}if(times===2){$(o.slides,$t).children(":eq("+(slides-1)+")").css({position:"absolute",left:width})}}$(o.slides,$t).animate({left:distance},o.slidespeed,function(){if(times===slides+1){times=1;$(o.slides,$t).css({left:0},function(){$(o.slides,$t).animate({left:distance})});$(o.slides,$t).children(":eq(0)").css({left:0});$(o.slides,$t).children(":eq("+(slides-1)+")").css({position:"absolute",left:-width})}if(times===slides)$(o.slides,$t).children(":eq(0)").css({left:(slides*width)});if(times===slides-1)$(o.slides,$t).children(":eq("+(slides-1)+")").css({left:(slides*width-width)});active=false});break;case"prev":times=times-1;distance=(-(times*width-width));current(times);if(o.autoHeight){autoHeight(times)}if(slides<3){if(times===0){$(o.slides,$t).children(":eq("+(slides-1)+")").css({position:"absolute",left:(-width)})}if(times===1){$(o.slides,$t).children(":eq(0)").css({position:"absolute",left:0})}}$(o.slides,$t).animate({left:distance},o.slidespeed,function(){if(times===0){times=slides;$(o.slides,$t).children(":eq("+(slides-1)+")").css({position:"absolute",left:(slides*width-width)});$(o.slides,$t).css({left:-(slides*width-width)});$(o.slides,$t).children(":eq(0)").css({left:(slides*width)})}if(times===2)$(o.slides,$t).children(":eq(0)").css({position:"absolute",left:0});if(times===1)$(o.slides,$t).children(":eq("+(slides-1)+")").css({position:"absolute",left:-width});active=false});break;case"fade":times=[times]*1;distance=(-(times*width-width));current(times);if(o.autoHeight){autoHeight(times)}$(o.slides,$t).children().fadeOut(o.fadespeed,function(){$(o.slides,$t).css({left:distance});$(o.slides,$t).children(":eq("+(slides-1)+")").css({left:slides*width-width});$(o.slides,$t).children(":eq(0)").css({left:0});if(times===slides){$(o.slides,$t).children(":eq(0)").css({left:(slides*width)})}if(times===1){$(o.slides,$t).children(":eq("+(slides-1)+")").css({position:"absolute",left:-width})}$(o.slides,$t).children().fadeIn(o.fadespeed);active=false});break;default:break}}})}});$.fn.loopedSlider.defaults={container:".container",slides:".slides",pagination:"pagination",containerClick:true,autoStart:0,restart:0,slidespeed:300,fadespeed:200,autoHeight:0,addPagination:false}})}

function filterDecimal(obj){
	var o_value =  obj.value.match(/^[\+]?\d*\.?\d*/);
	obj.value = o_value;
}
function filterInt(obj){
	var o_value  = obj.value.replace(/[^\d]/g,"");
	obj.value = o_value;
}

$(document).ready(function() {
	// 头部搜索框
	$(".catSelect").styleSelect({styleClass: "selectDark",optionsWidth: 1,speed: 'fast',optionsTop:'23px'});
   //搜索框提示文字
   var promp='请输入您的关键字';
   if($('#q_key').val()=='' || $('#q_key').val() == promp) {
	   $('#q_key').attr('value',promp);
	   $('#q_key').css('color','#9B9B9B');
   }
});

$(function() {
	// 搜索框提示文字
	var promp='请输入您的关键字';
	$('#q_key').bind('focus',function(){
		if($(this).val()==promp){
			$(this).attr('value','');
			$(this).css('color','#000000');
		}
	}).bind('blur',function(){
		if($(this).val()==''){
			$(this).attr('value',promp);
			$(this).css('color','#9B9B9B');
		}
	});
	
	$('#search_btn').bind('click',function(){
		var q = $('#q_key').val();
		if(q.trim()=="" || q==promp){
			alert('请输入您的查询关键字');
			return false;
		}
	});
	

   // 提示信息
	var alert_array = {'rushi':'支持消费者保障服务','zhen':'支持真品保障服务,假一赔三','zhe':'支持vip折扣',
			'sevenday':'支持7天无理由退换货',	'zhengpin':'正品保障服务','huodao':'支持货到付款',
			'shan':'支持闪电发货服务','weixiu':'支持数码与家电30天维修服务','xyka':'支持信用卡支付'};
	
	$.each(alert_array,function(name,value){
	   $("." + name).bind('mouseover',function(){
		      newTitle=value;
		      var $tip=$("<div id='tip'>"+newTitle+"</div>");
		      $("body").append($tip);
		      $("#tip").css('width',value.length * 12)
		      $("#tip").show("fast");
		   }).bind('mouseout',function(){
		      $("#tip").remove();
		   }).bind('mousemove',function(e){
		      $("#tip").css({"top":(e.pageY+10)+"px","left":(e.pageX+5)+"px"})
		 });
	});
	
	// 数据匹配
	$('input.int').bind('keyup',function(){
		filterInt($(this)[0]);
	});
	$('input.decimal').bind('keyup',function(){
		filterDecimal($(this)[0]);
	});
});

//头部菜单下拉列表
$(function() {
	var $oe_menu		= $('#oe_menu');
	var $oe_menu_items	= $oe_menu.children('li');

    $oe_menu_items.bind('mouseenter',function(){
		var $this = $(this);
		$this.addClass('slided selected');
		$this.children('div').css('z-index','9999').stop(true,true).slideDown(200,function(){
			$oe_menu_items.not('.slided').children('div').hide();
			$this.removeClass('slided');
		});
	}).bind('mouseleave',function(){
		var $this = $(this);
		$this.removeClass('selected').children('div').css('z-index','99').hide();
	});

	$oe_menu.bind('mouseenter',function(){
		var $this = $(this);
		$this.addClass('hovered');
	}).bind('mouseleave',function(){
		var $this = $(this);
		$this.removeClass('hovered');
		$oe_menu_items.children('div').hide();
	})
});

// 用户登录信息
function ajax_login_user(url) {
	$.post(url,null ,
	function(result) {
		if(result != null && result != '' ) {
			var user = eval('('+ result +')'); //JSON转换
			$('.not_login').css('display','none');
			$('.logined').css('display','block');
			$('#username').html(user.username);
			$('#logout').attr('href',$('#logout').attr('href') +user.verify); 
		}
	});
}