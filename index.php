<?php
$access_token = "YOUR_ACCESS_TOKEN_HERE";

function fbAPI($path, $cache_time = 1800, $params) {
	global $access_token;
	
	$params->access_token = $access_token;
	$q = http_build_query($params);

	$cache_name = './cache/' . md5($path . $q);
	if( time() - filemtime($cache_name) < $cache_time ) {
		return file_get_contents( $cache_name );
	} else {
		$url = "https://graph.facebook.com/v2.0/" . $path . "?" . $q;
		$data = file_get_contents($url);
		file_put_contents($cache_name, $data);
		return $data;
	}
}

$posts = json_decode(fbAPI('me/posts'));
foreach($posts->data as $post) {
	$post->insigths = json_decode(fbAPI($post->id . "/insights/post_impressions_unique,post_engaged_users"));
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Engagement rate</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
<script type="text/javascript" src="circles.min.js"></script>
<style>
body, html {
    margin: 0;
    padding: 0;
    height: 100%;
    font-family: sans-serif;
}
.post {
    padding: 40px 0;
    text-align: center;
}
h1 {
    text-align: left;
    margin: -0.15em 0 0 -0.1em;
    font-size: 10em;
    text-transform: uppercase;
    font-weight: bolder;
    letter-spacing: -0.14em;
    color: #FFF;
    text-shadow: -1px -1px 1px rgba(0,0,0,0.1);
    line-height: 0.7em;
}
.engagement_num {
    display: block;
}
.math {
    display: block;
    position: relative;
    top: -59px;
    color: #CCC;
}
.bg {
    background-image: url('p.gif');
    opacity: 0.06;
    width: 100%;
    height: 100%;
    position: fixed;
    top: 0;
    z-index: -1;
}
small {
    max-width: 30%;
    display: block;
    font-size: 0.6em;
    line-height: 1em;
    color: #000;
    opacity: 0.6;
}
</style>
</head>
<body>
<div class="bg">
</div>
<h1>Engagement rate</h1>
<div id="fb-root"></div>
<div id="pimpum"></div>
<small>
Project by <a href="https://twitter.com/luotojesse">Jesse Luoto</a>.
20 most recent FB posts from page with calculated Engagement Rates (The number of people who clicked anywhere in your posts [divided by] The number of people who saw your Page post).<br />
Background is <a href="http://thepatternlibrary.com/#brijan" rel="nofollow">Brijan</a> by Brijan Powell, from thepatternlibrary.
The awesome <a href="https://github.com/lugolabs/circles" rel="nofollow">Circles plugin</a> by Artan Sinani<br />
Licensed under <a href="LICENSE">MIT license</a>.
</small>
<script>
var $data = <?php echo json_encode($posts); ?>;

function Lerp(from, to, p) {
	return from + ((to-from)*p);
}

$(function() {
	var $container = $("#pimpum");

	for(var i in $data.data) {
		var post = $data.data[i];
		console.log(post);
		var insights = post.insigths.data;
		var impressions, engages;

		for(var j = 0; j < insights.length; j++) {
			switch(insights[j].name) {
				case "post_impressions_unique":
					impressions = insights[j].values[0].value;
					break;
				case "post_engaged_users":
					engages = insights[j].values[0].value;
					break;
				default:
					console.log(insights[j].name);
			}
		}

		var engagement_rate = engages / impressions;
		var engagement_rate_num = Math.round(engagement_rate * 1000) / 10;
		var log_rate = Math.log(engagement_rate_num)/Math.log(50);

		var datahref = post.link.indexOf("https://www.facebook.com") === 0 ? post.link : ("https://www.facebook.com/createtrips/posts/" + post.id.split("_").pop());
		$post = $('<div class="post">\
			<div class="engagement">\
				<span class="engagement_num" id="eng'+post.id+'">'+engagement_rate_num+'</span>\
				<span class="math">'+engages+'/'+impressions+'</span>\
			</div>\
			<div class="fb-post" data-href="'+ datahref  +'" data-width="466"></div>\
		</div>').appendTo($container);

		Circles.create({
		    id:         'eng'+post.id,
		    radius:     80,
		    value:      engagement_rate_num,
		    maxValue:   100,
		    width:      11.5,
		   // text:       function(value){return value + '%';},
		    colors:     [
					"hsl("+Lerp(0,122,log_rate)+",80%,"+(50+engagement_rate_num)+"%)",
					"hsl("+Lerp(0,122,log_rate)+",40%,"+(50+engagement_rate_num)+"%)",
				   ],
		    duration:   400,
		    wrpClass:   'circles-wrp',
		    textClass:  'circles-text'
		})


	}

});
</script>


<script>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/en_US/all.js#xfbml=1"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script>
</body>
</html>
