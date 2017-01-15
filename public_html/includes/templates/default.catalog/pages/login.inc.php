<aside id="sidebar">
  <?php include vmod::check(FS_DIR_HTTP_ROOT . WS_DIR_TEMPLATE . 'views/column_left.inc.php'); ?>
</aside>

<main id="content">
  <!--snippet:notices-->
  <!--snippet:breadcrumbs-->

  <div class="row">
    <div class="col-md-halfs">
      <div class="box-login" class="box">

        <h2><?php echo language::translate('title_sign_in', 'Sign In'); ?></h2>

        <?php echo functions::form_draw_form_begin('login_form', 'post', null, false, 'style="max-width: 320px;"'); ?>
          <?php echo functions::form_draw_hidden_field('redirect_url', true); ?>

          <div class="form-group">
            <?php echo functions::form_draw_email_field('email', true, 'placeholder="'. language::translate('title_email_address', 'Email Address') .'"'); ?>
          </div>

          <div class="form-group">
            <?php echo functions::form_draw_password_field('password', '', 'placeholder="'. language::translate('title_password', 'Password') .'"'); ?>
          </div>

          <div class="checkbox">
            <label><?php echo functions::form_draw_checkbox('remember_me', '1'); ?> <?php echo language::translate('title_remember_me', 'Remember Me'); ?></label>
          </div>

          <p class="btn-grup">
            <?php echo functions::form_draw_button('login', language::translate('title_sign_in', 'Sign In')); ?>
          </p>

        <?php echo functions::form_draw_form_end(); ?>
      </div>
    </div>

    <div class="col-md-halfs">

      <h2><?php echo language::translate('title_create_an_account', 'Create an Account'); ?></h2>

      <ul>
        <li><?php echo language::translate('description_get_access_to_all_order_history', 'Get access to all your order history.'); ?></li>
        <li><?php echo language::translate('description_save_your_cart_items', 'Save your shopping cart for a later visit.'); ?></li>
        <li><?php echo language::translate('description_access_your_cart_simultaneously', 'Access your shopping cart from different computers. Even simultaneously!'); ?></li>
        <li><?php echo language::translate('description_faster_checkout_with_prefilled_details', 'Faster checkout with prefilled customer details.'); ?></li>
        <li><?php echo language::translate('description_recceive_new_offers', 'Recceive information about new offers and great deals.'); ?></li>
      </ul>

      <p><a class="btn btn-default" href="<?php echo document::href_ilink('create_account'); ?>"><?php echo language::translate('title_register_now', 'Register Now'); ?></a><p>
    </div>
  </div>
</main>