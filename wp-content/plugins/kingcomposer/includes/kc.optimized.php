<?php
/**
*
*	King Composer
*	(c) KingComposer.com
*	kc.optimized.php
*
*/
if(!defined('ABSPATH')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

/*
*	cache class
*/

class kc_optimized {

	private $ignore;
	private $move_down_blocking_js = false;
	private $css_stack = array();
	private $js_stack = array();
	private $css_key;
	private $css_current;
	private $js_key;
	private $js_current;

	private $surl;
	private $jsx = "/<script(.*)>(.*)<\/script>/Uis";
	private $SS = '"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'';
	private $CC = '\/\*[\s\S]*?\*\/';
	private $CH = '<\!--[\s\S]*?-->';
	private $X = "\x1A";

	private $is_created = null;

	function __construct(){

		$this->surl = trailingslashit(str_replace(array('http://', 'https://'), "", KC_SITE));

		if (!is_admin()) {

			$case_get = false;
			if (isset($_GET['kc_optimized_action'])) {
				$action = $_GET['kc_optimized_action'];
				switch ($action) {
					case 'gethtml':
						remove_action('wp_head', 'print_emoji_detection_script', 7);
						remove_action('wp_print_styles', 'print_emoji_styles');
						remove_action( 'admin_print_scripts', 'print_emoji_detection_script');
						remove_action( 'admin_print_styles', 'print_emoji_styles');
						$case_get = true;
					break;
				}
			}

			if ($case_get === false) {

				add_action('wp_footer', array( &$this, 'in_footer' ), 99999);

			}

		}

	}

	public function in_footer() {

		global $kc, $post;

		$settings = get_option('kc_optimized', true);
		if (!kc_is_using() || !$kc->is($settings, array('enable'), 'on'))
			return;

		$kc_meta = get_post_meta ($post->ID , 'kc_data', true);

		if (isset($kc_meta['optimized']) && $kc_meta['optimized'] == 'deactive'){
			return;
		}

		if ($settings['global'] != 1 && (!isset($kc_meta['optimized'])))
			return;

		$url = (is_ssl() ? 'https://' : 'http://');
		$url .= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		$path = $this->render_path_name($url);

		if (!is_file($path)){
			if ($this->parse($url) !== false)
				echo '<!--Optimized successful-->';
			else echo '<!--Optimized fail-->';
		}

	}

	public function parse($url = '') {

		if (!$this->is_internal($url) || !$this->create_htaccess())
			return false;

		$html = $this->get_html($url);

		// Remove all trip html
		$html = preg_replace('/<!--(.*)-->/Uis', '', $html);

		// Process CSS
		$regexp_css = '%<(link|style)(?=[^<>]*?(?:type="(text/css)"|>))(?=[^<>]*?(?:media="([^<>"]*)"|>))(?=[^<>]*?(?:href="(.*?)"|>))(?=[^<>]*(?:rel="([^<>"]*)"|>))(?:.*?</\1>|[^<>]*>)%si';

		$this->css_key = $this->css_current = $this->js_key = $this->js_current = '';
		$this->css_stack = $this->js_stack = array();

		$html = preg_replace_callback($regexp_css, array(&$this, 'preg_css'), $html);

		// Process Head
		$html = $this->process_js ($html);

		$map_id = array();
		$map_link = array();

		foreach ($this->js_stack as $id => $links) {

			$map_id[] = $id;
			$combined = $this->create_combined($links, 'js');

			if ($combined !== false)
				$map_link[] = $combined;
			else return false;

		}

		foreach ($this->css_stack as $id => $links) {

			$map_id[] = $id;
			$combined = $this->create_combined($links, 'css');

			if ($combined !== false)
				$map_link[] = $combined;
			else return false;

		}

		$html = str_replace($map_id, $map_link, $html);
		$html = trim($html);

		if (empty($html))
			return false;

		$html .= "\n".'<!-- This page has been optimized on '.date('l jS \of F Y h:i:s A').' by KingComposer Page Builder : https://kingcomposer.com -->';

		return $this->create_index_file($html, $url);

	}

	private function process_js ($html = '') {

		/* Process HEAD */
		$html = preg_replace_callback("/<head[^<>]*>(.*)<\/head>/Uis", array(&$this, 'process_head'), $html);
		while (strpos($html, "\n<body") !== false || strpos($html, "\n</head>") !== false || strpos($html, "</body>\n") !== false || strpos($html, "\n<html>") !== false || strpos($html, "\n</html>") !== false)
			$html = str_replace(array("\n<body", "\n<head>", "</body>\n", "\n<html", "\n</html>"), array('<body', '<head>', '</body>', '<html', '</html>'), $html);

		// Move all blocking script to after body
		$this->move_down_blocking_js = true;
		$this->stack_js_trace = array();
		$html = preg_replace_callback("/<body(.*)>(.*)<\/body>/Uis", array(&$this, 'process_body'), $html);

		$this->js_key = '';

		$html = preg_replace_callback("/<\/body>(.*)/is", array(&$this, 'process_after_body'), $html).implode("", $this->stack_js_trace);

		return $html;


	}

	private function process_head ($m) {

		$regexp_js = "/<script[^>](.*)>(.*)<\/script>/Uis";
		$html = preg_replace_callback($regexp_js, array(&$this, 'preg_head_js'), $m[0]);

		return $this->minify_html ($html);

	}

	private function process_body ($m) {

		$html = preg_replace_callback($this->jsx, array(&$this, 'preg_js'), $m[2]);

		// if did not move in preg, move it manual
		if ($this->js_key !== '' && $this->move_down_blocking_js !== 'body') {
			$html .= "<script type=\"text/javascript\" data-ref=\"optimized\" src=\"".$this->js_key."\"></script>";
			$this->js_key = '';
		}

		$html = '<body'.$m[1].'>'.$this->minify_html($html).implode("", $this->stack_js_trace).'</body>';
		$this->stack_js_trace = array();

		return $html;

	}

	private function process_after_body ($m) {

		return preg_replace_callback($this->jsx, array(&$this, 'preg_js'), $m[0]);

	}

	private function preg_css ($m) {

		$atts = (shortcode_parse_atts(trim(str_replace(array('<', '/>', '>'), array('', '', ''), $m[0]))));

		if (isset($atts['rel']) && $atts['rel'] == 'stylesheet') {

			$href = str_replace(array('http://', 'https://'), "", $atts['href']);

			if ($this->css_current != $this->css_key || $this->css_key === '') {

				$key = 'ref:index-'.rand(435305,43845686778);
				$this->css_key = $key;
				$this->css_current = $key;
				$this->css_stack[$key] = array();

				$this->css_stack[$key][] = $href;
				return "<link rel=\"stylesheet\" data-ref=\"optimized\" href=\"".$key."\" type=\"text/css\" media=\"all\" />";

			}else{ $this->css_stack[$this->css_current][] = $href;}

			return '';

		}else{

			$this->css_key = '';

			if(isset($atts['type']) && $atts['type'] == 'text/css') {

				while (strpos($m[0],"  ") !== false || strpos($m[0], "\n") !== false)
					$m[0] = str_replace(
						array( "\n","	", ": ", " {", "  "),
						array( '', '', ':', '{', " "),
						$m[0]
					);

				return $m[0];
			}

			return $m[0];
		}
	}

	private function preg_head_js ($m) {

		$atts = (shortcode_parse_atts(trim($m[1])));

		if (isset($atts['src'])) {

			$src = str_replace(array('http://', 'https://'), "", $atts['src']);
			if ($this->js_key === '') {

				$key = 'ref:index-'.rand(435305,43845686778);
				$this->js_key = $key;
				$this->js_current = $key;
				$this->js_stack[$key] = array();

				$this->js_stack[$key][] = $src;

			}else{ $this->js_stack[$this->js_key][] = $src;}

			return '';

		}else{

			$str = explode("\n", $m[0]);
			for ($i = 0; $i < count($str[0]); $i++) {
				if (strpos('//', trim($str[0][$i])) === 0)
					$str[0][$i] = '';
			}

			$str = preg_replace('/\/\*(.*)\*\//Uis', '', implode('', $str));

			while (strpos($str, "  ") !== false || strpos($str, "\n") !== false)
				$str = str_replace(
					array( "\n","	", ": ", " {", "  "),
					array( '', '', ':', '{', " "),
					$str
				);

			return $str;

		}
	}

	private function preg_js ($m) {

		$atts = (shortcode_parse_atts(trim($m[1])));

		if (isset($atts['src'])) {

			$src = str_replace(array('http://', 'https://'), "", $atts['src']);
			if ($this->js_key === '') {

				$key = 'ref:index-'.rand(435305,43845686778);
				$this->js_key = $key;
				$this->js_current = $key;
				$this->js_stack[$key] = array();

				$this->js_stack[$key][] = $src;
				$this->stack_js_trace[] = "<script type=\"text/javascript\" data-ref=\"optimized\" src=\"".$key."\"></script>";

			}else{ $this->js_stack[$this->js_current][] = $src;}

		}else{

			$str = $this->minify_js($m[0]);

			if ($this->js_key !== '' && isset($this->move_down_blocking_js) && $this->move_down_blocking_js === true) {
				$str = "<script type=\"text/javascript\" data-ref=\"optimized\" src=\"".$this->js_key."\"></script>".$str;
				$this->move_down_blocking_js = 'body';
			}

			$this->js_key = '';
			$this->stack_js_trace[] = $str;

		}

		return '';
	}

	private function get_html ($url = '') {

		if (strpos($url, '?') === false)
			$url .= '?kc_optimized_action=gethtml&nightly=cache';
		else $url .= '&kc_optimized_action=gethtml&nightly=cache';

		$request = wp_remote_get ($url);
		$response = wp_remote_retrieve_body ($request);

		return $response;

	}

	private function create_index_file ($html, $url) {

		$path = $this->render_path_name($url);
		if ($path === false)
			return false;

		return (!@file_put_contents($path, $html) && !is_file($path)) ? false : true;

	}

	public function check_htaccess($advanced = 0) {

		$path = ABSPATH;

		if(isset($_SERVER["SERVER_SOFTWARE"]) && $_SERVER["SERVER_SOFTWARE"] &&
			(preg_match("/iis/i", $_SERVER["SERVER_SOFTWARE"]) || (!preg_match("/Apache/i", $_SERVER["SERVER_SOFTWARE"])))
		){
			$this->deactive();
			return array("msg" => "Work only with Apache server software. Enable optimization failed", "stt" => 0);
		}

		$active_plugins = (array) get_option( 'active_plugins', array() );
		$ignores = array();
		$list = array(
			'wp-fastest-cache/wpFastestCache.php',
			'w3-total-cache/w3-total-cache.php',
			'wp-super-cache/wp-cache.php',
			'wp-hide-security-enhancer/wp-hide.php',
			'adrotate/adrotate.php',
			'adrotate-pro/adrotate.php',
			'mobilepress/mobilepress.php',
			'speed-booster-pack/speed-booster-pack.php',
			/*'cdn-enabler/cdn-enabler.php',*/
			'wp-performance-score-booster/wp-performance-score-booster.php',
			'bwp-minify/bwp-minify.php',
			'check-and-enable-gzip-compression/richards-toolbox.php',
			'gzippy/gzippy.php',
			'gzip-ninja-speed-compression/gzip-ninja-speed.php',
			'wordpress-gzip-compression/ezgz.php',
			'filosofo-gzip-compression/filosofo-gzip-compression.php',
			'head-cleaner/head-cleaner.php',
		);

		foreach ($list as $ign) {
			if (in_array($ign, $active_plugins))
				$ignores[] = '<li>'.$ign.'</li>';
		}

		if (count($ignores) > 0) {
			return array("msg" => "Could not enable optimized, some of plugins need to be deactived to avoid conflict: <ol>".implode("", $ignores)."</ol>", "stt" => 0);
		}

		if(!get_option('permalink_structure') || get_option('permalink_structure', true) == '') {
			return array("msg" => "Your permalink settings must be set and cannot be empty. Go to <a href='".admin_url('options-permalink.php')."' target=_blank>permalink settings</a>", "stt" => 0);
		}

		if ($this->create_htaccess($advanced))
			return array("msg" => "Enable optimization success", "stt" => 1);

		return array("msg" => "Could not created htaccess file, please make sure that your hosting is writable", "stt" => 0);

	}

	private function create_htaccess ($advanced = 0) {

		if (is_file(ABSPATH.'.htaccess') && !is_writable(ABSPATH.'.htaccess'))
			return false;

		$parse = parse_url(KC_SITE);
		$host = $parse['host'];
		$path = isset($parse['path']) ? $parse['path'] : '';
		$forceTo = $notSecure = $trailing_slash = '';

		if (!is_file(ABSPATH.'.htaccess')) {
			$wp_htaccess = <<<EOD
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase {$path}/
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . {$path}/index.php [L]
</IfModule>

# END WordPress
EOD;

			if (!file_put_contents(ABSPATH.'.htaccess', $wp_htaccess) && !is_file(ABSPATH.'.htaccess'))
				return false;

			if(!get_option('permalink_structure'))
				add_option('permalink_structure', '/%postname%/');
			else update_option('permalink_structure', '/%postname%/');

		}

		ob_start();
		include ABSPATH.'.htaccess';
		$htcontent = ob_get_contents();
		ob_end_clean();

		if (strpos($htcontent, '# BEGIN KC Optimized') !== false &&
			strpos($htcontent, '# END KC Optimized') !== false &&
			(
				($advanced == 0 && strpos($htcontent, '# KC Gzip') === false) ||
				($advanced == 1 && strpos($htcontent, '# KC Gzip') !== false)
			)
		)return true;

		if (($advanced == 0 && strpos($htcontent, '# KC Gzip') !== false) ||
			($advanced == 1 && strpos($htcontent, '# KC Gzip') === false)) {
				$htcontent = preg_replace('/# BEGIN KC Optimized(.*)# END KC Optimized/Uis', '', $htcontent);
			}

		if(preg_match("/^https:\/\//", home_url())){
			if(preg_match("/^https:\/\/www\./", home_url())){
				$forceTo = "\nRewriteCond %{HTTPS} =on"."\n".
				           "RewriteCond %{HTTP_HOST} ^www.".str_replace("www.", "", $_SERVER["HTTP_HOST"]);
			}else{
				$forceTo = "\nRewriteCond %{HTTPS} =on"."\n".
						   "RewriteCond %{HTTP_HOST} ^".str_replace("www.", "", $_SERVER["HTTP_HOST"]);
			}
		}else{
			if(preg_match("/^http:\/\/www\./", home_url())){
				$forceTo = "\nRewriteCond %{HTTP_HOST} ^".str_replace("www.", "", $_SERVER["HTTP_HOST"])."\n".
						   "RewriteRule ^(.*)$ ".preg_quote(home_url(), "/")."\/$1 [R=301,L]";
			}else{
				$forceTo = "\nRewriteCond %{HTTP_HOST} ^www.".str_replace("www.", "", $_SERVER["HTTP_HOST"])." [NC]"."\n".
						   "RewriteRule ^(.*)$ ".preg_quote(home_url(), "/")."\/$1 [R=301,L]";
			}
		}

		if(!preg_match("/^https/i", get_option("home"))){
			$notSecure = "RewriteCond %{HTTPS} !=on"."\n";
		}

		if($this->is_trailing_slash()){
			$trailing_slash = "RewriteCond %{REQUEST_URI} \/$"."\n";
		}

		$kchtaccess = '# BEGIN KC Optimized'."\n".
			'<IfModule mod_rewrite.c>'."\n".
			'RewriteEngine On'."\n".
			'RewriteBase /'.$forceTo."\n".
			'RewriteCond %{HTTP_HOST} ^'.$host."\n".
			'RewriteCond %{HTTP_USER_AGENT} !(facebookexternalhit|WhatsApp|Mediatoolkitbot)'."\n".
			'RewriteCond %{REQUEST_METHOD} !POST'."\n".
			$notSecure.
			'RewriteCond %{REQUEST_URI} !(\/){2}$'."\n".
			$trailing_slash.
			'RewriteCond %{QUERY_STRING} !.+'."\n".
			'RewriteCond %{HTTP:Cookie} !(comment_author_|wordpress_logged_in|wp_woocommerce_session)'."\n".
			'RewriteCond %{HTTP:Profile} !^[a-z0-9\"]+ [NC]'."\n".
			'RewriteCond %{DOCUMENT_ROOT}/optimized/$1/index.html -f [or]'."\n".
			'RewriteCond '.ABSPATH.'optimized'.$path.'/$1/index.html -f'."\n";

		if(ABSPATH == "//"){
			$kchtaccess .= "RewriteCond %{DOCUMENT_ROOT}/optimized/$1/index.html -f"."\n";
		}else{
			$kchtaccess .= 'RewriteCond %{DOCUMENT_ROOT}/optimized/$1/index.html -f [or]'."\n";
			$kchtaccess .= 'RewriteCond '.ABSPATH.'optimized'.$path.'/$1/index.html -f'."\n";
		}

		$kchtaccess .= 'RewriteRule ^(.*) "'.$path.'/optimized'.$path.'/$1/index.html" [L]'."\n".'</IfModule>'."\n";

		$kchtaccess .= '<FilesMatch "\.(html|htm)$">'."\n".
							'AddDefaultCharset UTF-8'."\n".
							'<ifModule mod_headers.c>'."\n".
							'FileETag None'."\n".
							'Header unset ETag'."\n".
							'Header set Cache-Control "max-age=0, no-cache, no-store, must-revalidate"'."\n".
							'Header set Pragma "no-cache"'."\n".
							'Header set Expires "Mon, 29 Oct 1923 20:30:00 GMT"'."\n".
							'</ifModule>'."\n".
						'</FilesMatch>'."\n";


if ($advanced == 1) {

		$kchtaccess .= <<<EOD
# KC Gzip
<IfModule mod_deflate.c>
AddType x-font/woff .woff
AddOutputFilterByType DEFLATE image/svg+xml
AddOutputFilterByType DEFLATE text/plain
AddOutputFilterByType DEFLATE text/html
AddOutputFilterByType DEFLATE text/xml
AddOutputFilterByType DEFLATE text/css
AddOutputFilterByType DEFLATE text/javascript
AddOutputFilterByType DEFLATE application/xml
AddOutputFilterByType DEFLATE application/xhtml+xml
AddOutputFilterByType DEFLATE application/rss+xml
AddOutputFilterByType DEFLATE application/javascript
AddOutputFilterByType DEFLATE application/x-javascript
AddOutputFilterByType DEFLATE application/x-font-ttf
AddOutputFilterByType DEFLATE application/vnd.ms-fontobject
AddOutputFilterByType DEFLATE font/opentype font/ttf font/eot font/otf
</IfModule>
# KC LBC
<FilesMatch "\.(ico|pdf|flv|jpg|jpeg|png|gif|js|css|swf|x-html|css|xml|js|woff|woff2|ttf|svg|eot)(\.gz)?$">
<IfModule mod_expires.c>
ExpiresActive On
ExpiresDefault A0
ExpiresByType image/gif A2592000
ExpiresByType image/png A2592000
ExpiresByType image/jpg A2592000
ExpiresByType image/jpeg A2592000
ExpiresByType image/ico A2592000
ExpiresByType image/svg+xml A2592000
ExpiresByType text/css A2592000
ExpiresByType text/javascript A2592000
ExpiresByType application/javascript A2592000
ExpiresByType application/x-javascript A2592000
</IfModule>
<IfModule mod_headers.c>
Header set Expires "max-age=2592000, public"
Header unset ETag
Header set Connection keep-alive
FileETag None
</IfModule>
</FilesMatch>
EOD;
		}

		$kchtaccess .= "\n".'# END KC Optimized'."\n";
		if (!file_put_contents(ABSPATH.'.htaccess', $kchtaccess.$htcontent) && !is_file(ABSPATH.'.htaccess'))
			return false;

		flush_rewrite_rules();
		return true;

	}

	private function is_trailing_slash(){

		if($permalink_structure = get_option('permalink_structure')){
			if(preg_match("/\/$/", $permalink_structure)){
				return true;
			}
		}

		return false;
	}

	private function create_combined ($links = array(), $type = 'js') {

		$name = implode('-', $links);
		$name = md5($name).'.'.$type;

		$content = '';

		foreach ($links as $link) {

			if (strpos($link, $this->surl) !== false) {
				$path = str_replace(array($this->surl, '/'), array(ABSPATH, KDS), $link);
				$path = parse_url($path);
				if (!is_file($path['path'])) {
					// internal file : do not get content if does not exist
					continue;
				}
			}

			$get_content = $this->get_html((is_ssl() ? 'https://' : 'http://').$link);
			$content .= ($type == 'js') ? $this->minify_js ($get_content) : $this->before_combined_css ($get_content, $link);
			$content .= "\n";

		}

		if (!is_dir(ABSPATH.'optimized'))
			wp_mkdir_p(ABSPATH.'optimized');

		if (!file_put_contents(ABSPATH.'optimized/'.$name, $content) && !is_file(ABSPATH.'optimized/'.$name)){
			return false;
		}

		return site_url('/optimized/'.$name);

	}

	private function before_combined_css($css = '', $url = '') {

		$this->url = $url;
		$css = $this->minify_css($css);

		$css = preg_replace("/@import\s+[\"\']([^\;\"\'\)]+)[\"\'];/", "@import url($1);", $css);
		$css = preg_replace_callback("/url\(([^\)\n]*)\)/", array($this, 'img_path_css'), $css);
		$css = preg_replace_callback('/@import\s+url\(([^\)]+)\);/i', array($this, 'import_css_rules'), $css);
		$css = $this->css_charset($css);

		return !empty($css) ? $css : ' ';

	}

	private function img_path_css ($m) {

		$mt = trim($m[1]);
		$http = is_ssl() ? 'https://' : 'http://';
		$fix_url = $http.dirname($this->url);

		if (!preg_match("/data\:image\/svg\+xml/", $mt)){

			$mt = str_replace(array("\"","'"), "", $mt);
			$mt = trim($mt);

			if(!$mt) return "url('')";

			if (preg_match("/^(\/\/|http|\/\/fonts|data:image|data:application)/", $mt)) {

				if (preg_match("/fonts\.googleapis\.com/", $mt))
					$mt = '"'.$mt.'"';

			} else if (preg_match("/^\//", $mt)) {
				$mt = $http.dirname(home_url()).$mt;
			} else if(preg_match("/^\.\/.+/i", $mt)) {
				$mt = str_replace("./", $fix_url."/", $mt);
			} else if(preg_match("/^(?P<up>(\.\.\/)+)(?P<name>.+)/", $mt, $out)){

				$count = strlen($out["up"])/3;
				$url = dirname($this->url);

				for ($i = 1; $i <= $count; $i++)
					$url = substr($url, 0, strrpos($url, "/"));

				//$url = str_replace(array("http:", "https:"), "", $url);
				$mt = $http.$url."/".$out["name"];

			}else $mt = $fix_url."/".$mt;

		}

		return "url(".$mt.")";

	}

	private function import_css_rules ($m) {

		//if (strpos($m[0], site_url()) !== false) {

			if ($content = $this->get_html($matches[1], "?v=".time())) {

				$tmp_url = $this->url;
				$this->url = $m[1];
				$content = $this->img_path_css($content, $m[1]);
				$this->url = $tmp_url;

				return $content;

			}
		//}

		return $m[0];
	}

	private function css_charset ($css = '') {

		preg_match_all('/@charset[^\;]+\;/i', $css, $crs);

		if (count($crs[0]) > 0) {
			$css = preg_replace('/@charset[^\;]+\;/i', "", $css);
			foreach ($crs[0] as $cs)
				$css = $cs."\n".$css;
		}

		return $css;

	}

	private function minifier_html($input = '') {

	    return preg_replace_callback('#<\s*([^\/\s]+)\s*(?:>|(\s[^<>]+?)\s*>)#', array(&$this, 'minifier_html_01'), $input);
	}

	private function minifier_html_01($m) {
        if(isset($m[2])) {
            // Minify inline CSS declaration(s)
            if(stripos($m[2], ' style=') !== false) {
                $m[2] = preg_replace_callback('#( style=)([\'"]?)(.*?)\2#i', array(&$this, 'minifier_html_02'), $m[2]);
            }
            return '<' . $m[1] . preg_replace(
                array(
                    // From `defer="defer"`, `defer='defer'`, `defer="true"`, `defer='true'`, `defer=""` and `defer=''` to `defer` [^1]
                    '#\s(checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped)(?:=([\'"]?)(?:true|\1)?\2)#i',
                    // Remove extra white-space(s) between HTML attribute(s) [^2]
                    '#\s*([^\s=]+?)(=(?:\S+|([\'"]?).*?\3)|$)#',
                    // From `<img />` to `<img/>` [^3]
                    '#\s+\/$#'
                ),
                array(
                    // [^1]
                    ' $1',
                    // [^2]
                    ' $1$2',
                    // [^3]
                    '/'
                ),
            str_replace("\n", ' ', $m[2])) . '>';
        }
        return '<' . $m[1] . '>';
    }

	private function minifier_html_02($m) {
        return $m[1] . $m[2] . $this->minify_css($m[3]) . $m[2];
    }

	private function minify_html($input = '') {

	    if( ! $input = trim($input)) return $input;

	    // Keep important white-space(s) after self-closing HTML tag(s)
	    $input = preg_replace('#(<(?:img|input)(?:\s[^<>]*?)?\s*\/?>)\s+#i', '$1' . $this->X . '\s', $input);
	    // Create chunk(s) of HTML tag(s), ignored HTML group(s), HTML comment(s) and text
	    $input = preg_split('#(' . $this->CH . '|<pre(?:>|\s[^<>]*?>)[\s\S]*?<\/pre>|<code(?:>|\s[^<>]*?>)[\s\S]*?<\/code>|<script(?:>|\s[^<>]*?>)[\s\S]*?<\/script>|<style(?:>|\s[^<>]*?>)[\s\S]*?<\/style>|<textarea(?:>|\s[^<>]*?>)[\s\S]*?<\/textarea>|<[^<>]+?>)#i', $input, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
	    $output = "";
	    foreach($input as $v) {
	        if($v !== ' ' && trim($v) === "") continue;
	        if($v[0] === '<' && substr($v, -1) === '>') {
	            if($v[1] === '!' && strpos($v, '<!--') === 0) { // HTML comment ...
	                // Remove if not detected as IE comment(s) ...
	                if(substr($v, -12) !== '<![endif]-->') continue;
	                $output .= $v;
	            } else {
	                $output .= $this->minify_x($this->minifier_html($v));
	            }
	        } else {
	            // Force line-break with `&#10;` or `&#xa;`
	            $v = str_replace(array('&#10;', '&#xA;', '&#xa;'), $this->X . '\n', $v);
	            // Force white-space with `&#32;` or `&#x20;`
	            $v = str_replace(array('&#32;', '&#x20;'), $this->X . '\s', $v);
	            // Replace multiple white-space(s) with a space
	            $output .= preg_replace('#\s+#', ' ', $v);
	        }
	    }
	    // Clean up ...
	    $output = preg_replace(
	        array(
	            // Remove two or more white-space(s) between tag [^1]
	            '#>([\n\r\t]\s*|\s{2,})<#',
	            // Remove white-space(s) before tag-close [^2]
	            '#\s+(<\/[^\s]+?>)#'
	        ),
	        array(
	            // [^1]
	            '><',
	            // [^2]
	            '$1'
	        ),
	    $output);
	    $output = $this->minify_v($output);
	    // Remove white-space(s) after ignored tag-open and before ignored tag-close (except `<textarea>`)
	    return preg_replace('#<(code|pre|script|style)(>|\s[^<>]*?>)\s*([\s\S]*?)\s*<\/\1>#i', '<$1$2$3</$1>', $output);
	}

	private function minifier_css($input = '') {
	    // Keep important white-space(s) in `calc()`
	    if(stripos($input, 'calc(') !== false) {
	        $input = preg_replace_callback('#\b(calc\()\s*(.*?)\s*\)#i', array(&$this, 'minifier_css_01'), $input);
	    }
	    // Minify ...
	    return preg_replace(
	        array(
	            // Fix case for `#foo [bar="baz"]` and `#foo :first-child` [^1]
	            '#(?<![,\{\}])\s+(\[|:\w)#',
	            // Fix case for `[bar="baz"] .foo` and `@media (foo: bar) and (baz: qux)` [^2]
	            '#\]\s+#', '#\b\s+\(#', '#\)\s+\b#',
	            // Minify HEX color code ... [^3]
	            '#\#([\da-f])\1([\da-f])\2([\da-f])\3\b#i',
	            // Remove white-space(s) around punctuation(s) [^4]
	            '#\s*([~!@*\(\)+=\{\}\[\]:;,>\/])\s*#',
	            // Replace zero unit(s) with `0` [^5]
	            '#\b(?:0\.)?0([a-z]+\b|%)#i',
	            // Replace `0.6` with `.6` [^6]
	            '#\b0+\.(\d+)#',
	            // Replace `:0 0`, `:0 0 0` and `:0 0 0 0` with `:0` [^7]
	            '#:(0\s+){0,3}0(?=[!,;\)\}]|$)#',
	            // Replace `background(?:-position)?:(0|none)` with `background$1:0 0` [^8]
	            '#\b(background(?:-position)?):(0|none)\b#i',
	            // Replace `(border(?:-radius)?|outline):none` with `$1:0` [^9]
	            '#\b(border(?:-radius)?|outline):none\b#i',
	            // Remove empty selector(s) [^10]
	            '#(^|[\{\}])(?:[^\{\}]+)\{\}#',
	            // Remove the last semi-colon and replace multiple semi-colon(s) with a semi-colon [^11]
	            '#;+([;\}])#',
	            // Replace multiple white-space(s) with a space [^12]
	            '#\s+#'
	        ),
	        array(
	            // [^1]
	            $this->X . '\s$1',
	            // [^2]
	            ']' . $this->X . '\s', $this->X . '\s(', ')' . $this->X . '\s',
	            // [^3]
	            '#$1$2$3',
	            // [^4]
	            '$1',
	            // [^5]
	            '0',
	            // [^6]
	            '.$1',
	            // [^7]
	            ':0',
	            // [^8]
	            '$1:0 0',
	            // [^9]
	            '$1:0',
	            // [^10]
	            '$1',
	            // [^11]
	            '$1',
	            // [^12]
	            ' '
	        ),
	    $input);
	}

	private function minifier_css_01($m) {
        return $m[1] . preg_replace('#\s+#', $this->X . '\s', $m[2]) . ')';
    }

	private function minify_css($input = '') {

	    if( ! $input = trim($input)) return $input;

	    $input = preg_replace('/\/\*(.*)\*\//Uis', '', $input);
	    do {
			$input = str_replace(
					array( "\n","  ", ": ", " {", "  ", "	", "{ ", " }"),
					array( '', ' ', ':', '{', " ", "", '{', '}'),
					$input
				);
	    } while (strpos($input, "	") !== false || strpos($input, "{ ") !== false || strpos($input, " }") !== false);

	    return $this->minify_v($input);

	}

	private function minifier_js($input = '') {

	    return preg_replace(
	        array(
	            // Remove inline comment(s) [^1]
	            '#\s*\/\/.*$#m',
	            // Remove white-space(s) around punctuation(s) [^2]
	            '#\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#',
	            // Remove the last semi-colon and comma [^3]
	           // '#[;,]([\]\}])#',
	            // Replace `true` with `!0` and `false` with `!1` [^4]
	            '#\btrue\b#', '#\bfalse\b#', '#\breturn\s+#'
	        ),
	        array(
	            // [^1]
	            "",
	            // [^2]
	            '$1',
	            // [^3]
	           // '$1',
	            // [^4]
	            '!0', '!1', 'return '
	        ),
	    $input);
	}

	private function minify_js($input = '') {

		require_once('kc.vendors.jsmin.php');
		return JSMin::minify($input);

	    if( ! $input = trim($input)) return $input;

	    $input = preg_split('#(' . $this->SS . '|' . $this->CC . '|\/[^\n]+?\/(?=[.,;]|[gimuy]|$))#', $input, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
	    $output = "";
	    foreach($input as $v) {
	        if(trim($v) === "") continue;
	        if(
	            ($v[0] === '"' && substr($v, -1) === '"') ||
	            ($v[0] === "'" && substr($v, -1) === "'") ||
	            ($v[0] === '/' && substr($v, -1) === '/')
	        ) {
	            // Remove if not detected as important comment ...
	            if(strpos($v, '//') === 0 || (strpos($v, '/*') === 0 && strpos($v, '/*!') !== 0 && strpos($v, '/*@cc_on') !== 0)) continue;
	            $output .= $v; // String, comment or regex ...
	        } else {
	            $output .= $this->minifier_js($v);
	        }
	    }
	    return preg_replace(
	        array(
	            // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}` [^1]
	            '#(' . $this->CC . ')|([\{,])([\'])(\d+|[a-z_]\w*)\3(?=:)#i',
	            // From `foo['bar']` to `foo.bar` [^2]
	            '#([\w\)\]])\[([\'"])([a-z_]\w*)\2\]#i'
	        ),
	        array(
	            // [^1]
	            '$1$2$4',
	            // [^2]
	            '$1.$3'
	        ),
	    $output);
	}

	private function minify_x($input = '') {
	    return str_replace(array("\n", "\t", ' '), array($this->X . '\n', $this->X . '\t', $this->X . '\s'), $input);
	}

	private function minify_v($input = '') {
	    return str_replace(array($this->X . '\n', $this->X . '\t', $this->X . '\s'), array("\n", "\t", ' '), $input);
	}

	private function render_path_name ($url = '') {

		if (!is_dir(ABSPATH.'optimized') && wp_mkdir_p(ABSPATH.'optimized') === false)
			return false;

		$uparse = parse_url($url);
		$path = ABSPATH.'optimized';
		$name = 'index.html';

		if (isset($uparse['path'])) {
			$path .= $uparse['path'];
			if (!is_dir($path) && !wp_mkdir_p($path))
				return false;
		}

		return untrailingslashit($path).KDS.$name;

	}

	private function is_internal ($link = '') {
		$parse = parse_url ($link);
		return (strpos($this->surl, $parse['host']) === 0);
	}

	public function deactive () {

		delete_option('kc_optimized');

		if (!is_file(ABSPATH.'.htaccess'))
			return true;
		else if (!is_writable(ABSPATH.'.htaccess'))
			return false;

		ob_start();
		include ABSPATH.'.htaccess';
		$htcontent = ob_get_contents();
		ob_end_clean();

		$htcontent = preg_replace('/# BEGIN KC Optimized(.*)# END KC Optimized/Uis', '', $htcontent);
		$htcontent = trim($htcontent);
		if (!file_put_contents(ABSPATH.'.htaccess', $htcontent) && !is_file(ABSPATH.'.htaccess'))
			return false;

		flush_rewrite_rules();
		return true;

	}

	public function delete_cache ($url = 'all') {

		if ($url == 'all') {
			return kc_remove_dir(ABSPATH.'optimized');
		}else{
			$file = $this->render_path_name($url);
			if ($file !== false && file_exists($file))
				return unlink($file);
		}

	}

	public function update_cache($url = '') {
		if ($this->delete_cache($url) )
			return $this->parse($url);
		return false;
	}

}
