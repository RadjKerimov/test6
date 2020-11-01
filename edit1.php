<?php
  session_start();
  require_once('sql.php');
  require('../function.php');

  $name = trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
  $work = trim(filter_var($_POST['work'], FILTER_SANITIZE_STRING));
  $tel = trim(filter_var($_POST['tel'], FILTER_SANITIZE_NUMBER_INT));
  $address = trim(filter_var($_POST['address'], FILTER_SANITIZE_STRING));
  $id_user = $_POST['id'];


  $result = update_user_info($name, $work, $tel, $address, $id_user, $pdo);
  //print_r(isset($result)); die;



  set_flash_message('success', 'Профиль <b>'. $name .'</b> успешно обновлен.');
  redirect_to('../page_profile.php');
