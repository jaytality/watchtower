<?php

// GET: /
$base->get("{$settings['global']['basedir']}", function() use ($settings) {
  include(controllers.'/get/home.php');
});

// ERROR: 404
$base->notFound(function() {
  die('Error 404, file not found.');
});