$(function(){
    $.datepicker.regional['ja'] = {
	closeText: '閉じる',
	prevText: '&#x3c;前',
	nextText: '次&#x3e;',
	currentText: '今日',
	monthNames: ['1月','2月','3月','4月','5月','6月',
		     '7月','8月','9月','10月','11月','12月'],
	monthNamesShort: ['1月','2月','3月','4月','5月','6月',
			  '7月','8月','9月','10月','11月','12月'],
	dayNames: ['日曜日','月曜日','火曜日','水曜日','木曜日','金曜日','土曜日'],
	dayNamesShort: ['日','月','火','水','木','金','土'],
	dayNamesMin: ['日','月','火','水','木','金','土'],
	weekHeader: '週',
	dateFormat: 'yy/mm/dd',
	firstDay: 0,
	isRTL: false,
	showMonthAfterYear: true,
	yearSuffix: '年'};
    $.datepicker.setDefaults($.datepicker.regional['ja']);
    $("#date").datepicker();
    var today=new Date();
    today.setHours(0);
    today.setMinutes(0);
    today.setSeconds(0);
    var todayDay=today.getDay();
    var todayValue=today.valueOf();
    var ext=2,ext_check=false;
    var ext_param=[];
    if( todayDay=== 5 || todayDay === 4 ){
	ext=2;
    }
    if( todayDay=== 6 ){
	ext=1;
    }
    var _rest=[],_day;
    for(_i in rest){
	if(rest.hasOwnProperty(_i) && rest[_i]!==""){
	    _day=new Date(rest[_i]);
	    _rest.push(_day.valueOf());
	}
    }
    $("#date").datepicker('option','beforeShowDay',function(date){
	var notWeekEnd=(date.getDay() != 0 && date.getDay() != 6);
	var dateValue=date.valueOf();
	var dayCheck=(ext_param.indexOf(dateValue)===-1 && dateValue > todayValue && _rest.indexOf(dateValue)===-1 && notWeekEnd);
	if( !ext_check && dayCheck  ){
	    ext=ext-1;
	    ext_param.push(dateValue);    
	}
	if( ext==0  ){
	    ext_check=true;
	}
	var ret = [notWeekEnd && !holiday[dateValue] && ext_check && dayCheck  ];
	if(!ret[0]){
	    if(_rest.indexOf(dateValue)>-1){
		flag="rest";
		ret.push(flag);
	    }
	}
	return ret;
    });
});
var holiday={};
var entry;
var fetchHoliday=function(cal){
    if(typeof(cal)==typeof("")){
	cal=$.parseJSON(cal);
    }
    if(!!cal.feed && !!cal.feed.entry){
	for(key in cal.feed.entry){
	    entry = cal.feed.entry[key];
	    holiday[ new Date(entry.gd$when[0].startTime.replace(/-/g,"/")).valueOf() ] = entry.title.$t;
	}
    }    
}