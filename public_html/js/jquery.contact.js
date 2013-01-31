/**
 * jQuery plugin:friendlyContact
 * 这个插件，用于提高表单验证的亲和性。
 * author:陈瀚 Chen Han
 * first modify:  2011/5/25  
 * last modify: 2011/~/~ 
 * 
 * 用法示例:
 * <script src="jquery.js"></script>
 * <script src="jquery.contact.js"></script>
 * <script>
 * $(function(){
 *  $("#form").friendlyContact({
 *                             need:["type","name","tel"],  该元素的内容不能为空
 *                             nocheck:["email"],           对该元素不进行检查，即使该元素的内容明显有误。
 *                             [confirm:true]
 *                             [reg:{  
 *                                                          各类正则检查模式，以名字对应
 *                                                          插件默认支持name,[e]mail,url,id,number,tel,post[zip]几种模式
 *                                                          但可以通过手动指定对这些模式进行扩张。
 *                                  }]
 *                             [filter:/reg/g]              默认的过滤器，针对所有项目 
 *                             [defaultError:text]          错误提示消息设置，需要实现至少三个部分（缺少输入，输入内容错误，输入正确）
 *                              错误提示示例： <br /><span class=\"alert Null\" style=\"display:none;color:red\">缺少输入的错误提示</span><span class=\"alert Unmatch\" style=\"display:none;color:red\">不正确的输入提示</span><span class=\"alert Green\" style=\"display:none;color:green\">正确输入提示</span>
 *  })
 * })
 *</script> 
 */
