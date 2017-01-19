
	jQuery(document).ready(function(){
			
			
			$('.menu a.a1').click(function(){
				$.scrollTo('#point1', 1500, {offset: {top:-50} });
				return false;
			});
			
			$('.menu a.a2').click(function(){
				$.scrollTo('#point2', 1500, {offset: {top:-50} });
				return false;
			});
			
			$('.menu a.a3').click(function(){
				$.scrollTo('#point3', 1500, {offset: {top:-50} });
				return false;
			});
			
			$('.menu a.a4').click(function(){
				$.scrollTo('#point4', 1500, {offset: {top:-50} });
				return false;
			});
			
			$('.menu a.a5').click(function(){
				$.scrollTo('#point5', 1500, {offset: {top:-50} });
				return false;
			});
			
			$('.menu a.a6').click(function(){
				$.scrollTo('#point6', 1500, {offset: {top:-50} });
				return false;
			});
					
			$('.menu a.a7').click(function(){
				$.scrollTo('#point7', 1500, {offset: {top:-50} });
				return false;
			});
			$('.menu a.a8').click(function(){
				$.scrollTo('#point8', 1500, {offset: {top:-50} });
				return false;
			});
			$('.menu a.a10').click(function(){
				$.scrollTo('#point10', 1500, {offset: {top:-50} });
				return false;
			});
			
			
			$(window).scroll(function(){	
				if ($(this).scrollTop()>20)
				{
					$(".menu").css({"position":"fixed"});
					$(".menu").css({"top":"0"});
					
				}else{
					$(".menu").css({"position":"absolute"});
					$(".menu").css({"top":"20px"});
				}
			});
			
			
	})
		
