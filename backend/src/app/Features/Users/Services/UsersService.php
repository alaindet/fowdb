<?php

namespace App\Features\Users\Services;

use Firebase\JWT\JWT;

use App\Core\Exceptions\HttpExceptionFactory;
use App\Features\Users\Dtos\CreateUserDto;
use App\Features\Users\Dtos\AuthenticateUserDto;
use App\Features\Users\Dtos\AuthenticatedUserDto;
use App\Features\Users\Repositories\UsersRepository;
use App\Features\Users\Exceptions\UserNotFoundException;

class UsersService
{
    static public function createUser(CreateUserDto $dto): void
    {
        $repo = new UsersRepository();

        try {   
            $repo->findUserByEmail($dto->email);
            $message = "User with email {$dto->email} already exists";
            throw HttpExceptionFactory::conflict($message);
        }
        
        catch (UserNotFoundException $e) {
            $repo->createUser($dto);
        }
    }

    static public function authenticateUser(
        AuthenticateUserDto $dtoIn
    ): AuthenticatedUserDto
    {
        $repo = new UsersRepository();

        $user = $repo->findUserByEmail($dtoIn->email);

        if (
            $user === null ||
            !password_verify($dtoIn->password, $user->password)
        ) {
            $message = 'Wrong email and/or password';
            throw HttpExceptionFactory::unauthorized($message);
        }

        $issuerClaim = $_ENV['APP_URL'];
        $subjectClaim = $user->id; // TODO: Could be auth session ID
        $issuedAtClaim = time();
        $expiresInClaim = $issuedAtClaim + 3600; // An hour
        $notBeforeClaim = $issuedAtClaim;

        $claims = [
            'iss' => $issuerClaim,
            'sub' => $subjectClaim,
            'exp' => $expiresInClaim,
            'nbf' => $notBeforeClaim,
            'iat' => $issuedAtClaim,
        ];

        $token = JWT::encode($claims, $_ENV['APP_JWT_SECRET']);

        $dtoOut = new AuthenticatedUserDto();
        $dtoOut->email = $dtoIn->email;
        $dtoOut->token = $token;
        $dtoOut->expireAt = $expiresInClaim;

        return $dtoOut;
    }

    static public function checkUserToken(?string $authHeader): array
    {
        try {
            [$type, $token] = explode(' ', $authHeader);
            $secret = $_ENV['APP_JWT_SECRET'];
            $algorithms = ['HS256'];
            return (array) JWT::decode($token, $secret, $algorithms);
        } catch (\Exception $e) {
            throw new \Exception('Invalid or missing authorization token');
        }
    }
}
