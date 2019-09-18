<#
    
    
    if( data === undefined )
    data = {};
    
    var atts 				= ( data.atts !== undefined ) ? data.atts : {},
    style           = ( atts['style'] !== undefined ) ? atts['style'] : '',
    title           = ( atts['title'] !== undefined ) ? atts['title'] : '',
    icon_show       = ( atts['icon_show'] !== undefined ) ? atts['icon_show'] : '',
    icon            = ( atts['icon'] !== undefined ) ? atts['icon'] : '',
    icon_txt        = ( atts['icon_txt'] !== undefined ) ? atts['icon_txt'] : '',
    icon_float      = ( atts['icon_float'] !== undefined ) ? atts['icon_float'] : '',
    link            = ( atts['link'] !== undefined ) ? atts['link'] : '',
    link_url        = '#',
    link_title      = '',
    link_target     = '',
    custom_class    = ( atts['custom_class'] !== undefined ) ? atts['custom_class'] : '',
    main_class      = [],
    link_arr        = [];
    
    main_class = kc.front.el_class( atts );
    
main_class.push( 'kc-pro-button kc-button-' + style );

if ( custom_class !=='' ) {
    main_class.push( custom_class );
}

if ( link !== '' ) {
    
    link_arr = link.split('|');
    
    if ( link_arr[0] !== undefined )
        link_url = link_arr[0];
    else
        link_url = '#';
    
    
    if ( link_arr[1] !== undefined )
        link_title = link_arr[1];
    
    if ( link_arr[2] !== undefined )
        link_target = link_arr[2];
    
}

if ( icon_show == 'yes' )
    icon_txt = ' <i class="' + icon + '"></i> ';

#>

<div class="{{{main_class.join(' ')}}}">
    <a href="{{{link_url}}}" title="{{{link_title}}}" target="{{{link_target}}}">
        <#
        if ( icon_show == 'yes' && icon_float == 'before' ){ #>
        <span class="creative_icon creative_icon_left">{{{icon_txt}}}</span>
        <# } #>
        <span class="creative_title">{{{title}}}</span>
        <#
        if ( icon_show == 'yes' && icon_float == 'after' ){ #>
        <span class="creative_icon creative_icon_right">{{{icon_txt}}}</span>
        <# } #>
    </a>
</div>
