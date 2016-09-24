var hrefs = [];
var targets = [];
function recursive_ajax(a,max){
	$.ajax({
		method: "POST",
		url: hrefs[a],
		data: { part_only: "yes" }
	}).done(function(msg){
		console.log(msg+" - "+(targets[a]!="NULL")+" => "+targets[a]);
		if(targets[a]!="NULL"){
			$(targets[a]).html(msg);
		}
		if(a+1<max){
			recursive_ajax(a+1,max);
		}
	});
}
function ajax_content(target,href){
	hrefs = href.split(" ");
	targets = target.split(" ");
	recursive_ajax(0,targets.length);
}

$(document).ready(function() {
	$("a.ajax_content").each(function(){
		if(!$(this).data('href')){
			$(this).attr('data-href',this.href);
		}else{
			$(this).attr('data-href',this.href+" "+$(this).attr('data-href'));
		}
		this.href="javascript:void(0)";
		$(this).click(function(){
			ajax_content(
				$(this).data('target'),
				$(this).data('href')
			);
		});
	});
	$("form.ajax_content").each(function(){
		var prnt = this;
		if(!$(prnt).data('action')){
			$(prnt).attr('data-action',prnt.action);
		}else{
			$(prnt).attr('data-action',prnt.action+" "+$(prnt).attr('data-action'));
		}
		var sbmt = $(prnt).find(":submit");
		console.log(sbmt);
		$(sbmt).prop("type","button");
		$(sbmt).click(function(){
			console.log($(prnt));
			ajax_content(
				$(prnt).data('target'),
				$(prnt).data('action')
			);
		});
	});
});
