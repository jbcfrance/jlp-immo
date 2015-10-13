/***************************************************************************************
*
*		NAVIGATION
*
***************************************************************************************/

		$(document).ready(function(){
			$("#nav ul").css({display: "none"}); // Opera Fix
			$("#nav li").hover(function(){
				$(this).find('ul:first').css({visibility: "visible",display: "none"}).slideDown(200);
			},function(){
				$(this).find('ul:first').css({visibility: "hidden"});
			});
		});
		
/***************************************************************************************
*
*		CYCLES GALLERY
*
***************************************************************************************/

		$(document).ready(function(){
			$(".cyclebox").cycle({
				fx:     'none',  
				timeout: 0,
				containerResize:1,
				prev:$('#prev_cycle'),
				next:$('#next_cycle'),
				after:onAfter
			});
			
			function onAfter(curr, next, opts) {
				if($(this).attr('id')<10){
					var SlideId = '0'+$(this).attr('id');
				}else{
					var SlideId = $(this).attr('id');
				}
				 var caption = ''+SlideId+'/'+opts.slideCount;
				 $('#cycle-count').html(caption);
				 
				 $('#legende-cycle').html($(this).attr('title'));
				 
			} 
		});
/***************************************************************************************
*
*		LIGHTBOX IFRAME LINK
*
***************************************************************************************/	
 $(document).ready(function(){

      $('a.lightbox').lightbox({
		  resize:false,
		  background :'#000000',
		  showDuration    : 0,
		  closeDuration   : 0,
		  moveDuration    : 0,
		  resizeDuration  : 0,
		  showTransition  : 'none',
		  closeTransition : 'none',
		  moveTransition  : 'none',
		  resizeTransition : 'none'
		  });

    });




	
/***************************************************************************************
*
*		ACCORDION PANELS
*
***************************************************************************************/

		$(document).ready(function(){
			$(".accordion").accordion ({
				header: "h4"
			});
		});

/***************************************************************************************
*
*		TOGGLE PANELS
*
***************************************************************************************/
		
		$(document).ready(function(){
			$(".toggle div").hide(); // hide div on default
			$(".toggle h4").click(function(){ // set the trigger
				$(this).toggleClass("active").next().slideToggle(300); // add class active and toggle speed
				return false;
			});
		});
		
/***************************************************************************************
*
*		TABS PANELS
*
***************************************************************************************/

		$(document).ready(function() {
		
			//When page loads...
			$(".tab-content").hide(); //Hide all content
			$("ul.tabs li:first").addClass("active").show(); //Activate first tab
			$(".tab-content:first").show(); //Show first tab content
			
			$("ul.tabs li:first-child").addClass("first-child");
			$("ul.tabs li:last-child").addClass("last-child");
			
			//On Click Event
			$("ul.tabs li").click(function() {
		
				$("ul.tabs li").removeClass("active"); //Remove any "active" class
				$(this).addClass("active"); //Add "active" class to selected tab
				$(".tab-content").hide(); //Hide all tab content
				
				var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
				$(activeTab).fadeIn(); //Fade in the active ID content
				return false;
			});
		
		});	

/***************************************************************************************
*
*		ADD ODD AND EVEN CLASS TO THE TABLE TR
*
***************************************************************************************/
		
		$(document).ready(function() {
			
			$(".table-light tr:odd").addClass("odd");
			$(".table-light tr:even").addClass("even");
			$(".table-dark tr:odd").addClass("odd");
			$(".table-dark tr:even").addClass("even");
		
		});	
		
/***************************************************************************************
*
*		ADDING CLASSES TO LI'S
*
***************************************************************************************/
		
		$(document).ready(function() {
			
			$("ul li:first-child").addClass("first-child");
			$("ul li:last-child").addClass("last-child");
		
		});	

/***************************************************************************************
*
*		FANCYCAPTION PLUGIN FOR OVERLAYS
*
***************************************************************************************/
	
		$(document).ready(function(){
			$(".fancycaption").fancyCaption();
		});
		
/***************************************************************************************
*
*		FANCYBOX GALLERY PLUGIN
*
***************************************************************************************/

		$(document).ready(function(){
			$("a.fancybox-photo").fancybox({
				'overlayColor'		: '#000',
				'overlayOpacity'	: 0.7
			});
		});
		
