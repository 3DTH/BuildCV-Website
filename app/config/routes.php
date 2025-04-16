<?php
$routes = [

    // CV routes
    'cv' => ['CVController', 'index'],
    'cv/create' => ['CVController', 'create'],
    'cv/edit/(\d+)' => ['CVController', 'edit'],
    'cv/delete/(\d+)' => ['CVController', 'delete'],
    'cv/updateBasicInfo/(\d+)' => ['CVController', 'updateBasicInfo'],
    'cv/updatePersonalInfo' => ['CVController', 'updatePersonalInfo'],
    'cv/renderPreview/([a-zA-Z0-9-]+)' => ['CVController', 'renderPreview'],
    'cv/generatePDF' => ['CVController', 'generatePDF'],

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

    // Admin routes
    'admin' => ['AdminController', 'index'],
    'admin/users' => ['Admin\\UserController', 'index'],
    'admin/users/edit/(\d+)' => ['Admin\\UserController', 'edit'],
    'admin/users/delete/(\d+)' => ['Admin\\UserController', 'delete'],
    'admin/users/toggle-status/(\d+)' => ['Admin\\UserController', 'toggleStatus'],

    // Default route
    '' => ['HomeController', 'index'],
];

return $routes;
