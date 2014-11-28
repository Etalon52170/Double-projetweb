/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function hoverSpan(){
	$(this).css("border-radius"," = 50%");
        $(this).css("border-width"," = 5px");
        $(this).css("border-style"," = solid");
        $(this).css("border-color"," = Orange");
}

function switch1(){
	$('div.vanish').slideToggle(600);
} 


$(document).ready( function(){
	$('span.Icone>').mouseover(hoverSpan);
       // $('span.Icone').mouseleave(LeaveSpan);
});

