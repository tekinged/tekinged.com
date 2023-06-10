$(document).ready(function(){

  // initialize tooltip
  $( ".dynamic_definition span" ).tooltip({
     track:true,
     open: function( event, ui ) {
        var id = this.id;
        var userid = $(this).attr('data-id');
        ui.tooltip.css("max-width", "500px");
 
        $.ajax({
            url:'/dynamic_definition.php',
            type:'post',
            data:{userid:userid},
            success: function(response){
 
               // Setting content option
               console.log(`tooltip.js: ${response} ${id}`); 
               $("#"+id).tooltip('option','content',response);
               // $(this).attr('title','Can I put something here?');
            }
        });
     }
  });

  $(".dynamic_definition span").mouseout(function(){
     // re-initializing tooltip
     $(this).attr('title','Please wait foo...');
     $(this).tooltip();
     $('.ui-tooltip').hide();
  });

});
