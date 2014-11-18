var czas = 200;

$(document).ready(function() {
  $("ul.lines a").hover(function(){
    $(this).animate({/*borderTopWidth: 10,*/backgroundColor: '#ffffff'},czas);
  },function(){
    $(this).animate({/*borderTopWidth:  0,*/backgroundColor: '#e7f1f8'},czas);
  });
  
  $("ul.lines a.chosen").hover(function(){
    $(this).animate({/*borderTopWidth: 10,*/backgroundColor: '#ddccbb'},czas);
  },function(){
    $(this).animate({/*borderTopWidth:  0,*/backgroundColor: '#E1C3D2'},czas);
  });
});

//#E1C3D2;