//References
var loading = $("#loading");
var sub_content = $("#sub_content");
var msg = $("#message");	
sub_content.dialog({
	autoOpen: false,
	width: 920,
	modal: true,
	show: "blind",
	//hide: "explode",	
	close: function() {
	if(from_proj==1){}else 	if(from_report==1){location.reload(); }
	else{calendar.fullCalendar('refetchEvents');
}
	}
});
function showMessage(color){msg .css({visibility:"visible"}) .css({opacity:"1"}) .css({display:"block"}); if(color == 'undefined' || color == '')color = '#006'; msg.css({backgroundColor: color}); msg.delay(8000); msg.fadeOut('slow');}
function showLoading(){loading .css({visibility:"visible"}) .css({opacity:"1"}) .css({display:"block"});msg .css({visibility:"hidden"})}
//hide loading bar
function hideLoading(){loading.fadeTo(1000, 0, function(){loading .css({display:"none"});});};
function loadTimesheet(dataString){
showLoading();
$.ajax({type: 'POST',url: 'addtime.php',data: dataString,dataType: 'html',success: function(html){
sub_content.html(html);
//initTime();
sub_content.dialog( "open" );
hideLoading();
//var sp=dataString.split('=');
//initDatetimepicker(sp[1]);
}});
}
function calculate_hrs(dateText){
   var st = new Date($('#start_date').val());
   var ed = new Date(dateText);   
   var lunch_hrs = 0;
   var diff = ed.getTime() - st.getTime();
  // $('#hdn_start_date').val(Math.round(st.getTime()/1000));
  // $('#hdn_end_date').val(Math.round(ed.getTime()/1000));
  $('#hdn_start_date').val($('#start_date').val());
  $('#hdn_end_date').val($('#end_date').val());
   var hrs_diff = Math.round((diff/1000/60/60)*100)/100;
  // alert(hrs_diff);
 //  if(hrs_diff > 0)
 //alert($('#lunch_st').val());
   {	 
	 if(hrs_diff >= 4){$('#break_1').removeClass('tr_hide');}else if(!$('#break_1').hasClass('tr_hide'))$('#break_1').addClass('tr_hide');
	 if(hrs_diff >6){$('#lunch').removeClass('tr_hide');if($('#lunch_st').val() == ''){ if($('#lunch_chk').is(':checked') == true)alert('Please enter Lunch Start and End Time !');}}else if(!$('#lunch').hasClass('tr_hide')){$('#lunch').addClass('tr_hide');$('#lunch_st').val('');$('#lunch_ed').val('');} 
	 var lunch_st =  new Date(Date.parse($('#lunch_st').val(), "m/d/yyyy HH:mm:ss"));
	 var lunch_ed =  new Date(Date.parse($('#lunch_ed').val(), "m/d/yyyy HH:mm:ss")); 
//alert(lunch_st.getTime());	 
	 var lunch_diff = lunch_ed.getTime() - lunch_st.getTime();
	 if(lunch_diff > 0){ lunch_hrs = Math.round((lunch_diff/1000/60/60)*100)/100;}
	 if(lunch_hrs > 1){if(confirm('Are You Sure ?')==false){lunch_hrs = 0; $('#lunch_ed').val('');}}
	 $('#lunch_hrs').val(lunch_hrs);
	 if(hrs_diff > lunch_hrs) hrs_diff -= lunch_hrs; else hrs_diff = 0;
	 hrs_diff = Math.round(hrs_diff*100)/100;
	 $('#hrs_work').val(hrs_diff);
	 if(hrs_diff >= 8){$('#break_2').removeClass('tr_hide');$('#reg_hrs').val(8);$('#ot').val(Math.round((hrs_diff - 8)*100)/100);}else { $('#reg_hrs').val(hrs_diff); $('#ot').val(0); if(!$('#break_2').hasClass('tr_hide'))$('#break_2').addClass('tr_hide');}
	 if($('#ot').val()>4){$('#ot').val(4);$('#dt').val(Math.round((hrs_diff - 12)*100)/100);}else{$('#dt').val(0);}
   }
  // else
   {
  /*( $('#break_1').addClass('tr_hide');
   $('#lunch').addClass('tr_hide');
   $('#break_2').addClass('tr_hide');
      $('#lunch_st').addClass('tr_hide');
	     $('#lunch_ed').addClass('tr_hide');*/
   }
}
function calculate_odo(){
	if($('#odo_end').val() > $('#odo_start').val()){var diff = $('#odo_end').val() - $('#odo_start').val(); $('#odo_daily').val(diff); $('#odo_reim').val(((diff > 30)?diff - 30:0));} else {$('#odo_daily').val(0);$('#odo_reim').val(0);}
}
function calculate_misc(){
	var food = isNumber($('#odo_food').val()) ? $('#odo_food').val() : 0;$('#odo_food').val(Number(food));
	var lodging = isNumber($('#odo_lodging').val()) ? $('#odo_lodging').val() : 0;$('#odo_lodging').val(Number(lodging));
	var toll = isNumber($('#odo_toll_fees').val()) ? $('#odo_toll_fees').val() : 0;$('#odo_toll_fees').val(Number(toll));
	var park = isNumber($('#odo_park_fees').val()) ? $('#odo_park_fees').val() : 0;$('#odo_park_fees').val(Number(park));
	var misc = isNumber($('#odo_misc').val()) ? $('#odo_misc').val() : 0;$('#odo_misc').val(Number(misc));
	var sum = (Number(food) +  Number(lodging) +  Number(toll) +  Number(park) + Number(misc)).toFixed(2);
	$('#odo_mis_exp').val(((sum*100)/100));
}
function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}
