<div class="wrap columns-2 seed-csp4">
    <div id="seed_csp4_header">
        <h1>
            <img style="width:150px;margin-right:10px;margin-bottom: -2px;vertical-align: text-bottom;" src="<?php echo SEED_CSP4_PLUGIN_URL ?>public/images/seedprod-logo.png"> 
            Coming Soon Page and Maintenance Mode Lite
            <span class="seed_csp4_version" style="font-size: 10px;"> Version <?php echo SEED_CSP4_VERSION; ?></span>
        </h1>

    </div>

    <div id="seed_csp4_body">

        <div id="stockimages-popup">
        <div class="seed-ribbon">
        <span>Pro</span>
        </div>
        <h1 style="line-height:1.2; text-align:center">Upgrade to the Pro Version and Get<br>Access to Over 500,000 Free Stock Images</h1>
                <!-- <div id="seed-bg-images-form"><input type="email" id="seed-bg-images-email"  value="<?php echo get_option('admin_email')  ?>" /><button id="seed-bg-images-btn" class="button-primary">Send Me FREE Background Images</button></div> -->
                <script>
                jQuery( "#seed-bg-images-btn" ).click(function(e) {
                    e.preventDefault();
                    jQuery("#drip-email").val(jQuery("#seed-bg-images-email").val());
                    jQuery("#drip-submit").click();
                });
                </script>
                <div id="display-stock-images" class="seed-csp4-clear"></div>
        </div>

        <?php
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('imagesloaded');
        wp_enqueue_script('masonry');
        ?>
        <script>

        var stockimages_page = '1';
        var stockimages_query = '';
        <?php $stockimages_ajax_url = html_entity_decode(wp_nonce_url('admin-ajax.php?action=seed_csp4_backgrounds','seed_csp4_backgrounds')); ?>
        var index_stockimages = "<?php echo $stockimages_ajax_url; ?>";

        get_stockimages(stockimages_page);

        function get_stockimages(p,q){
            var stockimages_url = index_stockimages+'&query='+stockimages_query+'&page='+stockimages_page;

            jQuery.get( stockimages_url , function( data ) {

            jQuery("#display-stock-images").html(data);
            
            jQuery('#stockimages-popup .grid').imagesLoaded( {
            // options...
            },
            function() {
                jQuery('#stockimages-popup .grid').masonry({
                    // options
                    isFitWidth: true,
                    itemSelector: '#stockimages-popup .grid-item',
                    columnWidth: 200,
                    gutter: 10
                    });

            }
            );

            if(stockimages_query != ''){
                jQuery('#cspv5_background_search .query').val(stockimages_query);
            }

            jQuery('#stockimages-popup .pagination a').click(function (e) {
                e.preventDefault();
                var href =jQuery(this).attr('href').split('?')[1];
                console.log(href);
                var QueryString = function () {
                // This function is anonymous, is executed immediately and
                // the return value is assigned to QueryString!
                var query_string = {};
                var stockimages_query = href;
                var vars = stockimages_query.split("&");
                for (var i=0;i<vars.length;i++) {
                    var pair = vars[i].split("=");
                        // If first entry with this name
                    if (typeof query_string[pair[0]] === "undefined") {
                    query_string[pair[0]] = decodeURIComponent(pair[1]);
                        // If second entry with this name
                    } else if (typeof query_string[pair[0]] === "string") {
                    var arr = [ query_string[pair[0]],decodeURIComponent(pair[1]) ];
                    query_string[pair[0]] = arr;
                        // If third or later entry with this name
                    } else {
                    query_string[pair[0]].push(decodeURIComponent(pair[1]));
                    }
                } 
                return query_string;
                }();

                stockimages_page = QueryString.page;
                stockimages_query = QueryString.query;


                get_stockimages(stockimages_page,stockimages_query);
            });

            jQuery('#cspv5_background_search .search').click(function (e) {
                e.preventDefault();
                stockimages_query =  jQuery('#cspv5_background_search .query').val();
                stockimages_page='1';
                //console.log(page);
                //console.log(query);
                get_stockimages(stockimages_page,stockimages_query);
            });

            jQuery( "#stockimages-popup .grid-item" ).append( '<div class="seed-csp4-middle"><div class="seed-csp4-text">Get SeedProd</div></div>' );
            jQuery( ".seed-csp4-text" ).click(function() {
                jQuery('.exit-popup-link').magnificPopup('open');
                window.open('<?php echo seed_csp4_admin_upgrade_link( 'stock-images' ); ?>','_blank');
            });



            });

        }


        </script>
    </div>
</div>

<?php include SEED_CSP4_PLUGIN_PATH.'resources/views/exit-pop.php';?>

