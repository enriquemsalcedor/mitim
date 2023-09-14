$(document).on('mousemove', '.frame', function(){
  
  var element = {
    width: $(this).width(),
    height: $(this).height()
  };
  
  var mouse = {
    x : event.pageX,
    y : event.pageY
  };
  
  var offset = $(this).offset();
  
  var origin = {
    x: (offset.left+(element.width/2)),
    y: (offset.top+(element.height/2))
  };
  
  var trans = {
    left: (origin.x - mouse.x)/2,
    down: (origin.y - mouse.y)/2
  };
  
  var transform = ("scale(2,2) translateX("+ trans.left +"px) translateY("+ trans.down +"px)");
  
  $(this).children(".zoom").css("transform", transform);
  
});

$(document).on('mouseleave', '.frame', function(){
  $(this).children(".zoom").css("transform", "none");
});



