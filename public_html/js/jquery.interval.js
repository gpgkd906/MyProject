/**
 *
 *   Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 *   author: chenhan,gpgkd906@gmail.com
 */

(function($){

    $.fn.interval=function(s){
	var _default = {
	    source:[],
	    interval:50,
	    stack:10,
	    reduce:function(){},
	    loaded:function(){},
	    complete:function(){}
	}
	var o = $.extend(true,{},_default,s),self=$(this);
	var i=0,cur;
	var sid=setInterval(function(){
	    var j=i+o.stack,store;
	    for(;i<=o.source.length;){
		cur=o.source[i];
		if(cur===undefined){
		    clearInterval(sid);
		    setTimeout(o.complete,o.interval);
		    break;
		}
		store=o.reduce(store,cur);
		i++;
		if(i>j){
		    break;
		}
	    }
	    if(!!store){
		o.loaded(self,store);
	    }
	},o.interval);
    }
})(jQuery)