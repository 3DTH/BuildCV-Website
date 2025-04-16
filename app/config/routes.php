<?php
$routes = [

   // Languages routes
   'language/getAll/(\d+)' => ['LanguageController', 'getAll'],
   'language/add' => ['LanguageController', 'add'],
   'language/update/(\d+)' => ['LanguageController', 'update'],
   'language/delete/(\d+)' => ['LanguageController', 'delete'],

   // Certificates routes
   'certificate/getAll/(\d+)' => ['CertificateController', 'getAll'],
   'certificate/add' => ['CertificateController', 'add'],
   'certificate/update/(\d+)' => ['CertificateController', 'update'],
   'certificate/delete/(\d+)' => ['CertificateController', 'delete'],

   // Projects routes
   'project/getAll/(\d+)' => ['ProjectController', 'getAll'],
   'project/add' => ['ProjectController', 'add'],
   'project/update/(\d+)' => ['ProjectController', 'update'],
   'project/delete/(\d+)' => ['ProjectController', 'delete'],

  // Contact routes
  'contact/getAll/(\d+)' => ['ContactController', 'getAll'],
  'contact/add' => ['ContactController', 'add'],
  'contact/update/(\d+)' => ['ContactController', 'update'],
  'contact/delete/(\d+)' => ['ContactController', 'delete'],

  // Default route
  '' => ['HomeController', 'index'] 
];

return $routes;
