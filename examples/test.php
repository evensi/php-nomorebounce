<?php

use silvergit\NoMoreBounce\NoMoreBounce;

// Set credential path
$credential_path = __DIR__ . '/credentials.json';

// Instance NoMoreBounce library
$NoMoreBounce = new NoMoreBounce($credential_path);
$email = 'testsomeemail@gmail.com';

// Launch some request
$response = $NoMoreBounce->checkEmail($email, FALSE);
