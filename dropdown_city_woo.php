<?php
// Replace the city text field by a dropdown of cities for a specific country in Woocommerce
function get_cities_options(){
    $domain = 'woocommerce'; // The domain text slug

    return array(
        ''          => __('Select a city', $domain),
        'Abhā'      => 'Abhā',      'Abqaiq'    => 'Abqaiq',
        'Al-Baḥah'  => 'Al-Baḥah',  'Al-Dammām' => 'Al-Dammām',
        'Al-Hufūf'  => 'Al-Hufūf',  'Al-Jawf'   => 'Al-Jawf',
        'Al-Kharj'  => 'Al-Kharj',  'Al-Khubar' => 'Al-Khubar',
        'Al-Qaṭīf'  => 'Al-Qaṭīf',  'Al-Ṭaʾif'  => 'Al-Ṭaʾif',
        'ʿArʿar'    => 'ʿArʿar',    'Buraydah'  => 'Buraydah',
        'Dhahran'   => 'Dhahran',   'Ḥāʾil'     => 'Ḥāʾil',
        'Jiddah'    => 'Jiddah','Jīzān'     => 'Jīzān',
        'Khamīs Mushayt'            => 'Khamīs Mushayt',
        'King Khalīd Military City' => 'King Khalīd Military City',
        'Mecca'     => 'Mecca',     'Medina'    => 'Medina',
        'Najrān'    => 'Najrān',    'Ras Tanura'=> 'Ras Tanura',
        'Riyadh'    => 'Riyadh',    'Sakākā'    => 'Sakākā',
        'Tabūk'     => 'Tabūk',     'Yanbuʿ'    => 'Yanbuʿ',
        'Other'     => __('Other cities (not listed)', $domain),
    );
}

// add an additional field
add_filter( 'woocommerce_checkout_fields' , 'additional_checkout_city_field' );
function additional_checkout_city_field( $fields ) {
    // Inline CSS To hide the fields on start
    ?><style> #billing_city2_field.hidden, #shipping_city2_field.hidden {display:none;}</style><?php

    $fields['billing']['billing_city2'] = array(
        'placeholder'   => _x('Other city', 'placeholder', 'woocommerce'),
        'required'  => false,
        'priority'  => 75,
        'class'     => array('form-row-wide hidden'),
        'clear'     => true
    );

    $fields['shipping']['shipping_city2'] = array(
        'placeholder'   => _x('Other city', 'placeholder', 'woocommerce'),
        'required'  => false,
        'priority'  => 75,
        'class'     => array('form-row-wide hidden'),
        'clear'     => true
    );

    return $fields;
}

