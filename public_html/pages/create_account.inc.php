<?php

  header('X-Robots-Tag: noindex');
  document::$snippets['head_tags']['noindex'] = '<meta name="robots" content="noindex" />';
  document::$snippets['title'][] = language::translate('title_create_account', 'Create Account');

  breadcrumbs::add(language::translate('title_create_account', 'Create Account'));

  if (!$_POST) {
    foreach (customer::$data as $key => $value) {
      $_POST[$key] = $value;
    }
    $_POST['newsletter'] = '1';
  }

  if (!empty(customer::$data['id'])) {
    notices::add('errors', language::translate('error_already_logged_in', 'You are already logged in'));
  }

  if (!empty($_POST['create_account'])) {

    if (settings::get('captcha_enabled')) {
      $captcha = functions::captcha_get('create_account');
      if (empty($captcha) || $captcha != $_POST['captcha']) notices::add('errors', language::translate('error_invalid_captcha', 'Invalid CAPTCHA given'));
    }

    if (isset($_POST['email'])) $_POST['email'] = strtolower($_POST['email']);

    if (!empty($_POST['email']) && database::num_rows(database::query("select id from ". DB_TABLE_CUSTOMERS ." where email = '". database::input($_POST['email']) ."' limit 1;"))) notices::add('errors', language::translate('error_email_already_registered', 'The email address already exists in our customer database. Please login or select a different email address.'));

    if (empty($_POST['email'])) notices::add('errors', language::translate('error_email_missing', 'You must enter your email address.'));

    if (empty($_POST['password'])) notices::add('errors', language::translate('error_missing_password', 'You must enter a password.'));
    if (empty($_POST['confirmed_password'])) notices::add('errors', language::translate('error_missing_confirmed_password', 'You must confirm your password.'));
    if (isset($_POST['password']) && isset($_POST['confirmed_password']) && $_POST['password'] != $_POST['confirmed_password']) notices::add('errors', language::translate('error_passwords_missmatch', 'The passwords did not match.'));

    if (empty($_POST['firstname'])) notices::add('errors', language::translate('error_missing_firstname', 'You must enter a first name.'));
    if (empty($_POST['lastname'])) notices::add('errors', language::translate('error_missing_lastname', 'You must enter a last name.'));
    //if (empty($_POST['address1'])) notices::add('errors', language::translate('error_missing_address1', 'You must enter an address.'));
    //if (empty($_POST['city'])) notices::add('errors', language::translate('error_missing_city', 'You must enter a city.'));
    //if (empty($_POST['postcode']) && !empty($_POST['country_code']) && reference::country($_POST['country_code'])->postcode_format) notices::add('errors', language::translate('error_missing_postcode', 'You must enter a postcode.'));
    if (empty($_POST['country_code'])) notices::add('errors', language::translate('error_missing_country', 'You must select a country.'));
    if (empty($_POST['zone_code']) && !empty($_POST['country_code']) && reference::country($_POST['country_code'])->zones) notices::add('errors', language::translate('error_missing_zone', 'You must select a zone.'));

    $mod_customer = new mod_customer();
    $result = $mod_customer->validate($_POST);
    if (!empty($result['error'])) notices::add('errors', $result['error']);

    if (empty(notices::$data['errors'])) {

      $customer = new ctrl_customer();

      $customer->data['status'] = 1;

      $fields = array(
        'email',
        'tax_id',
        'company',
        'firstname',
        'lastname',
        'address1',
        'address2',
        'postcode',
        'city',
        'country_code',
        'zone_code',
        'phone',
        'newsletter',
      );

      foreach ($fields as $field) {
        if (isset($_POST[$field])) $customer->data[$field] = $_POST[$field];
      }

      $customer->save();

      $customer->set_password($_POST['password']);

      $aliases = array(
        '%store_name' => settings::get('store_name'),
        '%store_link' => document::ilink(''),
        '%customer_firstname' => $_POST['firstname'],
        '%customer_lastname' => $_POST['lastname'],
        '%customer_email' => $_POST['email'],
        '%customer_password' => $_POST['password']
      );

      $subject = language::translate('email_subject_customer_account_created', 'Customer Account Created');
      $message = strtr(language::translate('email_account_created', "Welcome %customer_firstname %customer_lastname to %store_name!\r\n\r\nYour account has been created. You can now make purchases in our online store and keep track of history.\r\n\r\nLogin using your email address %customer_email and password %customer_password.\r\n\r\n%store_name\r\n\r\n%store_link"), $aliases);

      $email = new email();
      $email->add_recipient($_POST['email'], $_POST['firstname'] .' '. $_POST['lastname'])
            ->set_subject($subject)
            ->add_body($message)
            ->send();

      notices::add('success', language::translate('success_your_customer_account_has_been_created', 'Your customer account has been created.'));

    // Login user
      customer::load($customer->data['id']);

      header('Location: '. document::ilink(''));
      exit;
    }
  }

  $_page = new view();
  echo $_page->stitch('pages/create_account');
