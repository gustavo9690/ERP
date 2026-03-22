<?php

class AuthController extends Controller
{
    private AuthService $service;

    public function __construct()
    {
        $this->service = new AuthService();
    }

    public function login(): void
    {
        try {
            $dto = LoginRequestDTO::fromRequest();
            $result = $this->service->login($dto);

            $this->success($result->toArray(), 'Login correcto');

        } catch (Exception $e) {
            $this->error($e->getMessage(), 400);
        }
    }

    public function me(): void
    {
        try {
            AuthMiddleware::handle();

            $user = AuthMiddleware::user();
            $result = $this->service->me($user);

            $this->success($result, 'OK');

        } catch (Exception $e) {
            $this->error($e->getMessage(), 400);
        }
    }
}