// Add checkout custom select fields
add_action( 'wp_footer', 'custom_checkout_city_field', 20, 1 );
function custom_checkout_city_field() {
    // Only checkout page
    if( is_checkout() && ! is_wc_endpoint_url() ):

    $country = 'SA'; //  <=== <=== The country code

    $b_city  = 'billing_city';
    $s_city  = 'shipping_city';
    $billing_city_compo    = 'name="'.$b_city.'" id="'.$b_city.'"';
    $shipping_city_compo   = 'name="'.$s_city.'" id="'.$s_city.'"';
    $end_of_field          = ' autocomplete="address-level2" value="">';
    $billing_text_field    = '<input type="text" class="input-text" ' . $billing_city_compo  . $end_of_field;
    $shipping_text_field   = '<input type="text" class="input-text" ' . $shipping_city_compo . $end_of_field;
    $billing_select_field  = '<select ' . $billing_city_compo  . $end_of_field;
    $shipping_select_field = '<select ' . $shipping_city_compo . $end_of_field;

    ?>
    <script type="text/javascript">
    jQuery(function($){
        var a   = <?php echo json_encode( get_cities_options() ); ?>,           fc = 'form.checkout',
            b   = 'billing',                s   = 'shipping',               ci = '_city2',
            bc  = '<?php echo $b_city; ?>', sc = '<?php echo $s_city; ?>',  co = '_country',
            bci = '#'+bc,                   sci = '#'+sc,                   fi = '_field',
            btf = '<?php echo $billing_text_field; ?>',     stf = '<?php echo $shipping_text_field; ?>',
            bsf = '<?php echo $billing_select_field; ?>',   ssf = '<?php echo $shipping_select_field; ?>',
            cc  = '<?php echo $country; ?>';

        // Utility function that fill dynamically the select field options
        function dynamicSelectOptions( type ){
            var select = (type == b) ? bsf : ssf,
                fvalue = (type == b) ? $(bci).val() : $(sci).val();


            $.each( a, function( key, value ){
                selected = ( fvalue == key ) ? ' selected' : '';
                selected = ( ( fvalue == '' || fvalue == undefined ) && key == '' ) ? ' selected' : selected;
                select += '<option value="'+key+'"'+selected+'>'+value+'</option>';
            });
            select += '</select>';

            if ( type == b ) 
                $(bci).replaceWith(select);
            else 
                $(sci).replaceWith(select);
        }

        // Utility function that will show / hide the "country2" additional text field
        function showHideCity2( type, city ){
            var field   = (type == b) ? bci : sci,
                country = $('#'+type+co).val();

            if( country == cc && city == 'Other' && $('#'+type+ci+fi).hasClass('hidden') ){
                $('#'+type+ci+fi).removeClass('hidden');
            } else if( country != cc || ( city != 'Other' && ! $('#'+type+ci+fi).hasClass('hidden') ) ) {
                $('#'+type+ci+fi).addClass('hidden');
                if( country != cc && city == 'Other' ){
                    $(field).val('');
                }
            }
        }

        // On billing country change
        $(fc).on('change', '#'+b+co, function(){
            var bcv = $(bci).val();
            if($(this).val() == cc){
                if( $(bci).attr('type') == 'text' ){
                    dynamicSelectOptions(b);
                    showHideCity2( b, $(bci).val() );
                }
            } else {
                if( $(bci).attr('type') != 'text' ){
                    $(bci).replaceWith(btf);
                    $(bci).val(bcv);
                    showHideCity2( b, $(bci).val() );
                }
            }
        });

        // On shipping country change
        $(fc).on('change', '#'+s+co, function(){
            var scv = $(sc).val();
            if($(this).val() == cc){
                if( $(sci).attr('type') == 'text' ){
                    dynamicSelectOptions(s);
                    showHideCity2( s, $(sci).val() );
                }
            } else {
                if( $(sci).attr('type') != 'text' ){
                    $(sci).replaceWith(stf);
                    $(sci).val(scv);
                    showHideCity2( s, $(sci).val() );
                }
            }
        });

        // On billing city change
        $(fc).on('change', bci, function(){
            showHideCity2( b, $(this).val() );
        });

        // On shipping city change
        $(fc).on('change', sci, function(){
            showHideCity2( s, $(this).val() );
        });
    });
    </script>
    <?php
    endif;
}

// Check  for city 2 fields if billing or/and shipping city fields is "Other"
add_action('woocommerce_checkout_process', 'cbi_cf_process');
function cbi_cf_process() {
    // Check billing city 2 field
    if( isset($_POST['billing_city2']) && empty($_POST['billing_city2']) && $_POST['billing_city'] == 'Other' ){
        wc_add_notice( __( "Please fill in billing city field" ), "error" );
    }

    // Updating shipping city 2 field
    if( isset($_POST['shipping_city2']) && empty($_POST['shipping_city2']) && $_POST['shipping_city'] == 'Other' ){
        wc_add_notice( __( "Please fill in shipping city field" ), "error" );
    }
}

// Updating billing and shipping city fields when using "Other"
add_action( 'woocommerce_checkout_create_order', 'update_order_city_field', 30, 2 );
function update_order_city_field( $order, $posted_data ) {
    // Updating billing city from 'billing_city2'
    if( isset($_POST['billing_city2']) && ! empty($_POST['billing_city2']) && $_POST['billing_city'] == 'Other' ){
        $order->set_billing_city(sanitize_text_field( $_POST['billing_city2'] ) );
    }

    // Updating shipping city
    if( isset($_POST['shipping_city2']) && ! empty($_POST['shipping_city2']) && $_POST['shipping_city'] == 'Other' ){
        $order->set_shipping_city(sanitize_text_field( $_POST['shipping_city'] ) );
    }
}