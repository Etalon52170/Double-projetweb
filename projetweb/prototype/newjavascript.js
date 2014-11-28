function addvanish(){
	$("section").append(" <div class='vanish'></div>");
	$('div.vanish').mouseover(hoverDiv);
	$('div.vanish').click(color);
}

function hoverDiv(){
	$(this).css("height","+=10");
}

function switch1(){
	$('div.vanish').slideToggle(600);
} 

function color(){
	var colors = ['#ff0000', '#00ff00', '#0000ff','#222222','orange'];
	var random_color = colors[Math.floor(Math.random() * colors.length)];
	$(this).css("backgroundColor", random_color);
}



$(document).ready( function(){
	//$('div.vanish').mouseover(hoverDiv);
	//$('div.vanish').click(color);
	//$('button#hide').click(switch1);
	//$('button#add').click(addvanish);
});

