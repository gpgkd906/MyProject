$(function(){
      //下書きはajaxで行い
      $("#draft").click(function(){
			    $("#status").val("下書き");
			    var form=$("form").eq(0);
			    var id=$("[name=\"id\"]");
			    var obj={
				title:$("[name=\"title\"]").val(),
				contents:$('iframe:first').contents().find('.wysiwyg').html(),
				year:$("select[name=\"year\"]").val(),
				month:$("select[name=\"month\"]").val(),
				day:$("select[name=\"day\"]").val(),
				status:$("#status").val(),
				ajax:1
			    };
			    if(id.val()){ obj.id=id.val(); }
			    $.ajax({
				       type:"POST",
				       url:window.location.href.replace(/\/view\/?$/,"")+"/post_news",
				       datatype:"json",
				       data:obj,
				       success:function(){
					   id.val()||id.val(1);
					   alert("下書き保存は完了しました");
				       },
				       error:function(){
					   //do nothing
				       }
				   });
			});
      $("#Checkall").click(function(){
			       var status=$(this).attr("checked");
			       $(":checkbox",$(".data_tb")).each(function(){
								     $(this).attr("checked",status); 
								 });
			   });
      $("select",$(".post_tb")).each(function(){
					 $(this).val(time[$(this).attr('name')]);
				     });
      $('iframe:first').contents().find('.wysiwyg').focus();
      $(":submit").each(function(){
			   $(this).click(function(){
					     checkUnload=false;
					 });
		       });
      $(":button").each(function(){
			    $(this).click(function(){
					      checkUnload=false;
					  });
			});
      $("a:not([href*='logout'])").each(function(){
		      $(this).bind('click',function(){
					checkUnload=false;
				    });
		  });
      $("a[href*='logout']").each(function(){
				      if( $(":submit").length<2 ){
					  $(this).bind('click',function(){
							   checkUnload=false;
						       });
				      }    
				  });
      $("a","#logo").each(function(){
			      $(this).unbind('click');
			  })
      window.onbeforeunload = function(e){
	  if(checkUnload){
	      return "ページを離れるとデータは失われます";
	  }
      }
      $(":submit").eq(1).click(function(){
				   return confirm("選択したブログを削除してもいいですか?"); 
			       });
  });

var checkUnload=true;
var ajaxFileUpload=function(domain){
    $("#loading")
	.ajaxStart(function(){
		       $(this).show();
		   })
	.ajaxComplete(function(){
			  $(this).hide();
		      });
    $.ajaxFileUpload
    (
	{
	    url:domain+'admin/uploadImage',
	    secureuri:false,
	    fileElementId:'image',
	    dataType: 'image',
	    success: function (data, status)
	    {
		if(data.match(/http/)){
		    $(".content","#facebox").html("").append("<img id=\"response_image\" src="+data+">");
		    $("#response_image").data("response",data);		   
		}else{
		    alert(data);
		}
	    },
	    error: function (data, status, e)
	    {
		
	    }
	});
    return false;    
}
