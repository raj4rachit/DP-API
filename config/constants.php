<?php
$application = env('FRONTEND_URL', 'http://localhost');

return [
    // Pagination number
    'per_page' => 10,
    'user_dashboard' => $application.'/dashboard',
    'email_verification_link' => $application.'/v1/auth/login',
    'verify_email_url' => $application."/v1/auth/email/verify",
    'reset_password_url' => $application."/v1/auth/reset-password"
];

