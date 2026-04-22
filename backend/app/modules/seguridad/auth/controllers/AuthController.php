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

    public function refresh(): void
    {
        try {
            $input = $_POST;

            if (empty($input)) {
                $json = file_get_contents('php://input');
                $input = json_decode($json, true) ?? [];
            }

            $refreshToken = $input['refreshToken'] ?? null;

            if (!$refreshToken) {
                throw new Exception('Refresh token requerido');
            }

            $result = $this->service->refresh($refreshToken);

            $this->success($result, 'Token renovado');

        } catch (Exception $e) {
            $this->error($e->getMessage(), 400);
        }
    }
}