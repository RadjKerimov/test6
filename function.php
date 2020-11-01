<?php
  //авторизован пользователь?  return true
  function registered_user($text, $redirect){
    if (!isset($_SESSION['email'])) {
      set_flash_message('danger', $text);
      redirect_to($redirect);
      die;
    }
    return true;
  }

  function user_admin($id, $role){


  }

  //Подготовка сообщения 
  function set_flash_message($name, $message){
    $_SESSION[$name] = $message;
  };

  function display_flash_message($name){ 
    if (isset($_SESSION[$name])) {
      echo "<div class=\"alert alert-$name\">$_SESSION[$name]</div>";
      unset($_SESSION[$name]);
    }
  };

  function redirect_to($path){
    header("Location: $path");
  };
  ////////////////////////// 

  /*
    Проверяем если такой email БД 
    return array [email, pass]
  */
  function if_the_user($email, $pass, $pdo){
    $prepare = $pdo->prepare("SELECT `email`,`pass`, `role` FROM `users` WHERE `email` = :email ");
    $prepare->execute(['email' => $email]);
    return $prepare->fetch(PDO::FETCH_ASSOC); 
  }





  //Возвращает id пользователя по email
  function user_id($email,$pdo){
    $prepare = $pdo->prepare("SELECT `id` FROM `users` WHERE `email` = ?");
    $prepare->execute([$email]);
    return $prepare->fetch(PDO::FETCH_ASSOC);
  }
  //Возвращает true если admin 
  function role($role){
    if ($role == 'admin') 
      return true;
    else
      return false;
  }




    //Проверяем сушествует ли такой пользыватель, если да то return email и password
  function get_user_by_email ($email, $pdo){
    $prepare = $pdo->prepare("SELECT email, pass FROM `users` WHERE `email` = :email");
    $prepare->execute(['email' => $email]);
    return $prepare->fetch(PDO::FETCH_ASSOC);
  };

  // Добавляет пользователя return id user
  function add_user($email, $pass, $pdo){
    $pass =  password_hash($pass, PASSWORD_DEFAULT);
    $prepare = $pdo->prepare("INSERT INTO `users`(`email`, `pass`) VALUES(:email, :pass)");
    $prepare->execute(['email' => $email, 'pass' => $pass]);
    return $pdo->lastInsertId();
  };
  // Общая информация
  function user_info($name, $work, $tel, $address, $new_user_id, $pdo){
    $prepare = $pdo->prepare("INSERT INTO `user_info`(`name`, `work`, `tel`, `address`, `id_user`) VALUES(?,?,?,?,?)");
    $prepare->execute([$name, $work, $tel, $address, $new_user_id]);
  };
  // MEDIA
  function user_media($status, $img, $vk, $telegram, $insta, $new_user_id, $pdo){
    $prepare = $pdo->prepare("INSERT INTO `media`(`status`, `img`, `vk`, `telegram`, `insta`, `id_user`) VALUES(?,?,?,?,?,?)");
    $prepare->execute([$status, $img, $vk, $telegram, $insta, $new_user_id]);
  };



  //Получаем всех пользователей return array
  function is_not_logged_in($pdo){
    $prepare = $pdo->prepare("SELECT * FROM `users` ");
    $prepare->execute();
    return $prepare->fetchAll(PDO::FETCH_ASSOC);
  }
  function get_user_info($id_user, $pdo){
    $prepare = $pdo->prepare("SELECT * FROM `user_info`  WHERE `id_user` = ?");
    $prepare->execute([$id_user]);
    return $prepare->fetch(PDO::FETCH_ASSOC);
  }
  function get_user_media($id_user, $pdo){
    $prepare = $pdo->prepare("SELECT * FROM `media`  WHERE `id_user` = ?");
    $prepare->execute([$id_user]);
    return $prepare->fetch(PDO::FETCH_ASSOC);
  }



    //Получаем пользоватя по id return array
  function get_user_by_id($id, $pdo){
    $prepare = $pdo->prepare("SELECT * FROM `user_info` WHERE `id_user` = ?");
    $prepare->execute([$id]);
    return $prepare->fetch(PDO::FETCH_ASSOC);
  }

  //Обновляем данные пользователя
  function update_user_info($name, $work, $tel, $address, $id_user, $pdo){
    $prepare = $pdo->prepare("UPDATE `user_info` SET `name` = :name, `work` = :work, `tel` = :tel, `address` = :address  WHERE `id_user` = :id_user");
    $prepare->execute([
      'name' => $name, 
      'work' => $work, 
      'tel' => $tel, 
      'address' => $address ,
      'id_user' => $id_user
    ]);
    //return $pdo->errorInfo();
  }