
function ajax_content(target,href){
	console.log(href);
	$.ajax({
		method: "POST",
		url: href,
		data: { part_only: "yes" }
	}).done(function(msg){
		console.log(msg);
		$(target).html(msg);
	});
}

$(document).ready(function() {
	$("a.ajax_content").each(function(){
		$(this).attr('data-href',this.href);
		this.href="javascript:void(0)";
		$(this).click(function(){
			ajax_content(
				$(this).data('target'),
				$(this).data('href')
			);
		});
	});
});
