<?php


// handle postback
if(!empty($_POST['action']) && $_POST['action'] == 'install-rafflepress'){
    $post_nonce = $_POST['_wpnonce'];

    // Check for permissions.
    if (! current_user_can('install_plugins')) {
        wp_die('You do not have permisson to install a plugin');
    }

    if ( !wp_verify_nonce( $post_nonce, 'seedprod-rafflepress' ) ) {
        wp_die('Please try again.');
    }

    $download_url = 'https://downloads.wordpress.org/plugin/rafflepress.zip';


    global $hook_suffix;
    
    // Set the current screen to avoid undefined notices.
    set_current_screen();

    // Prepare variables.
    $method = '';
    $url    = add_query_arg(
            array(
                'page' => 'seed_csp4_giveaways'
            ),
            admin_url('admin.php')
        );
    $url = esc_url($url);

    // Start output bufferring to catch the filesystem form if credentials are needed.
    ob_start();
    if (false === ($creds = request_filesystem_credentials($url, $method, false, false, null))) {
        $form = ob_get_clean();
        echo json_encode(array( 'form' => $form ));
        wp_die();
    }

    // If we are not authenticated, make it happen now.
    if (! WP_Filesystem($creds)) {
        ob_start();
        request_filesystem_credentials($url, $method, true, false, null);
        $form = ob_get_clean();
        echo json_encode(array( 'form' => $form ));
        wp_die();
    }

    // We do not need any extra credentials if we have gotten this far, so let's install the plugin.
    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    
    require_once SEED_CSP4_PLUGIN_PATH . 'framework/skin.php';
        
    // Create the plugin upgrader with our custom skin.
    $installer = new Plugin_Upgrader($skin = new ComingSoon_Skin());
    $installer->install($download_url);

    // Flush the cache and return the newly installed plugin basename.
    wp_cache_flush();
    if ($installer->plugin_info()) {
        $plugin_basename = $installer->plugin_info();
        //echo json_encode(array( 'plugin' => $plugin_basename ));
        //wp_die();
    }else{
        //$install_plugin_nonce = wp_create_nonce('install-plugin_rafflepress'));
        // window.open(
        //     "update.php?action=install-plugin&plugin=" +
        //         self.plugins[gplugin]
        //             .slug_base +
        //         "&_wpnonce=" +
        //         response.data,
        //     "_blank"
        // );
    }

    // now try to activate
    $activate = activate_plugins($plugin_basename);




}

if(!empty($_POST['action']) && $_POST['action'] == 'activate-rafflepress'){
    $post_nonce = $_POST['_wpnonce'];
    if ( wp_verify_nonce( $post_nonce, 'seedprod-rafflepress' ) ) {
        $activate = activate_plugins('rafflepress/rafflepress.php');
    }
}




// check if plugin is installed
$plugins = seed_csp4_plugins_active();


$nonce = wp_create_nonce( 'seedprod-rafflepress' );

?>
<div class="wrap columns-2 seed-csp4">
    <div id="seed_csp4_header" style="margin:22px 0">
        <h1 style="    display: flex;
    align-items: center;
    justify-content: center;">
            <img style="width:290px;margin-right:10px;margin-bottom: -2px;vertical-align: text-bottom;" src="<?php echo SEED_CSP4_PLUGIN_URL ?>public/images/rafflepress-logo.png"> 
            <span style="padding:0 20px;font-size:32px">+</span>
            <img style="width:230px;margin-right:10px;margin-bottom: -2px;vertical-align: text-bottom;" src="<?php echo SEED_CSP4_PLUGIN_URL ?>public/images/seedprod-logo.png"> 
           
        </h1>

    </div>
    <br><br>

    <div id="seed_csp4_body" style="text-align:center;background:#fff;max-width:1200px;padding:50px">
    <h1 style="font-size:28px; font-weight:bold">The Best WordPress Giveaway Plugin by the SeedProd Team</h1>
        <p  style="font-size:20px;"> Did you know that giveaways / contests are one of the fastest ways to increase your website traffic, get more social media followers, and grow your email list?</p><p style="font-size:20px;margin:30px  0 40px">This is why we built, RafflePress, to help you grow your website, FASTER!
        </p>
       
        <?php if($plugins['rafflepress']== 'Not Installed'){ ?>
        <form action="admin.php?page=seed_csp4_giveaways" method="POST">
            <input type="hidden" name="_wpnonce" value="<?php echo $nonce ?>">
            <input type="hidden" name="action" value="install-rafflepress">
            <button style="background-color:#f3470e; color:#fff; padding: 25px 60px; border-radius:4px; border:none; font-size: 24px  ">Install RafflePress Plugin (Free)</button>
        </form>
        <?php } ?>
        <?php if($plugins['rafflepress']== 'Inactive'){ ?>
        <form action="admin.php?page=seed_csp4_giveaways" method="POST">
            <input type="hidden" name="_wpnonce" value="<?php echo $nonce ?>">
            <input type="hidden" name="action" value="activate-rafflepress">
            <button  style="background-color:#f3470e; color:#fff; padding: 25px 60px; border-radius:4px; border:none; font-size: 24px  ">Activate RafflePress</button>
        <form>
        <?php } ?>
        <?php if($plugins['rafflepress']== 'Active'){ ?>
       

        <!-- <a href="admin.php?page=rafflepress_lite#/welcome"  style="background-color:#f3470e; color:#fff; padding: 20px 40px; border-radius:4px; border:none; font-size: 22px  ">Visit RafflePress Settings</a> -->
        <script>location.href = 'admin.php?page=rafflepress_lite#/welcome'</script>
  
        <?php } ?>

        <p style="font-size:20px;margin:60px  0 40px">Want to learn more about RafflePress? Watch the video below!</p>

        <a href="#" class="js-tingle-rafflepress"><img style="max-width:100%" src="<?php echo SEED_CSP4_PLUGIN_URL ?>public/images/home-video-teaser.png"></a>

        <div style="">
        <p style="font-size:20px;margin:60px  0 30px">To your continued success,</p>
        <img style="margin-right:10px;margin-bottom: -2px;vertical-align: text-bottom;" src="<?php echo SEED_CSP4_PLUGIN_URL ?>public/images/jt-compressor.png"> 
        <p style="font-size:20px;margin:30px  0 0px">John Turner</p>

        <p style="font-size:20px;margin:5px  0 40px">Founder of SeedProd</p>
        </div>
       
       
    </div>
</div>

<style>
.tingle-modal{
    z-index:10000;
}
</style>

<script>
var btn6 = document.querySelector('.js-tingle-rafflepress');
btn6.addEventListener('click', function () {
    var modalSurprise = new tingle.modal({
        onClose: function () {
            modalSurprise.destroy();
        }
    });
    modalSurprise.setContent('<div class="video-responsive"><iframe width="100%" height="400" src="https://www.youtube.com/embed/r62HgG6wzQw?autoplay=1" frameborder="0" allowfullscreen></iframe></div>');
    modalSurprise.open();
});
</script>


