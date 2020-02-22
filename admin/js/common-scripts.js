/*---LEFT BAR ACCORDION----*/
$(function() {
    $('#nav-accordion').dcAccordion({
        eventType: 'click',
        autoClose: true,
        saveState: true,
        disableLink: true,
        speed: 'slow',
        showCount: false,
        autoExpand: true,
//        cookie: 'dcjq-accordion-1',
        classExpand: 'dcjq-current-parent'
    });
});

$(document).ready(function() {
	
	$('body').on('keyup','input[type="number"]', function(evt){
				var rel=$(this).attr('rel');
	
	this.value = this.value.replace(/[^0-9\.]/g,'1');
				
	
	})
	
	//var basename=document.location.pathname.match(/[^\/]+$/)[0];
	var basename='';
	if(basename)
	{
		$('.sidebar-menu > li').each(function(){
			if($(this).hasClass('sub-menu')==true)
			{
				$(this).find('ul.sub li a').each(function(){
						if($(this).attr('href')==basename)
						{
							$(this).parent().addClass('active');
							$(this).parent().parent().slideDown();	
							$(this).parent().parent().parent().find('a').addClass('active');	
						}
					
					})
			}
			else
			{
					
			}
			
			})
	}
	
  $.simpleWeather({
    location: 'Mumbai, India',
    woeid: '',
    unit: 'c',
    success: function(weather) {
      var html = weather.temp+'&deg;'+weather.units.temp;
	  
	  var html2=weather.humidity;
 		var html3=weather.wind.speed;
		var html4=weather.text; 
      $(".degree").html(html);
	  $(".humidity").html(html2);
	  $(".currently_weather").html(html4);
	  $(".winds_weather").html(html3);
	  
    },
    error: function(error) {
     
    }
  });
  
  
 
  
  
});

var Script = function () {

//    sidebar dropdown menu auto scrolling

    jQuery('#sidebar .sub-menu > a').click(function () {
        var o = ($(this).offset());
        diff = 250 - o.top;
        if(diff>0)
            $("#sidebar").scrollTo("-="+Math.abs(diff),500);
        else
            $("#sidebar").scrollTo("+="+Math.abs(diff),500);
    });

//    sidebar toggle

    $(function() {
        function responsiveView() {
            var wSize = $(window).width();
            if (wSize <= 768) {
                $('#container').addClass('sidebar-close');
                $('#sidebar > ul').hide();
            }

            if (wSize > 768) {
                $('#container').removeClass('sidebar-close');
                $('#sidebar > ul').show();
            }
        }
        $(window).on('load', responsiveView);
        $(window).on('resize', responsiveView);
    });

    $('.icon-reorder').click(function () {
		
        if ($('#sidebar > ul').is(":visible") === true) {
            $('#main-content').css({
                'margin-left': '0px'
            });
            $('#sidebar').css({
                'margin-left': '-210px'
            });
            $('#sidebar > ul').hide();
            $("#container").addClass("sidebar-closed");
        } else {
            $('#main-content').css({
                'margin-left': '210px'
            });
            $('#sidebar > ul').show();
            $('#sidebar').css({
                'margin-left': '0'
            });
            $("#container").removeClass("sidebar-closed");
        }
    });

// custom scrollbar
    $("#sidebar").niceScroll({styler:"fb",cursorcolor:"#e8403f", cursorwidth: '3', cursorborderradius: '10px', background: '#404040', spacebarenabled:false, cursorborder: ''});

    $("html").niceScroll({styler:"fb",cursorcolor:"#e8403f", cursorwidth: '6', cursorborderradius: '10px', background: '#404040', spacebarenabled:false,  cursorborder: '', zindex: '1000'});

// widget tools

    jQuery('.inv-toggle').click(function () {
		
        var el = jQuery(this).parents(".panel").children(".panel-body");
 			if($(this).hasClass('icon-chevron-down'))
			{	      
            jQuery(this).removeClass("icon-chevron-down").addClass("icon-chevron-up");
			}
			else
			{
				jQuery(this).removeClass("icon-chevron-up").addClass("icon-chevron-down");
			}
			el.slideToggle(200);
        
    });
	
	

    jQuery('.panel .tools .icon-remove').click(function () {
        jQuery(this).parents(".panel").parent().remove();
    });


//    tool tips

    $('.tooltips').tooltip();

//    popovers

    $('.popovers').popover();



// custom bar chart

    if ($(".custom-bar-chart")) {
        $(".bar").each(function () {
            var i = $(this).find(".value").html();
            $(this).find(".value").html("");
            $(this).find(".value").animate({
                height: i
            }, 2000)
        })
    }


}();