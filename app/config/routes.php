<?php
$routes = [
  // Contact routes
  'contact/getAll/(\d+)' => ['ContactController', 'getAll'],
  'contact/add' => ['ContactController', 'add'],
  'contact/update/(\d+)' => ['ContactController', 'update'],
  'contact/delete/(\d+)' => ['ContactController', 'delete'],

  // Default route
  '' => ['HomeController', 'index']
];

return $routes;
