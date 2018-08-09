function fillTakenBox(pegNum){
  $.ajax({
      url: 'peg/'+pegNum,
      type: 'GET',
      dataType: 'html',
       error:function(exception){console.log('Exception:'+exception);}
    }).done(function ( data ) {
      $('#takenPegBox-'+pegNum).replaceWith(data);
    });
};

function deleteusermodal(id)
{
  $('#deleteLink').attr('href', function() {
        return window.location.href + '/delete/' + id;
    });
}
function deletegroupmodal(id)
{
  $('#deleteLink').attr('href', function() {
        return window.location.href + '/delete/' + id;
    });
}
function deletepegmodal(nr)
{
  $('#deleteLink').attr('href', function() {
        return '/pegs/delete/' + nr;
    });
}

function autoRefresh_div() {
    $("#peg-actions-table").load("statistic/load", function() {
      var dt = new Date();
      $("#peg-actions-table-heading").html("Paskutiniai veiksmai - atnaujinta "+ dt.toLocaleTimeString());
        setTimeout(autoRefresh_div, 10000);
    });
}
autoRefresh_div();

var activeurl = window.location;
$('a[href="'+activeurl+'"]').parent('li').addClass('active');

function toggleGroupDesc(id){
    $('#'+id).fadeIn('200');
    if($("#"+id + "-toggle-icon").attr('class') == "glyphicon glyphicon-chevron-up"){
      $("#"+id + "-toggle-icon").removeClass().toggleClass("glyphicon glyphicon-chevron-down");
      $('#'+id).fadeOut('200');
    }
    else{

      $("#"+id + "-toggle-icon").removeClass().toggleClass("glyphicon glyphicon-chevron-up");
    }
};

function togglePegMeniu(id)
{
  $('#'+id).fadeIn("slow");
}

$(".nav li a").on("click", function(){
  $(".li").find(".active").removeClass("active");
  $(this).parent().addClass("active");
});

$(function() {
  $( "#datepicker" ).datepicker({
    dateFormat: "yy-mm-dd"
  });
});
$('.datepick').each(function(){
    $(this).datepicker({
      dateFormat: "yy-mm-dd"
    });
});
