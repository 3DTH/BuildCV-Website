<?php
$routes = [

    // Experience routes
    'experience/getAll/(\d+)' => ['ExperienceController', 'getAll'],
    'experience/add' => ['ExperienceController', 'add'],
    'experience/update/(\d+)' => ['ExperienceController', 'update'],
    'experience/delete/(\d+)' => ['ExperienceController', 'delete'],

    // Education routes
    'education/getAll/(\d+)' => ['EducationController', 'getAll'],
    'education/add' => ['EducationController', 'add'],
    'education/update/(\d+)' => ['EducationController', 'update'],
    'education/delete/(\d+)' => ['EducationController', 'delete'],

    // Skills routes
    'skill/getAll/(\d+)' => ['SkillController', 'getAll'],
    'skill/add' => ['SkillController', 'add'],
    'skill/update/(\d+)' => ['SkillController', 'update'],
    'skill/delete/(\d+)' => ['SkillController', 'delete'],

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
