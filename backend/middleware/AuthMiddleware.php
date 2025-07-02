<?php
class AuthMiddleware {
    public static function authenticate() {
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? null;
        
        // Mock check (real exam: decode JWT)
        if (!$token || !str_starts_with($token, "mock_jwt_")) {
            Flight::halt(403, 'Unauthorized');
        }
    }
}

// Usage in ExamRoutes.php so delete from here:
Flight::route('POST /customers/add', function() {
    AuthMiddleware::authenticate(); // Protect this route
    // ... existing customer-add logic ...
});
?>