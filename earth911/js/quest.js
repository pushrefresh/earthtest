// Overall Site Smooth Scroll //

jQuery(function() {
  jQuery('a[href*="#"]:not([href="#"])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = jQuery(this.hash);
      target = target.length ? target : jQuery('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        jQuery('html, body').animate({
          scrollTop: target.offset().top
        }, 300);
        return false;
      }
    }
  });
});

// Smooth Scroll End //

// Google Fonts //

(function(d) {
	var config = {
	  kitId: 'wvv4stp',
	  scriptTimeout: 3000,
	  async: true
	},
	h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
})(document);

// Fonts End //

// Custom jQuery Start //

// jQuery(window).scroll(function() {    
//     var scroll = jQuery(window).scrollTop();

//     if (scroll >= 50) {
//         jQuery("#banner-header").addClass("fixed-to-top");
//     } else {
//         jQuery("#banner-header").removeClass("fixed-to-top");
//     }
// });

jQuery(window).scroll(function() {    
    var scroll = jQuery(window).scrollTop();

    if (scroll >= 100) {
        jQuery(".back-to-top").addClass("to-top-show");
    } else {
        jQuery(".back-to-top").removeClass("to-top-show");
    }
});

jQuery(window).scroll(function() {    
    var scroll = jQuery(window).scrollTop();

    if (scroll >= 130) {
        jQuery("header#banner-header.navbar-fixed-top").addClass("show-it");
    } else {
        jQuery("header#banner-header.navbar-fixed-top").removeClass("show-it");
    }
});


(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=1683434621985963";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));