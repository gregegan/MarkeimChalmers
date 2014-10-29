var wpversion;
jQuery(document).ready(function($) {
	//alert('got');
	var wpversion = $('meta[name=wpversion]').attr("content");
	//alert($('meta[name=wpversion]').attr("content"));
	$(".postbox h3.advps-expand").click(function(){
		//alert($(this).parent().attr('class'));
		if($(this).parent().hasClass('closed')){
			$(this).parent().removeClass('closed')
		}
		else
		{
			$(this).parent().addClass('closed')
		}
	});
	
	if(wpversion >= '3.5'){
	 	$(".advps-color-picker").wpColorPicker();
	}
	else{
		 jQuery('.advpsfarb').hide();
		 $('.advpsfarb').each(function() {
			 //alert();
			 var sell = $(this).parent().find('.advps-color-picker').attr('id');
			 //alert(sell);
			 jQuery(this).farbtastic("#"+sell);
		 });
		 
		 $('.advps-color-picker').click(function() {
        	$(this).parent().find('.advpsfarb').fadeIn();
		});
	
		$(document).mousedown(function() {
			$('.advpsfarb').each(function() {
				var display = $(this).css('display');
				if ( display == 'block' )
					$(this).fadeOut();
			});
		});
	 }//
});

/*function checkPager(){
	var pagerVal = jQuery("#ex_pager").val();
	//alert(pagerVal); 
	if(pagerVal == 'yes'){
		jQuery(".pagerfld").fadeOut(500,'linear',function(){});
	}
	else
	{
		jQuery(".pagerfld").fadeIn(500,'linear',function(){});
	}
}
function checkNextPrev(){
	var nxtprvVal = jQuery("#ex_nxtprv").val();
	//alert(pagerVal); 
	if(nxtprvVal == 'yes'){
		jQuery(".nxtprvfld").fadeOut(500,'linear',function(){});
	}
	else
	{
		jQuery(".nxtprvfld").fadeIn(500,'linear',function(){});
	}
}*/
function onlyNum(evt)
{
    var e = window.event || evt;
    var charCode = e.which || e.keyCode;

    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;

}

/*function advpsUpdateOpt(a,f){
	//alert(n);
	if(a==2){
		var chkConf = confirm("Do you realy want to dele this option set ?");
		if(chkConf){
			jQuery("#advps_ack"+f).val('delete');
			jQuery("#updateOptFrm"+f).submit();
		}
	}
	else{
		jQuery("#advps_ack").val('update');
		jQuery("#updateOptFrm").submit();
	}
}*/
function advpsCheckCat(p,n){
	//alert(jQuery('#advps-cat-field').html());
	//alert(p);
	//return;
	var fldSel = 'advps-cat-field'+n;
	if(p != "page"){
		jQuery.ajax({
			  type : "post",
			  context: this,
			  dataType : "html",
			  url : advpsajx.ajaxurl, 
			  data : {action: "chkCaetegory",post_type:p,checkReq:advpsajx.advpsAjaxReqChck},        	
			  success: function(response) {
				 //alert(response);return;
					jQuery('#'+fldSel).html(response);
				},
				complete : function(){
					
				}
		});///
	}
	else
	{
		jQuery('#'+fldSel).html('');
	}
}