/***************************************************************************************
*
*		FLICKR FEED
*
***************************************************************************************/
		
		$(document).ready(function(){
			$('#flickr-feed ul').jflickrfeed({
				feedapi: 'photoset.gne',
				limit: 8,
				qstrings: {
					nsid: '10729228@N07',
					set: '72157624798241210'
				},
				itemTemplate:
				'<li>' +
					'<a class="flickr-feed-photos" rel="group2" href="{{image}}" title="{{title}}">' +
						'<img src="{{image_s}}" alt="{{title}}" width="38" height="38" />' +
					'</a>' +
				'</li>'
			}, function(data) {
				$("a.flickr-feed-photos").fancybox({
					'overlayColor'		: '#000',
					'overlayOpacity'	: 0.7,
					'cyclic'            : true
				});
			});
		});
		
/***************************************************************************************
*
*		TWITTER FEED
*
***************************************************************************************/

		$(document).ready(function(){
			$('#tweets').tweetable({
				username: 'mthemes', 
				time: true, 
				limit: 1, 
				replies: true, 
				position: 'append'
			});
		});
		
/***************************************************************************************
*
*		TOTOP PLUGIN
*
***************************************************************************************/

		$(document).ready(function() {
			
			$().UItoTop({
				scrollSpeed: 500,
				easingType: 'easeOutQuart' 
	 		});
			
		});
		
/***************************************************************************************
*
*		TESTIMONIAL PLUGIN
*
***************************************************************************************/
		$(document).ready(function() {
		
			$('.testimonial').quovolver();
		
		});

/***************************************************************************************
*
*		NEWSLETTER INPUT
*
***************************************************************************************/
		
		$(document).ready(function() {  
			$('.newsletter input[type="text"], .sidebar-search input[type="text"]').addClass("normalfield");  
			$('.newsletter input[type="text"], .sidebar-search input[type="text"]').focus(function() {  
				$(this).removeClass("normalfield").addClass("focusfield");  
				if (this.value == this.defaultValue){  
					this.value = '';  
				}  
				if(this.value != this.defaultValue){  
					this.select();  
				}  
			});  
			$('.newsletter input[type="text"], .sidebar-search input[type="text"]').blur(function() {  
				$(this).removeClass("focusfield").addClass("normalfield");  
				if ($.trim(this.value == '')){  
					this.value = (this.defaultValue ? this.defaultValue : '');  
				}  
			});  
		});

/***************************************************************************************
*
*		PORTFOLIO SORTING
*
***************************************************************************************/

		$(document).ready(function(){

			$(document).ready(function(){
				$(".fancycaption").fancyCaption();
			});
			
			// Initialize fancyBox plugin
			$(document).ready(function(){
				$(".portfolio-filterable li a.fancybox-photo").fancybox({
					'overlayColor'		: '#000',
					'overlayOpacity'	: 0.7
				});
			});
			
			$(".filter li").removeClass("first-child");
			$(".filter li").removeClass("last-child");	

			// Clone portfolio items to get a second collection for Quicksand plugin
			var $portfolioClone = $(".portfolio-filterable").clone();
			
			// Attempt to call Quicksand on every click event handler
			$(".filter a").click(function(e){
				
				$(".filter li").removeClass("current");	
				
				// Get the class attribute value of the clicked link
				var $filterClass = $(this).parent().attr("class");

				if ( $filterClass == "all" ) {
					var $filteredPortfolio = $portfolioClone.find("li");
				} else {
					var $filteredPortfolio = $portfolioClone.find("li[data-type~=" + $filterClass + "]");
				}
				
				// Call quicksand
				$(".portfolio-filterable").quicksand( $filteredPortfolio, { 
					duration: 800, 
					easing: 'easeInOutQuad'
				}, function(){
					
					$(document).ready(function(){
						$(".fancycaption").fancyCaption();
					});
					
					$(document).ready(function(){
						$(".portfolio-filterable li a.fancybox-photo").fancybox({
							'overlayColor'		: '#000',
							'overlayOpacity'	: 0.7
						});
					});
				});

				$(this).parent().addClass("current");

				// Prevent the browser jump to the link anchor
				e.preventDefault();
			})
		});