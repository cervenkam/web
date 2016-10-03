var hrefs = [];
var targets = [];
var json_data;
var __switched_off__ = false;
var running = false;

function pad(string,number){
	string = string.toString();
	return string.length < number ? pad("0"+string,number) : string;
}

function get_time(){
	var date = new Date();
	return pad(date.getHours(),2)+":"+pad(date.getMinutes(),2)+":"+pad(date.getSeconds(),2);
}

function check_news(){
	json_data = { 
		method: "POST",
		url: "controller/check_news.php",
		data: {
			"part_only": "yes"
		}
	}
	$.ajax(json_data).done(function(msg){
		var text = $("#messages").html();
		if(msg){
			$("#messages").css('display','block');
			$("#messages").html(text+get_time()+"<br />"+msg);
		}
	});
	$("#messages").click(function(){
		$("#messages").css('display','none');
		$("#messages").html("");
	});	
}
function recursive_ajax(a,max){
	$.ajax({
		method: "POST",
		url: hrefs[a],
		data: json_data
	}).done(function(msg){
		if(targets[a]!="NULL"){
			//window.history.pushState({},hrefs[a],hrefs[a]);
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
			CKEDITOR.replace('add_abstract_field');
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
	$("a.ajax_content").each(function(){
		this.href="javascript:void(0)";
		$(this).unbind('click');
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
		$(sbmt).prop("type","button");
		$(sbmt).unbind('click');
		$(prnt).find("input").keypress(function(e){
			if(e.keyCode==13){
				$(sbmt).click();
			}
		});
		$(sbmt).click(function(){
			var ser = $(prnt).serializeArray();
			var data = {};
			for(var a=0; a<ser.length; a++){
				data[ser[a].name] = ser[a].value;
			}
			ajax_content(
				$(prnt).data('target'),
				$(prnt).data('action'),
				data
			);
		});
	});
}

$(document).ready(function(){
	if(__switched_off__){
		return;
	}
	/*window.addEventListener('popstate',function(e){
		if(e.state){
			window.location.href = e.target.location.href;
		}	
	});*/
	async();
	if($("#add_abstract_field").lenght){
		CKEDITOR.replace('add_abstract_field');
	}
	if(!running){
		check_news();
		setInterval(check_news,5000);
		running = true;
	}
});
