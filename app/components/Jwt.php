<?php

namespace app\components;

use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;
use Yii;
use yii\base\Component;

class Jwt extends Component
{
    public string $key;
    public string $issuer;
    public string $audience;
    public int $expire;

    public function issueToken(int $userId): string
    {
        $now = time();

        $payload = [
            'iss' => $this->issuer,
            'aud' => $this->audience,
            'iat' => $now,
            'nbf' => $now,
            'exp' => $now + $this->expire,
            'sub' => $userId,
        ];

        return FirebaseJWT::encode($payload, $this->key, 'HS256');
    }

    public function getUserIdFromToken(string $token): ?int
    {
        try {
            $decoded = FirebaseJWT::decode($token, new Key($this->key, 'HS256'));
            return isset($decoded->sub) ? (int)$decoded->sub : null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
