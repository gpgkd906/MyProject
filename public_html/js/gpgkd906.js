var mt_rand=function(b,c){var d=Math.round(Math.random()*c);return d<b?mt_rand(b,c):d},var_dump=function(b,c,d){c=typeof c!="undefined"?c:1;d=typeof d!="undefined"?d:c;var e=("/*\n  author han chen (\u9648\u701a) (http://chenhan120.iobb.net)\n  version 1.0\n*/\n\u89e3\u6790\u6df1\u5ea6\u8bbe\u5b9a:\nArray:"+d+"\u5c42\nObject:"+c+"\u5c42\n").replace(/0\u5c42/g,"\u65e0\u9650\u5236");if(!isset(b))return alert(e+"null Object!!");var i=function(g,l,h){var j="",k="";h++;for(var f in g){if(!g.hasOwnProperty(f))continue;k=typeof g[f]!=="object"&&typeof g[f]!=="function"?JSON.stringify(g[f]):typeof g[f]==="function"?g[f]:g[f]instanceof Array?h<d||!d?"["+i(g[f],"array",h)+"]":"[array Array]":h<c||!c?"{\n"+i(g[f],"object",h)+"\n"+"    ".repeat(h)+"}":"[object Object]";if(l==="object")j+=j===""?"    ".repeat(h)+f+":"+k:",\n"+"    ".repeat(h)+f+":"+k;else if(l==="array")j+=j===""?k:","+k}return j};return typeof b!=="object"&&typeof b!=="function"?alert(e+typeof b+":"+JSON.stringify(b)):typeof b==="function"?alert(e+"function:"+b):b instanceof Array?alert(e+"array:["+i(b,"array",0)+"]"):alert(e+"object:{\n"+i(b,"object",0)+"\n}")},htmlspecialchars=function(b){return b.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/'/g,"&#039;")},esc=function(b){if(typeof b==="object"){for(a in b)b[a]=esc(b[a]);return b}else return typeof b==="string"?htmlspecialchars(b):b},isset=function(b){return typeof b!="undefined"},empty=function(b){if(typeof b=="undefined"||b===null||b==="")return true;if(typeof b=="number"&&isNaN(b))return true;if(b instanceof Date&&isNaN(Number(b)))return true;if(b instanceof Array&&!b.length)return true;if(typeof b=="object"){var c=0;for(n in b)c++;return!c}return false},is_array=function(b){return b instanceof Array};

var getscript=function(script,option)
{
    var group=document.getElementsByTagName("script");
    for(i in group){
	if(isset(group[i].src) && group[i].src.match(script)){
	    group[i].parentNode.removeChild(group[i]);
	}
    }
    //
    var obj=group[0];
    var add=document.createElement("script");
    var exts=[],ext="?randid="+mt_rand(1,10000);
    if(!script.match(/\.(js|php)$/))script=script+".js";
    if(!!option){
	for(key in option){
	    exts.push(key+"="+option[key]);
	}
	ext+="&"+exts.join("&");
    }
    add.type="text/javascript";
    add.async=false;
    add.src=script+ext;
    document.head.appendChild(add);
    return true;
};

var buildScript=function(script,option){
    var exts=[],ext="?randid="+mt_rand(1,10000);
    if(!script.match(/\.(js|php)$/))script=script+".js";
    if(!!option){
	for(key in option){
	    exts.push(key+"="+option[key]);
	}
	ext+="&"+exts.join("&");
    }
    return script+ext;
}

var array_combine=function(array1,array2){
    if(!is_array(array1) || !is_array(array2)){
	return alert("渡されたパラメタは配列ではありません");
    }
    var len=array1.length;
    var obj={};
    for(var i=0;i<len;i++){
	obj[array1[i]]=array2[i];
    }
    return obj;
}

//error
var error=function(a,b){
    e=new Error;
    if(!!b){
	e.name=a;
	e.message=b;
    }else{
	e.message=a;
    }
    throw e;
}

var parseQueryString=function(queryString){
	var group=queryString.split("&");
	var item;
	var _get={}
	for(var i in group){
		if(!group.hasOwnProperty(i)){
			continue;
		}
		item=group[i].split("=");
		_get[item[0]]=item[1];
	}
	return _get;
}
//javascript extension
String.prototype.nl2br=function(){
    return this.replace(/\r\n/g,"<br/>").replace(/\n/g,"<br/>").replace(/\r/g,"<br/>");
}

String.prototype.br2nl=function(){
    return this.replace(/<br>/g,"\r\n").replace(/<br\/>/g,"\r\n");
}

String.prototype.repeat=function(cnt,join){
    var add=[];
    for(i=0;i<cnt-1;i++){
	add.push( !!join?this+join:this )
    }
    add.push(this);
    return this.replace(this,add.join(""));
}

if(!isset(String.prototype.trim)){
    String.prototype.trim=function(){
	return this.replace(/^[\s\t\r\n]*/,"").replace(/[\s\t\r\n]*$/,"");
    }
}

Array.prototype.unique=function(){
    var c=[],d=[],e;
    for(e in this){
	if( typeof this[e]=='function'){
	    continue;
	}
	if(!d[this[e]]){
	    d[this[e]]=1;
	    c.push(this[e]);
	}
    }
    this.splice(0);
    for(e in c){
	if( typeof this[e]=='function'){
	    continue;
	}
	this.push(c[e]);
    }
    return this;
}

Array.prototype.del=function(elem){
    var c=[];
    for(e in this){
	if( typeof this[e]=='function' || this[e]===elem ){
	    continue;
	}
	c.push(this[e]);
    }
    this.splice(0);
    for(e in c){
	if( typeof this[e]=='function'){
	    continue;
	}
	this.push(c[e]);
    }
    return this;
}

Date.prototype.microtime=function(){
    return this.valueOf();
    //return Date.parse(this.toUTCString());
}

Date.prototype.nextDay=function(){
    this.setDate(this.getDate()+1);
    return this;
}

Date.prototype.nextMonth=function(){
    this.setMonth(this.getMonth()+1);
    return this;
}

Date.prototype.format=function(format){
    if(!format){
	format="Y-m-d H:i:s";
    }
    var dow={0:"Sun",1:"Mon",2:"Tue",3:"Wed",4:"Thu",5:"Fri",6:"Sat"};
    var jdow={0:"日曜日",1:"月曜日",2:"火曜日",3:"水曜日",4:"木曜日",5:"金曜日",6:"土曜日"};
    var moy={0:"Jan",1:"Feb",2:"Mar",3:"Apr",4:"May",5:"Jun",6:"Jul",7:"Aug",8:"Sep",9:"Oct",10:"Nov",11:"Dec"}
    var jmoy={0:"一月",1:"二月",2:"三月",3:"四月",4:"五月",5:"六月",6:"七月",7:"八月",8:"九月",9:"十月",10:"十一月",11:"十二月"}
    return format.replace("Y",this.getFullYear()).replace("m",(this.getMonth()+1)<10?"0"+(this.getMonth()+1):(this.getMonth()+1)).replace("d",this.getDate()<10?"0"+this.getDate():this.getDate()).replace("j",this.getDate()).replace("H",this.getHours()<10?"0"+this.getHours():this.getHours()).replace("h",this.getHours()>12?this.getHours()-12:this.getHours()).replace("i",this.getMinutes()<10?"0"+this.getMinutes():this.getMinutes()).replace("s",this.getSeconds()<10?"0"+this.getSeconds():this.getSeconds()).replace("w",this.getDay()).replace("y",this.getFullYear().toString().substring(2,4)).replace("JD",jdow[this.getDay()]).replace("D",dow[this.getDay()]).replace("JF",jmoy[this.getMonth()]).replace("F",moy[this.getMonth()]).replace("a",this.getHours()>12?"pm":"am").replace("A",this.getHours()>12?"PM":"AM");
}

//trans only
var object_sum=function(obj){
    var sum=0;
    for( i in obj){
	if(obj.hasOwnProperty(i)){
	    sum+=Number(obj[i]);
	}
    }
    return sum;
}

Number.prototype.toTime=function(){
    var time=this,microsec=0,second=0,minute=0,hour=0,day=0,res="";
    microsec = time % 1000;
    res = microsec + "ミニ秒";
    if( time == microsec ){
	return res;
    }
    time = Math.floor( time / 1000 );
    second = time % 60 ;
    res = second + "秒" + res;
    if( time == second ){
	return res;
    }
    time = Math.floor( time / 60 );
    minute = time % 60;
    res = minute + "分" + res;
    if( time == minute ){
	return res;
    }
    time = Math.floor( time / 60 );
    hour = time % 24;
    res = hour + "時" +res;
    if( time == hour ){
	return res;
    }
    day = Math.floor( time / 24 );
    return day + "日" + res;
}

//HTML5 extension
if(isset(window.localStorage)){
    //prototypeと関数を除いて，全データタイプに対応する
    Storage.prototype.get=function(key){
	try{
	    return JSON.parse(this.getItem(key));
	}catch(e){
	    return this.getItem(key);
	}
    }
    Storage.prototype.set=function(key,val){
	this.setItem(key,JSON.stringify(val));
    }
}

//pipeのインスタンス作成。
//localStoreageを補完するようなオブジェクト，ページが閉じると，保存したデータは消されますが，localStorageと同じインタフェースで扱うことができます,将来的に次の_オブジェクトにまとめる予定です
var pipe=function(){
    var obj={};
    return {
	set:function(key,value){
	    obj[key]=value; 
	},
	get:function(key){
	    return obj[key];
	}
    }
}();
window._debug=[];

//
(function(w){
    w._=function(){
	return {
	    Storage:function(){
		var obj={};
		return {
		    set:function(key,value){
			obj[key]=value; 
		    },
		    get:function(key){
			return obj[key];
		    },
		    setItem:function(key,value){
			obj[key]=value.toString();
		    },
		    getItem:function(key){
			return JSON.stringify(obj[key]);
		    },
		}
	    }(),
<<<<<<< HEAD
	    mt_rand:function(b,c){var d=Math.round(Math.random()*c);return d<b?mt_rand(b,c):d},
	    var_dump:function(b,c,d){c=typeof c!="undefined"?c:1;d=typeof d!="undefined"?d:c;var e=("/*\n  author han chen (\u9648\u701a) (http://chenhan120.iobb.net)\n  version 1.0\n*/\n\u89e3\u6790\u6df1\u5ea6\u8bbe\u5b9a:\nArray:"+d+"\u5c42\nObject:"+c+"\u5c42\n").replace(/0\u5c42/g,"\u65e0\u9650\u5236");if(!this.isset(b))return alert(e+"null Object!!");var i=function(g,l,h){var j="",k="";h++;for(var f in g){if(!g.hasOwnProperty(f))continue;k=typeof g[f]!=="object"&&typeof g[f]!=="function"?JSON.stringify(g[f]):typeof g[f]==="function"?g[f]:g[f]instanceof Array?h<d||!d?"["+i(g[f],"array",h)+"]":"[array Array]":h<c||!c?"{\n"+i(g[f],"object",h)+"\n"+"    ".repeat(h)+"}":"[object Object]";if(l==="object")j+=j===""?"    ".repeat(h)+f+":"+k:",\n"+"    ".repeat(h)+f+":"+k;else if(l==="array")j+=j===""?k:","+k}return j};return typeof b!=="object"&&typeof b!=="function"?alert(e+typeof b+":"+JSON.stringify(b)):typeof b==="function"?alert(e+"function:"+b):b instanceof Array?alert(e+"array:["+i(b,"array",0)+"]"):alert(e+"object:{\n"+i(b,"object",0)+"\n}")},
=======
	    mt_rand:function(b,c){var d=Math.round(Math.random()*c);return d<b?this.mt_rand(b,c):d},
	    var_dump:function(b,c,d){c=typeof c!="undefined"?c:1;d=typeof d!="undefined"?d:c;var e=("/*\n  author han chen (\u9648\u701a) (http://www.gpgkd906.com)\n  version 1.0\n*/\n\u89e3\u6790\u6df1\u5ea6\u8bbe\u5b9a:\nArray:"+d+"\u5c42\nObject:"+c+"\u5c42\n").replace(/0\u5c42/g,"\u65e0\u9650\u5236");if(!this.isset(b))return alert(e+"null Object!!");var i=function(g,l,h){var j="",k="";h++;for(var f in g){if(!g.hasOwnProperty(f))continue;k=typeof g[f]!=="object"&&typeof g[f]!=="function"?JSON.stringify(g[f]):typeof g[f]==="function"?g[f]:g[f]instanceof Array?h<d||!d?"["+i(g[f],"array",h)+"]":"[array Array]":h<c||!c?"{\n"+i(g[f],"object",h)+"\n"+"    ".repeat(h)+"}":"[object Object]";if(l==="object")j+=j===""?"    ".repeat(h)+f+":"+k:",\n"+"    ".repeat(h)+f+":"+k;else if(l==="array")j+=j===""?k:","+k}return j};return typeof b!=="object"&&typeof b!=="function"?alert(e+typeof b+":"+JSON.stringify(b)):typeof b==="function"?alert(e+"function:"+b):b instanceof Array?alert(e+"array:["+i(b,"array",0)+"]"):alert(e+"object:{\n"+i(b,"object",0)+"\n}")},
>>>>>>> update
	    htmlspecialchars:function(b){return b.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/'/g,"&#039;")},
	    isset:function(b){return typeof b!="undefined"},
	    empty:function(b){if(typeof b=="undefined"||b===null||b==="")return true;if(typeof b=="number"&&isNaN(b))return true;if(b instanceof Date&&isNaN(Number(b)))return true;if(b instanceof Array&&!b.length)return true;if(typeof b=="object"){var c=0;for(n in b)c++;return!c}return false},
	    is_array:function(b){return b instanceof Array},
	}
    }();
    //web sql (chrome||opera||safari) 
    if(!!w.openDatabase){
	w._.sql=function(){
	    var conn=w.openDatabase("app","1.0","gpgkd906",10*1024*1024);
	    var recordset=function(res){
		var offset=0;
		var length=res.rows.length;
		var insertId;
		try{ 
		    insertId=res.insertId; 
		}catch(e){ 
		    insertId=null;
		};
		return {
		    fetch:function(){
			if( offset  >=  length){
			    return null;
			}
			return res.rows.item(offset++);
		    },
		    insertId:insertId,
		    rowCount:res.rowAffected?res.rowAffected:res.rows.length,
		    mode:res.rowAffected?"Update":"Select"
		};
	    }
	    return {
		create:function(name,options){
		    var sql="create table if not exists {name}({id}{option})";
		    var option=[];
		    var noid=true;
		    var _id="";
		    for(i in options){
			option.push(i+" "+options[i].join(" "));
			if(i=="id"){
			    noid=false;
			}
		    }
		    if(noid){
			_id="id integer primary key autoincrement,";
		    }
		    this.query(sql.replace("{id}",_id).replace("{name}",name).replace("{option}",option.join()));
		},
		execute:function(){
		    var _res=false;
		    var self=this;
		    var sql="";
		    var param=[];
		    var callback,error,success;
		    var arg;
		    for(i=0;i<arguments.length;i++){
			arg=arguments[i];
			switch(typeof arg){
			case typeof sql:
			    sql=arg;
			    break;
			case typeof param:
			    param=arg;
			    break;
			case typeof Function:
			    if(!callback){
				callback=arg;
			    }else if(!error){
				error=arg;
			    }else if(!success){
				success=arg;
			    }
			    break;
			}
		    }
		    conn.transaction(
			function(con){
			    con.executeSql(sql,param,function(con,res){
				if(!!callback){
				    callback(self,recordset(res));
				}
			    });
			},
			function(e){
			    if(!error){
				console.log(e.code+":"+e.message);
			    }else{
				error(e);
			    }
			},
			function(){
			    if(!!success){
				success();
			    }
			}
		    );
		},
	    }
	}();
    }
    //web indexedDB
})(window);
if(!window.jQuery){
	getscript("http://code.jquery.com/jquery-latest.min.js");
}
