<?php
/**
*
*	King Composer
*	(c) KingComposer.com
*	kc.extension.php
*
*/
if(!defined('ABSPATH')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

/*
*	Extensions class
*/

class kc_extensions {
	
	private $tab = 'store';
	private $page = 1;
	private $path = '';
	private $errors = array();
	private $upload_extension = array();
	
	private $api_url = '';
	private $scheme = '';
	
	function __construct(){
		
		$this->path = untrailingslashit(ABSPATH).KDS.'wp-content'.KDS.'uploads'.KDS.'kc_extensions'.KDS;
		
		$this->scan_blacklist();
		
		$this->scheme = is_ssl() ? 'https' : 'http';
		$this->api_url = $this->scheme.'://extensions.kingcomposer.com/';
		
		if (is_admin()) {
			
			add_action ('admin_menu', array( &$this, 'admin_menu' ), 1);
			if (isset($_GET['tab']) && !empty($_GET['tab']))
				$this->tab = $_GET['tab'];
			if (isset($_GET['page']) && !empty($_GET['page']))
				$this->page = $_GET['page'];
				
			add_action('kc_list_extensions_store', array(&$this, 'extensions_store'));
			add_action('kc_list_extensions_installed', array(&$this, 'extensions_installed'));
			add_action('kc_list_extensions_upload', array(&$this, 'extensions_upload'));
			
			add_action('init', array(&$this, 'process_bulk_action'));
			
		}
		
		$this->load_extensions();
		
	}
	
	public function load_xml($url = '', $arg = array()) {
		
		$options = array(
			"http" => array(
	        "header" => "Referer: ".$_SERVER['HTTP_HOST']."\r\n".
	        			"Scheme: ".$this->scheme."\r\n".
	        			"Cookie: PHPSESSID=".str_replace('=', '', base64_encode($_SERVER['HTTP_HOST']))."\r\n",
	        "ignore_errors" => true,
	    ));
		
		if (count($arg) > 0) {
			$options['http']['header'] .= implode("\r\n", $arg);
		}
				    
		$context = @stream_context_create($options);
		
		if ($url === null)
			return $context;
		
		@libxml_set_streams_context($context);
					
		return @simplexml_load_file($url);
		
	}
	
	public function admin_menu() {
		
		$capability = apply_filters('access_kingcomposer_capability', 'access_kingcomposer');
		add_submenu_page(
			'kingcomposer',
			__('Extensions', 'kingcomposer'), 
			__('Extensions', 'kingcomposer'),
			$capability,
			'kc-extensions',
			array( &$this, 'screen_display' )
		);
		
	}
	
	public function screen_display() {
		
		include 'extensions/kc.screen.tmpl.php';
		
	}
	
	public function extensions_store($page = 1) {
		
		global $kc;
		$pdk = $kc->get_pdk();
		
		if (isset($pdk['pack']) && isset($pdk['key']) && $pdk['pack'] != 'trial' && !empty($pdk['key']) && $pdk['stt'] == 1)
			$key = $pdk['key'];
		else $key = '';
		
		$response = @wp_remote_get(
			$this->api_url.'catalog/',
			array(
				"headers" => array(
					"license" => $key,
					"pack" => isset($pdk['pack']) ? $pdk['pack'] : '',
					"theme" => sanitize_title(basename(get_template_directory())),
					"domain" => site_url(),
					"time" => time()+604800,
					"q" => (isset($_GET['q']) ? $_GET['q'] : ''),
					"filter" => (isset($_GET['filter']) ? $_GET['filter'] : ''),
					"paged" => (isset($_GET['paged']) ? $_GET['paged'] : ''),
				),
				'timeout'     => 1200,
			)
		);
		
		if (is_wp_error($response)) {
			echo '<center><h2 style="color: #888; margin-top: 50px">'.__('Sorry, Can not connect to server at this time. Please check your internet connection and ', 'kingcomposer').'<a href="#" onclick="window.location.reload()">Try again</a></h2></center>';
			return;
		}
		
		$data = @json_decode($response['body'], true);
		
		if (!is_array($data)) {
			echo '<center><h2 style="color: #888; margin-top: 50px">'.__('Sorry, An error has occurred, we will fix it soon.', 'kingcomposer').'</h2>'.$response['body'].'</center>';
			return;
		}
		
		$items = $data['items'];
		$total = $data['total'];
		$pages = $data['pages'];
		$installs = $this->load_installed('all');
		$actives = (array) get_option( 'kc_active_extensions', array() );
		
		include 'extensions/kc.store.tmpl.php';

	}
		
	public function extensions_installed ($page = 1) {
		
		$items = $this->load_installed('all');
		$actives = (array) get_option( 'kc_active_extensions', array() );
		include 'extensions/kc.installed.tmpl.php';
		
	}
	
	public function extensions_upload ($page = 1) {
		
		$upload = $this->upload_extension;
		$errors = $this->errors;
		include 'extensions/kc.upload.tmpl.php';
		
	}
	
	public function load_installed ($mod = 'all') {
		
		if (!is_dir($this->path) && !mkdir($this->path, 0755)) {
			echo '<center><h2 style="color: #888; margin-top: 50px">'.__('Error, could not create extensions folder '.$this->path, 'kingcomposer').'</h2></center>';
			return;
		}
		
		$items = array();
		$files = scandir($this->path, 0);
		
		foreach ($files as $file) {
			
			if (is_dir($this->path.$file) && $file != '.' && $file != '..') {
				
				if (file_exists($this->path.$file.KDS.'index.php')) {
					
					$data = get_file_data($this->path.$file.KDS.'index.php', array(
						'Extension Name',
						'Extension Preview',
						'Description',
						'Version',
						'Author',
						'Author URI',
					));
					
					$items[$file] = array(
						'name' => !empty($data[0]) ? $data[0] : 'Unknow',
						'Extension Preview' => !empty($data[1]) ? $data[1] : '',
						'Description' => !empty($data[2]) ? $data[2] : '',
						'Version' => !empty($data[3]) ? $data[3] : '1.0',
						'Author' => !empty($data[4]) ? $data[4] : 'Unknow',
						'Author URI' => !empty($data[5]) ? $data[5] : '#unknow',
						'extension' => sanitize_title($file)
					);
				}
			}
		}
		
		return $items;
		
	}
	
	public function load_extensions () {
		
		$actives = (array) get_option( 'kc_active_extensions', array() );
		foreach ($actives as $name => $stt) {
			if ($stt == 1) {
				if (file_exists($this->path.$name.KDS.'index.php')) {
					require_once($this->path.$name.KDS.'index.php');
					$ex_class = 'kc_extension_'.str_replace('-', '_', sanitize_title($name));
					if (class_exists($ex_class)) {
						new $ex_class();
					} else {
						$this->errors[] = 'Could not find the PHP classname "'.$ex_class.'" in the extenstion "/'.$name.KDS.'index.php"';
						unset($actives[$name]);
						update_option('kc_active_extensions', $actives);
					} 
				} else {
					$this->errors[] = 'Could not find the extension file /'.$name.KDS.'index.php';
					unset($actives[$name]);
					update_option('kc_active_extensions', $actives);
				}
			}
		}
		
		return $this->errors;
		
	}
	
	public function list_table( $items, $actives ) {
		
		$KCExtTable = new KC_Extensions_List();
		$KCExtTable->set_data( $items, $actives );
		$KCExtTable->prepare_items();
		
		?>
		<div class="plugins">
			<?php $KCExtTable->display(); ?>
		</div>
		<?php
	}
	
	
	public function process_bulk_action() {
		
		if (
			isset($_POST['action']) && 
			isset($_POST['kc-extension-action']) && 
			isset($_POST['kc-nonce']) && 
			wp_verify_nonce($_POST['kc-nonce'], 'kc-nonce')
		){
			
			if (!is_admin() || !current_user_can('upload_files')) {
				header('HTTP/1.0 403 Forbidden');
				exit;
			}
			
			$actives = (array) get_option( 'kc_active_extensions', array() );
			
			$checked = isset($_POST['checked']) ? (array) $_POST['checked'] : array();
			$path = untrailingslashit(ABSPATH).KDS.'wp-content'.KDS.'uploads'.KDS.'kc_extensions'.KDS;
			
			switch ($_POST['action']){
				
				case 'bulk-deactivate' :
					
					foreach( $checked as $ext )
						unset( $actives[ $ext ] );
					
					if (!add_option('kc_active_extensions', $actives, null, 'no'))
						update_option('kc_active_extensions', $actives );
					
					break;
				
				case 'bulk-activate' :
					
					foreach( $checked as $ext )
						$actives[$ext] = 1;
					
					if (!add_option('kc_active_extensions', $actives, null, 'no'))
						update_option('kc_active_extensions', $actives );
					
					break;
				
				case 'bulk-update' :
					
					break;
				
				case 'bulk-delete' :
					foreach( $checked as $ext ) {
						if (is_dir($path.$ext) && kc_remove_dir($path.$ext) && isset($actives[$ext]))
							unset($actives[$ext]);
					}	
					update_option('kc_active_extensions', $actives);
				break;
				
				case 'upload' : 
					
					$this->tab = 'upload';
					
					if (!class_exists('ZipArchive')) {
						$this->errors[] = 'Server does not support ZipArchive';
					} else if (
						(
							($_FILES["extensionzip"]["type"] == "application/zip") || 
							($_FILES["extensionzip"]["type"] == "application/x-zip") || 
							($_FILES["extensionzip"]["type"] == "application/x-zip-compressed")
						) && 
						($_FILES["extensionzip"]["size"] < 20000000)
					) {
       
						if (move_uploaded_file($_FILES['extensionzip']['tmp_name'], $path.$_FILES['extensionzip']['name']) === true) {
							$zip = new ZipArchive;
							$res = $zip->open($path.$_FILES['extensionzip']['name']);
							if ($res === TRUE) {
								
								$ext = $zip->extractTo($path);
								
								if (is_dir($path.'__MACOSX'))
									kc_remove_dir($path.'__MACOSX');
									
								if ($ext ===true) {
								
									$ext = trim($zip->getNameIndex(0), KDS);
									
									if (!file_exists($path.$ext.KDS.'index.php'))
										$this->errors[] = 'Missing index.php file of extension';
									
									$this->upload_extension[0] = $_FILES['extensionzip']['name'];
									$this->upload_extension[1] = $ext;
									
								} else $this->errors[] = 'Could not extract file';
								$zip->close();
								
								
							} else {
								$this->errors[] = 'Could not unzip';
							}
						} else {
							$this->errors[] = 'Error upload file';
						}
					} else {
						$this->errors[] = 'Invalid file type';
					}
					
				break;
				
			}
			
		}
	
	}
	
	public function scan_blacklist() {
		
		if (is_dir($this->path)) {
			
			if (!is_file($this->path.'index.html')) {
				@file_put_contents($this->path.'index.html', '');
			}
			
			$blacklist_dirs = array('background-image-cropper', 'XAttacker', 'reup', 'visual');
			$files = @scandir($this->path, 0);
			
			if (isset($files) && is_array($files) && count($files) > 0) {
				foreach ($files as $file) {
					if ($file != '.' && $file != '..') {
						if (is_dir($this->path.$file) && in_array($file, $blacklist_dirs)) {
							kc_remove_dir($this->path.$file);
						}
						if (is_file($this->path.$file) && strpos($file, '.zip') !== false) {
							@unlink($this->path.$file);
						}
					}
				}
			}
		}
		
	}
	
}

class kc_extension {
	
	public $path;
	public $url;
	
	public function init($file) {
		
		$this->path = dirname($file);
		$this->url = site_url('/wp-content/uploads/kc_extensions/'.basename(dirname($file)));
		
	}
	
	public function map($args) {
		
		global $kc;
		if (empty($args) || !is_array($args))
			return;
		
		$kc->add_map($args);
		
	}
	
	public function output($name, $callback) {
		if (is_callable($callback)) {
			add_shortcode ($name, $callback);
		}
	}
		
}

new kc_extensions();

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class KC_Extensions_List extends WP_List_Table
{
	private $table_data = array();
	private $actives = array();
	/** Class constructor */
	public function __construct() {
		
		parent::__construct( array(
			'singular' => __( 'Extension', 'kingcomposer' ), //singular name of the listed records
			'plural'   => __( 'Extensions', 'kingcomposer' ), //plural name of the listed records
			'ajax'     => true //should this table support ajax?
		
		) );
		
	}
	
	/**
	 * @return array
	 */
	protected function get_table_classes() {
		return array( 'widefat', $this->_args['plural'] );
	}
	
	/**
	 * Prepare the items for the table to process
	 *
	 * @return Void
	 */
	public function prepare_items()
	{
	
		$columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();
		$data = $this->table_data;
		
		usort( $data, array( &$this, 'sort_data' ) );
		
		$perPage = 20;
		$currentPage = $this->get_pagenum();
		$totalItems = count($data);
		
		$this->set_pagination_args( array(
			'total_items' => $totalItems,
			'per_page'    => $perPage
		) );
		
		$data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
		
		$this->_column_headers = array($columns, $hidden, $sortable);
		$this->items = $data;
		
	}
	
	/**
	 * Override the parent columns method. Defines the columns to use in your listing table
	 *
	 * @return Array
	 */
	public function get_columns()
	{
		$columns = array(
			'cb'      => '<input type="checkbox" />',
			'name'       => 'Extension Name',
			'description' => 'Description'
		);
		
		return $columns;
	}
	
	/**
	 * Define which columns are hidden
	 *
	 * @return Array
	 */
	public function get_hidden_columns()
	{
		return array();
	}
	
	/**
	 * Define the sortable columns
	 *
	 * @return Array
	 */
	public function get_sortable_columns()
	{
		return array('name' => array('name', false));
	}
	
	/**
	 * Get the table data
	 *
	 * @return Array
	 */
	public function set_data( $items, $actives )
	{
		$this->table_data = $items;
		$this->actives = $actives;
	}
	
	/**
	 * Define what data to show on each column of the table
	 *
	 * @param  Array $item        Data
	 * @param  String $column_name - Current column name
	 *
	 * @return Mixed
	 */
	public function column_default( $item, $column_name )
	{
		switch( $column_name ) {
			case 'name':
			case 'description':
				return $item[ $column_name ];
			
			default:
				return print_r( $item, true ) ;
		}
	}
	
	/**
	 * Allows you to sort the data by the variables set in the $_GET
	 *
	 * @return Mixed
	 */
	private function sort_data( $a, $b )
	{
		// Set defaults
		$orderby = 'name';
		$order = 'asc';
		
		// If orderby is set, use this as the sort column
		if(!empty($_GET['orderby']))
		{
			$orderby = $_GET['orderby'];
		}
		
		// If order is set use this as the order
		if(!empty($_GET['order']))
		{
			$order = $_GET['order'];
		}
		
		
		$result = strcmp( $a[$orderby], $b[$orderby] );
		
		if($order === 'asc')
		{
			return $result;
		}
		
		return -$result;
	}
	
	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'No items found', 'kingcomposer' );
	}
	
	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = array(
			'bulk-activate' => 'Activate',
			'bulk-deactivate' => 'Deactivate',
			'bulk-update' => 'Update',
			'bulk-delete' => 'Delete',
		);
		
		return $actions;
	}
	
	/**
	 * @global string $status
	 * @global int $page
	 * @global string $s
	 * @global array $totals
	 *
	 * @param array $item
	 */
	public function single_row( $item ) {
		
		global $status, $page, $s, $totals;
		
		$idc = rand(334,4343);
		$name = esc_html($item['name']);
		$slug = esc_attr($item['extension']);
		
		?>
		<tr class="<?php
		
		if (isset($this->actives[$slug]) && $this->actives[$slug] == '1')
			echo 'active';
		else echo 'inactive';
		
		?>" data-extension="<?php echo $slug; ?>">
			<th scope="row" class="check-column">
				<label class="screen-reader-text" for="checkbox_<?php echo $idc; ?>">
					<?php _e('Select', 'kingcomposer'); ?> <?php echo $name; ?>
				</label>
				<input type="checkbox" name="checked[]" value="<?php echo $slug; ?>" id="checkbox_<?php echo $idc; ?>">
			</th>
			<td class="plugin-title column-primary">
				<strong><?php echo $name; ?></strong>
				<div class="row-actions visible">
	                <span class="activate">
	                	<a href="#active" class="active" aria-label="Activate <?php echo $name; ?>">
		                	<?php _e('Activate', 'kingcomposer'); ?>
		                </a> |
	                </span>
					<span class="deactivate">
	                	<a href="#deactive" class="deactive" aria-label="Activate <?php echo $name; ?>">
		                	<?php _e('Deactivate', 'kingcomposer'); ?>
		                </a> |
	                </span>
					<span class="delete">
	                	<a href="#delete" class="delete" aria-label="Delete <?php echo $name; ?>">
		                	<?php _e('Delete', 'kingcomposer'); ?>
		                </a>
	                </span>
				</div>
			</td>
			<td class="column-description desc">
				<div class="plugin-description">
					<p><?php echo esc_html($item['Description']); ?></p>
				</div>
				<div class="inactive second plugin-version-author-uri">
					<?php _e('Version', 'kingcomposer'); ?> <?php echo esc_html($item['Version']); ?> |
					<?php _e('By', 'kingcomposer'); ?>
					<a href="<?php echo esc_url($item['Author URI']); ?>" target=_blank>
						<?php echo esc_html($item['Author']); ?>
					</a>
					<?php if (!empty($item['Extension Preview'])) { ?>
						|
						<a href="<?php echo esc_url($item['Extension Preview']); ?>" target=_blank>
							<?php _e('Preview', 'kingcomposer'); ?>
						</a>
					<?php } ?>
				</div>
			</td>
		</tr>
		<?php
		
	}
	
	/**
	 * Display the table
	 *
	 * @since 3.1.0
	 * @access public
	 */
	public function display() {
		
		$singular = $this->_args['singular'];
		
		$this->display_tablenav( 'top' );
		
		$this->screen->render_screen_reader_content( 'heading_list' );
		?>
		<table class="wp-list-table <?php echo implode( ' ', $this->get_table_classes() ); ?>" id="kc-extensions-list">
			<thead>
			<tr>
				<?php $this->print_column_headers(); ?>
			</tr>
			</thead>
			
			<tbody id="the-list"<?php
			if ( $singular ) {
				echo " data-wp-lists='list:$singular'";
			} ?>>
			<?php $this->display_rows_or_placeholder(); ?>
			</tbody>
			
			<tfoot>
			<tr>
				<?php $this->print_column_headers( false ); ?>
			</tr>
			</tfoot>
		
		</table>
		<?php
		$this->display_tablenav( 'bottom' );
	}
	
}
