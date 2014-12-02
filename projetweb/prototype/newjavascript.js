

function hoverDiv(){
        $(this).css("border-color","Black");
        $(this).css("background-color","#F83400");
}
function leaveDiv(){
	$(this).css("border-color","#FFFFFF");
        $(this).css("background-color","");
}

function SendInfo()
{
    var value = $(this).attr('id'); // récupére l'attribue de l'icone séléctionner
    console.log(value);
    var my_data;
    $.ajax({
	url: "Responses.php",
	type: "",
	data: { a : "value"},
        
    })
    // console.log(data); ne fonctionne pas ici
    /*.done(function( data ) {
	my_data = data;
	$.each(my_data, function(key, val) {
            console.log(val); 
            var message = $//('<section class="theme" data-id="'+val.id+'"><img src="'+val.imageUri+'" alt="logo"><div class="resto"><p>'+val.nom+'</p><a href="#"> Voir la carte</a></div></section>');

            ('<div id="container_block"><div id="index_'+val.nom+'" class="blockIndex"><h2 class="title-blockIndex">'+val.nom+'</h2><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur laoreet sed lacus ac aliquam. Aliquam a consequat nulla. Mauris ullamcorper, sem sit amet aliquam convallis, dolor mauris dignissim urna, eu iaculis nibh odio sit amet ligula.</p><a href="pizza.php" class="btn_blockIndex plat">Voir les plats</a></div></div>');
            $("#container").append(message); 
            message.click(); 
        });
    });*/
}

$(document).ready( function(){
	$('p.Cperso').mouseover(hoverDiv);
        $('p.Cperso').mouseleave(leaveDiv);
	$('p.Cperso').click(SendInfo);
	//$('button#hide').click(switch1);
	//$('button#add').click(addvanish);
});