(function(jQuery) {
     jQuery.fn.changeType=function(type){
	 return jQuery( jQuery.extend( this.get(0), {type:type} ) );
     }
     jQuery.fn.friendlyContact = function(options) {
	 jQuery.fn.friendlyContact.defaults = {
	     need:[],
	     nocheck:[],
	     confirm:true,
	     reg:{
		 name:/^[あ-んァ-ヾ一-龠]+$/,
		 email:/^[\w\-\.]+@[\w-]+(\.[\w]+)+$/,
		 kana:/^[ァ-ヾ]*$/,
		 mail:/^[\w\-\.]+@[\w-]+(\.[\w]+)+$/,
		 url:/^https?:\/\/([^/:]+)(:(\d+))?(\/.*)?$/,
		 id:/^[a-zA-Z0-9]$/,
		 number:/^\s*(\d+([-\s]\d+)*)$/,
		 postzip:/^\s*(\d+([-\s]\d+)*)$/,
		 post:/^\s*(\d+([-\s]\d+)*)$/,
		 tel:/^\s*(\d+([-\s]\d+)*)$/,
		 company:/^[あ-んァ-ヾ一-龠\w\s,、，。@\-]*$/,
		 departments:/^[あ-んァ-ヾ一-龠\w\s,、，。@\-]*$/,
		 address:/^[あ-んァ-ヾ一-龠\w\s,、，。@\-]*$/
	     },
	     filter:/<[^\d](?:"[^"]*"|'[^']*'|[^'">*])*>/g,
	     defaultError:"<br /><span class=\"alert Null\" style=\"display:none;color:red\">該当項目は必須入力項目です。必ず入力してください。</span><span class=\"alert Unmatch\" style=\"display:none;color:red\">該当項目を正しく入力してください。</span><span class=\"alert Green\" style=\"display:none;color:green\">該当項目は適切に入力されています。</span>",
	     extra:function(){
		 return true;
	     }
	 };
	 //取得用户参数对象
	 var o = jQuery.extend(true,{}, jQuery.fn.friendlyContact.defaults, options);
	 //检查self是否是form对象，否则返回未定义对象
	 var self = jQuery(this);
	 if(jQuery.nodeName(this,"form")){
	     return undefined;
	 }
	 //定义submited对象，用于防止多重提交。
	 var not_submit_yet=true;
	 //定义body对象，用于可能的scroll
	 var body=(window.opera)?(document.compatMode=="CSS1Compat"?$('html'):$('body')):$('html,body');
	 //默认错误消息
	 var defaultError=$(o.defaultError);
	 //检查是否确认模式，准备确认用的按键及事件
	 if(o.confirm){
	     var back=$("<input id=\"jquery_contact_back\" type=\"button\" value=\"入力し直す \">");
	     back.click(function(){
			    o.confirm=true;
			    objects.unConfirm();
			});
	 }
	 //搜索form里的submit，如包含则修改其type为button,并保存该元素
	 //否则寻找button元素,若button也不存在则在form的最后自动添加。
	 var submit=$(":submit",self).changeType("button");
	 if( !submit.get(0) || !submit.get(0).nodeName ){
	     submit=$(":button",self).eq(0);
	     if( !submit.get(0) || !submit.get(0).nodeName ){
		 submit=$("<input type=\"button\" value=\"送信する\">");
		 self.append(submit);
	     }
	 }
	 //form中一般可能存在的填入项目包括input,textarea,checkbox,radio,select
	 var objects=$("input:not(:button),textarea,checkbox,radio,select",self);
	 //定义一个可用于确认模式的表达元素池以及提交用的隐藏元素池。
	 var pool=$([]);
	 var hidden_pool=$([]);
	 //定义一个errorList的构造器
	 var errorList=function(){
	     return {
		 //长度获取
		 len:function(){
		     var cnt=0;
		     for(i in this){
			 if( i!=="len" && i!=="scrolling" )cnt++;
		     }
		     return cnt;
		 },
		 //如果有错误就移动到错误所在地
		 //如果有多个错误则移动到最上方的错误所在地
		 scrolling:function(){
		     var to=null;
		     for(i in this){
			 if( i!=="len" && i!=="scrolling" ){
			     if( to===null ){
				 to=$("[name='"+i+"']",self).offset().top;
			     }else{
				 to=Math.min(to,$("[name='"+i+"']",self).offset().top); 
			     }
			 }
		     }
		     if( to!==null )body.animate({scrollTop: to},300);
		     return false;
		 }
	     };
	 };
	 objects.errorList=errorList();
	 //定义检查方法以便后期调用，该方法应该是处理单一对象而无返回值的。
	 var objectCheck=function(){
	     var error=false;
	     var name=$(this).attr("name").toLowerCase();
	     if( $.inArray(name,o.nocheck)>=0 ){
		 return true;
	     }
	     if($(this).attr("type")){
		 var type=$(this).attr("type").toLowerCase();
	     }
	     if( type==="checkbox" || type==="radio" ){
		 var checked;
		 $(":"+type+"[name='"+name+"']").each(function(){
							  return !( checked=$(this).attr("checked") );
					     });
		 if( !checked && ($.inArray(name,o.need) >= 0) ){
		     error=true;
		     objects.errorList[name]="Null";
		 }
	     }else if( $.nodeName(this,"select") ){
		 if( (!$(this).val() && ($.inArray(name,o.need) >= 0)) || $(this).val()=='0'){
		     error=true;
		     objects.errorList[name]="Null";
		 }
	     }else if( $.nodeName(this,"input") || $.nodeName(this,"textarea") ){
		 if( !$(this).val() && ($.inArray(name,o.need) >= 0) ){
		     error=true;
		     objects.errorList[name]="Null";
		 }else if( !!$(this).val() ){
		     //先从元素的name属性中选择应该使用的正则表达式，如果没有对应的正则表达式则对元素的值进行html标签过滤。
		     if( o.reg[name]!==undefined ){
			 if( !o.reg[name].test( $(this).val() ) ){
			     error=true;
			     objects.errorList[name]="Unmatch";
			 }
		     }else{
			 if( o.filter.test( $(this).val() ) ){
			     error=true;
			     objects.errorList[name]="Unmatch";
			 }
		     }
		 }
	     }
	     if( $.inArray(name,o.need)===-1  &&  !$(this).val() ){
		 return true;
	     }
	     //如果有错，则显示错误提示信息
	     //否则取消提示。
	     var tips=$("span.alert",$(this).parent());
	     tips.css("display","none");
	     if( error ){
		 type=objects.errorList[name];
		 $("."+type,$(this).parent()).css("display","");
	     }else{
		 type="Green";
		 $("."+type,$(this).parent()).css("display","");
		 delete objects.errorList[name];
	     }
	 };
	 //检查元素们的位置，如果其直接为form的子元素则加入中间层，并检查是否包含足够的错误提示信息，否则自动添加错误提示信息。
	 //其中check和radio元素的后半部分如果不是label而是普通的文本节点，则替换为label，并加入一些简单的动画效果
	 //最后，为所有元素添加"blur"方法
	 objects.each(function(){
			  var obj=$(this);
			  if(obj.attr("type")){
			      var _type=obj.attr("type").toLowerCase();
			      var type=_type==="checkbox"?"checkbox":_type==="radio"?"radio":false;
			      if( type ){
				  obj.bind("click",objectCheck);
			      }
			  }
			  if( $.nodeName( obj.parent().get(0),"form") ){
			      if( type ){
				  var name=obj.attr("name");
				  var target=$(":"+type+"[name='"+name+"']");
				  var group=$([]);
				  var cnt=0;
				  target.each(function(){
						  if( $(this).attr("type").toLowerCase()!==type ){
						      return true;
						  }
						  cnt++;
						  if( $.nodeName($(this).next().get(0),"label") ){
						      group.push( this,$(this).next().get(0) );
						  }else if(  this.nextSibling.nodeType===3 ){
						      if( !!$(this).attr("id") ){
							  var id=$(this).attr("id");
						      }else{
							  var id=$(this).attr("name")+cnt;
							  $.extend(this,{id:id});
						      }
						      var label=$(this.nextSibling).wrap("<label for='"+id+"'></label>").parent();
						      group.push( this,label.get(0) );
						  }
						  //点击自动变色
						  $(this).click(function(){
								    var id=$(this).attr("id");
								    var label=$("label[for='"+id+"']",self);
								    if( /background/i.test(label.attr("style")) ){
									label.css("background","");
								    }else{
									label.css("background","#ffcccc");
								    }
								});
					      });
				  group.wrapAll("<span></span>");
				  return true;
			      }else{
				  if( $.nodeName(this,"input") || $.nodeName(this,"textarea") ){
				      $(this).focus(function(){
							$(this).css("background","#D6D6FF");
						    });
				      $(this).blur(function(){
						       $(this).css("background","");
						    });
				  }
				  obj.wrap("<span></span>");
			      }
			      obj.parent().append( defaultError.clone() );
			  }else if( $(".alert",obj.parent()).length===0 ){
			      obj.parent().append( defaultError.clone() );    			  
			  }
			  obj.bind("blur",objectCheck);
		      });
	 //其中检查值有效性的包括checkbox,radio,select。需要检查值内容正确的包括input,textarea
	 objects.check=function(){
	     $(this).each( objectCheck );
	 };
	 //替换当前表单为确认页面，填入表达元素池和hidden元素池，隐藏原有元素集合。
	 objects.beConfirm=function(){
	     $(this).each(function(){
			      var dis=$("<span></span>");
			      var hid=$("<input type='hidden'>");
			      var obj=$(this);
			      if(obj.attr("type")){
				  var _type=obj.attr("type").toLowerCase();
			      }
			      var type=_type==="checkbox"?"checkbox":_type==="radio"?"radio":false;
			      if( type==="checkbox" || type==="radio" ){
				  if(this.checked){
				      $.extend( hid.get(0),{name:obj.attr("name"),value:obj.val()} );
				      dis.html( "&nbsp;"+obj.val()+"," );
				      pool.push( dis.get(0) );
				      hidden_pool.push( hid.get(0) );
				  }
				  $("label",obj.parent()).css("display","none");
			      }else if( this.nodeType===1 ){
				  $.extend( hid.get(0),{name:obj.attr("name"),value:obj.val()} );
				  dis.text( obj.val() );
				  pool.push( dis.get(0) );
				  hidden_pool.push( hid.get(0) );
			      }
			      obj.after(dis).after(hid);
			  });
	     $(this).css("display","none");
	     pool.css("display","");
	     $("span.alert").css("display","none");
	     submit.data("store",submit.val()).val("上記内容を確認した，送信する").after(back);
	 };
	 //把确认页面恢复为表单，重新初始化表达元素池和hidden元素池，恢复原有元素集合表达。
	 objects.unConfirm=function(){
	     pool.remove();
	     hidden_pool.remove();
	     pool=$([]);
	     hidden_pool=$([]);
	     $(this).each(function(){
			      var obj=$(this);
			      var _type=obj.attr("type").toLowerCase();
			      var type=_type==="checkbox"?"checkbox":_type==="radio"?"radio":false;
			      if( type==="checkbox" || type==="radio" ){
				  $("label",obj.parent()).css("display","");
			      }
			  });
	     $(this).css("display","");
	     submit.val( submit.data("store") );
	     back.detach();
	 };
	 //提交动作,判断是否确认模式,如果是确认模式,则显示返回按键。
	 submit.click(function(){
			  objects.errorList=errorList();
			  objects.check();
			  if( objects.errorList.len() > 0 ){
			      return objects.errorList.scrolling();
			  }
			  if( !o.extra() ){
			      return false;
			  }
			  if(o.confirm){
			      objects.beConfirm();
			      o.confirm=false;
			  }else{
			      if(not_submit_yet){
				  not_submit_yet=false;
				  if( !pool.length ){
				      objects.beConfirm();
				  }
				  //删除元素集合，表达元素池，只保留隐藏元素池用于提交。
				  pool.remove();
				  objects.remove();
				  self.get(0).submit();
			      } 
			  }
		      });
	 return self;
     };
 })(jQuery);