<?php

  require __DIR__ . '/../vendor/autoload.php';
  require __DIR__ . '/RegisterForm.php';

  $form = new RegisterForm();

  if(! $form->validate())
  {
    echo $form->render();
  }

  else
  {
    $form->runFollowUps();
  }
