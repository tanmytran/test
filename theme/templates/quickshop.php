<?php 
    global $post;
    
    $product_id = plsh_get($_POST, 'product');
    $post = get_post($product_id);
    $post->is_quickshop = true;
    setup_postdata($post);
    
    $product = get_product( $post->ID);
?>

<div class="lightbox"></div>

<!-- BEGIN .quick-shop -->
<div class="quick-shop clearfix">
    <div class="content clearfix">

        <?php do_action( 'woocommerce_before_single_product_summary' ); ?>
        
        <div class="main-item">
            <div class="item-info">
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <p class="item-text"><?php echo wp_trim_words(strip_tags(get_the_excerpt()), 35); ?></p>
                <?php
                    if($product->product_type != 'external')
                    {
                        //get form opening
                        $params = array();

                        if($product->product_type == 'variable')
                        {
                            $params = array(
                                'available_variations'  => $product->get_available_variations()
                            );
                        }

                        woocommerce_get_template('single-product/form.php', $params); 
                    }
                ?>
                    <div class="details clearfix">

                        <?php
                            //if product is variable type, show variants
                            if($product->product_type == 'variable')
                            {
                                woocommerce_get_template( 'single-product/variants.php', array(
                                    'available_variations'  => $product->get_available_variations(),
                                    'attributes'   			=> $product->get_variation_attributes(),
                                    'selected_attributes' 	=> $product->get_variation_default_attributes()
                                ) );
                            }
                        ?>

                        <?php 	 		
                        if ( ! $product->is_sold_individually() && $product->product_type != 'external' && $product->product_type != 'grouped')
                        {
                            woocommerce_quantity_input( 
                                array(
                                    'min_value' => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
                                    'max_value' => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product )
                                ) 
                            ); 
                        }
                        ?>

                        <?php woocommerce_template_single_sharing(); ?>

                        <?php woocommerce_template_single_meta(); ?>

                        <?php
                            /**
                             * woocommerce_single_product_summary hook
                             * @all default hooks removed
                             */
                            do_action( 'woocommerce_single_product_summary' );
                        ?>

                    </div><!-- .details -->

                    <?php woocommerce_template_single_add_to_cart(); ?>

                    
                </form>

                <?php do_action('woocommerce_after_add_to_cart_form'); ?>
            </div>
        </div>

        <a href="#" class="close"></a>

    </div>
    <script type="text/javascript">
        stButtons.locateElements();
         (function(e,t,n,r){e.fn.wc_variation_form=function(){e.fn.wc_variation_form.find_matching_variations=function(t,n){var r=[];for(var i=0;i<t.length;i++){var s=t[i],o=s.variation_id;e.fn.wc_variation_form.variations_match(s.attributes,n)&&r.push(s)}return r};e.fn.wc_variation_form.variations_match=function(e,t){var n=!0;for(attr_name in e){var i=e[attr_name],s=t[attr_name];i!==r&&s!==r&&i.length!=0&&s.length!=0&&i!=s&&(n=!1)}return n};this.unbind("check_variations update_variation_values found_variation");this.find(".reset_variations").unbind("click");this.find(".variations select").unbind("change focusin");return this.on("click",".reset_variations",function(t){e(this).closest("form.variations_form").find(".variations select").val("").change();var n=e(this).closest(".product").find(".sku"),r=e(this).closest(".product").find(".product_weight"),i=e(this).closest(".product").find(".product_dimensions");n.attr("data-o_sku")&&n.text(n.attr("data-o_sku"));r.attr("data-o_weight")&&r.text(r.attr("data-o_weight"));i.attr("data-o_dimensions")&&i.text(i.attr("data-o_dimensions"));return!1}).on("change",".variations select",function(t){$variation_form=e(this).closest("form.variations_form");$variation_form.find("input[name=variation_id]").val("").change();$variation_form.trigger("woocommerce_variation_select_change").trigger("check_variations",["",!1]);e(this).blur();e().uniform&&e.isFunction(e.uniform.update)&&e.uniform.update()}).on("focusin",".variations select",function(t){$variation_form=e(this).closest("form.variations_form");$variation_form.trigger("woocommerce_variation_select_focusin").trigger("check_variations",[e(this).attr("name"),!0])}).on("check_variations",function(n,r,i){var s=!0,o=!1,u=!1,a={},f=e(this),l=f.find(".reset_variations");f.find(".variations select").each(function(){e(this).val().length==0?s=!1:o=!0;if(r&&e(this).attr("name")==r){s=!1;a[e(this).attr("name")]=""}else{value=e(this).val();a[e(this).attr("name")]=value}});var c=parseInt(f.data("product_id")),h=f.data("product_variations");h||(h=t.product_variations[c]);h||(h=t.product_variations);var p=e.fn.wc_variation_form.find_matching_variations(h,a);if(s){var d=p.pop();if(d){f.find("input[name=variation_id]").val(d.variation_id).change();f.trigger("found_variation",[d])}else{f.find(".variations select").val("");i||f.trigger("reset_image");alert(woocommerce_params.i18n_no_matching_variations_text)}}else{f.trigger("update_variation_values",[p]);i||f.trigger("reset_image");r||f.find(".single_variation_wrap").slideUp("200")}o?l.css("visibility")=="hidden"&&l.css("visibility","visible").hide().fadeIn():l.css("visibility","hidden")}).on("reset_image",function(t){var n=e(this).closest(".product"),r=n.find("div.images img:eq(0)"),i=n.find("div.images a.zoom:eq(0)"),s=r.attr("data-o_src"),o=r.attr("data-o_title"),u=i.attr("data-o_href");s&&r.attr("src",s);u&&i.attr("href",u);if(o){r.attr("alt",o).attr("title",o);i.attr("title",o)}}).on("update_variation_values",function(t,n){$variation_form=e(this).closest("form.variations_form");$variation_form.find(".variations select").each(function(t,r){current_attr_select=e(r);current_attr_select.data("attribute_options")||current_attr_select.data("attribute_options",current_attr_select.find("option:gt(0)").get());current_attr_select.find("option:gt(0)").remove();current_attr_select.append(current_attr_select.data("attribute_options"));current_attr_select.find("option:gt(0)").removeClass("active");var i=current_attr_select.attr("name");for(num in n){var s=n[num].attributes;for(attr_name in s){var o=s[attr_name];if(attr_name==i)if(o){o=e("<div/>").html(o).text();o=o.replace(/'/g,"\\'");o=o.replace(/"/g,'\\"');current_attr_select.find('option[value="'+o+'"]').addClass("active")}else current_attr_select.find("option:gt(0)").addClass("active")}}current_attr_select.find("option:gt(0):not(.active)").remove()});$variation_form.trigger("woocommerce_update_variation_values")}).on("found_variation",function(t,n){var r=e(this),i=e(this).closest(".product"),s=i.find("div.images img:eq(0)"),o=i.find("div.images a.zoom:eq(0)"),u=s.attr("data-o_src"),a=s.attr("data-o_title"),f=o.attr("data-o_href"),l=n.image_src,c=n.image_link,h=n.image_title;r.find(".variations_button").show();r.find(".single_variation").html(n.price_html+n.availability_html);if(!u){u=s.attr("src")?s.attr("src"):"";s.attr("data-o_src",u)}if(!f){f=o.attr("href")?o.attr("href"):"";o.attr("data-o_href",f)}if(!a){a=s.attr("title")?s.attr("title"):"";s.attr("data-o_title",a)}if(l&&l.length>1){s.attr("src",l).attr("alt",h).attr("title",h);o.attr("href",c).attr("title",h)}else{s.attr("src",u).attr("alt",a).attr("title",a);o.attr("href",f).attr("title",a)}var p=r.find(".single_variation_wrap"),d=i.find(".product_meta").find(".sku"),v=i.find(".product_weight"),m=i.find(".product_dimensions");d.attr("data-o_sku")||d.attr("data-o_sku",d.text());v.attr("data-o_weight")||v.attr("data-o_weight",v.text());m.attr("data-o_dimensions")||m.attr("data-o_dimensions",m.text());n.sku?d.text(n.sku):d.text(d.attr("data-o_sku"));n.weight?v.text(n.weight):v.text(v.attr("data-o_weight"));n.dimensions?m.text(n.dimensions):m.text(m.attr("data-o_dimensions"));p.find(".quantity").show();!n.is_in_stock&&!n.backorders_allowed&&r.find(".variations_button").hide();n.min_qty?p.find("input[name=quantity]").attr("min",n.min_qty).val(n.min_qty):p.find("input[name=quantity]").removeAttr("min");n.max_qty?p.find("input[name=quantity]").attr("max",n.max_qty):p.find("input[name=quantity]").removeAttr("max");if(n.is_sold_individually=="yes"){p.find("input[name=quantity]").val("1");p.find(".quantity").hide()}p.slideDown("200").trigger("show_variation",[n])})};e("form.variations_form").wc_variation_form();e("form.variations_form .variations select").change()})(jQuery,window,document);
    </script>
<!-- END .quick-shop -->
</div>