(function($){

    $.fn.localInstant=function(s){
	var _default = {
	    placeholder:"ここでplaceholderを設定",
	    callback:function(){}
	}
	var o = $.extend(true,{},_default,s);
	if(jQuery.nodeName(this,"select")){
	    return undefined;
	}
	var self=$(this);
	//check if browser is support the placeholder
	var _test=document.createElement("input");
	if(!("placeholder" in _test)){
	    //let's emu placeholder
	    (function($){
		var _defaultColor="silver";
		var _targets=$("input[type=text]");
		_targets.live("focus",function(){
		    var _self=$(this);
		    if(!_self.data("_li_defaultColor")){
			_self.data("_li_defaultColor",_self.css("color"));
		    }
		    if(_self.val()==_self.attr("placeholder")){
			_self.val("");
			_self.css("color",_self.data("_li_defaultColor"));
		    }
		});
		_targets.live("blur",function(){
		    var _self=$(this);
		    if(_self.val()==""){
			_self.val(_self.attr("placeholder"));
			_self.css(_defaultColor);
		    }
		});
		$("form").submit(function(){
		    $(this).find("input[type=text]").each(function(){
			var _input=$(this);
			if(_input.val()==_input.attr("placeholder")){
			    _input.val("");
			}
			return true;
		    });
		});
		_targets.each(function(){
		    var _input=$(this);
		    switch(_input.val()){
		    case "":
			_input.val(_input.attr("placeholder"));
		    case _input.attr("placeholder"):
			_input.css(_defaultColor);
			break;
		    }
		});
	    })($);
	}
	//now we get the placeholder,back to our work
	var input=$("<input type='text' placeholder='"+o.placeholder+"' size='15'>");
	input.keyup(function(){
	    var reg=new RegExp($(this).val(),'i');
	    var select=self;
	    var options=select.find("option");
	    var _options=[];
	    for(o in options){
		if(o==~~o){
		    _options.push({_t:$(options[o]),_v:$(options[o]).html()});
		}
	    }
	    _options.sort(function(a,b){
		if( (reg.test(a._v) && reg.test(b._v)) || (!reg.test(a._v) && !reg.test(b._v)) ){
		    return 0;
		}else if(reg.test(a._v)){
		    return -1;
		}else if(reg.test(b._v)){
		    return 1;
		}
	    });
	    options.remove();
	    for(i in _options){
		select.append(_options[i]._t);		
	    }
	    select.val(_options[0]._t.val());
	});
	input.insertBefore(self);
	if(!!o.callback){
	    o.callback(self);
	}
    }  

})(jQuery)