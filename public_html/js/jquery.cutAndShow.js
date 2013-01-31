/**
 *
 *   Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 *   author: chenhan,gpgkd906@gmail.com
 */

(function($){

    $.fn.cutAndShow=function(s){
	var _default = {
	    top:"0px",
	    left:"0px",
	    width:"50px",
	    height:"50px",
	    backGroundColor:"black",
	    autoplay:true,
	    action:undefined,
	    duration:1000,
	    inited:function(){},
	    start:function(){},
	    complete:function(){}
	}
	var env={},action={},o = $.extend(true,{},_default,s);
	//oはユーザパラメタ,envは実行時の環境変数
	//目標要素の幅/高/位置を取得して，環境変数に保存
	var self = $(this);
	var _position=self.position();
	env={
	    width:self.width(),
	    height:self.height(),
	    position:self.css("position"),
	    top:_position.top+"px",
	    left:_position.left+"px",
	    duration:o.duration/2,
	    init:undefined,
	    animate:undefined,
	    restore:undefined
	};
	action=function(self){
	    var inAnimate=false;

	    return {
		init:function(){
		    //一個目のdivを生成し,様式を初期化
		    var wrapOne=$("<div></div>");
		    wrapOne.css({
			position:"absolute",
			overflow:"hidden",
			top:o.top,
			left:o.left,
			width:o.width,
			height:o.height
		    });
		    //二個目のdivを生成し，様式を初期化
		    var wrapTwo=$("<div></div>");
		    wrapTwo.css({
			position:env.position,
			top:env.top,
			left:env.left,
			width:env.width+"px",
			height:env.height+"px",
			'background-color':o.backGroundColor
		    });
		    //目標をラップさせる上，様式を変更する
		    self.wrap(wrapTwo);
		    self.wrap(wrapOne);
		    self.css({
			position:"absolute",
			top:"-"+o.top,
			left:"-"+o.left
		    });
		},
		animate:function(userCallback){
		    if(inAnimate){
			return false;
		    }
		    inAnimate=true;
		    o.start(self);
		    self.parent().animate(
			{top:"0px",height:env.height+"px"},
			env.duration,
			function(){
			    self.parent().animate(
				{left:"0px",width:env.width+"px"},
				env.duration
			    )
			}
		    );
		    self.animate(
			{top:"0px"},
			env.duration,
			function(){
			    self.animate(
				{left:"0px"},
				env.duration,
				function(){
				    inAnimate=false;
				    o.complete(self,action);
				    if(userCallback!==undefined){
					userCallback(self,action);					
				    }
				}
			    );
			}
		    );
		},
		restore:function(){
		    if(inAnimate){
			return false;
		    }
		    self.unwrap();
		    self.unwrap();
		    self.css({
			width:env.width+"px",
			height:env.height+"px",
			position:env.position,
			top:env.top,
			left:env.left
		    });
		    this.init();
		}
	    }
	}(self);
	action.init();
	o.inited(self,action);
	if(o.action!==undefined){
	    self.bind(o.action,action.animate);
	}else{
	    if(o.autoplay){
		action.animate();
	    }
	}
    }
})(jQuery)