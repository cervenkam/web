var hrefs = [];
var targets = [];
var json_data;
function recursive_ajax(a,max){
	console.log(json_data);
	$.ajax({
		method: "POST",
		url: hrefs[a],
		data: json_data
	}).done(function(msg){
		console.log(msg+" - "+(targets[a]!="NULL")+" => "+targets[a]);
		if(targets[a]!="NULL"){
			$(targets[a]).html(msg);
		}
		//TODO delete following else branch
		else{
			$("#debug_div").html(msg);
		}
		if(a+1<max){
			recursive_ajax(a+1,max);
		}else{
			async();
		}
	});
}
function ajax_content(target,href,append_data){
	hrefs = href.split(" ");
	targets = target.split(" ");
	json_data = append_data;
	json_data["part_only"] = "yes";
	recursive_ajax(0,targets.length);
}

function async(){
	console.log("reload");
	$("a.ajax_content").each(function(){
		this.href="javascript:void(0)";
		$(this).click(function(){
			ajax_content(
				$(this).data('target'),
				$(this).data('href'),
				{}
			);
		});
	});
	$("form.ajax_content").each(function(){
		var prnt = this;
		var sbmt = $(prnt).find(":submit");
		console.log(sbmt);
		$(sbmt).prop("type","button");
		$(sbmt).click(function(){
			var ser = $(prnt).serializeArray();
			var data = {};
			for(var a=0; a<ser.length; a++){
				data[ser[a].name] = ser[a].value;
			}
			console.log($(prnt));
			ajax_content(
				$(prnt).data('target'),
				$(prnt).data('action'),
				data
			);
		});
	});
}

$(document).ready(function(){
	async();
});
