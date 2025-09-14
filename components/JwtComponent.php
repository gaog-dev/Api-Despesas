<?php
namespace app\components;

use Yii;
use yii\base\Component;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtComponent extends Component
{
    public $key = 'your-secret-key'; // Mude para uma chave segura
    public string $alg = 'HS256'; // algoritmo padrão
    public function init()
    {
        parent::init();
        if (empty($this->key)) {
            throw new InvalidConfigException("JWT key must be set in the application config.");
        }
    }

    /**
     * Gera um token JWT
     */
    public function encode(array $payload, int $expireInSeconds = 3600): string
    {
        $issuedAt = time();
        $payload['iat'] = $issuedAt;
        $payload['exp'] = $issuedAt + $expireInSeconds;

        return JWT::encode($payload, $this->key, $this->alg);
    }

    /**
     * Decodifica um token JWT
     */
    public function decode(string $token): object
    {
        return JWT::decode($token, new Key($this->key, $this->alg));
    }

    public function generateToken($userId)
    {
        $payload = [
            'iss' => 'http://localhost:8080/site/login', // Emissor
            'aud' => 'http://localhost:8080/', // Audiência
            'iat' => time(), // Timestamp de emissão
            'exp' => time() + 3600, // Expiração (1 hora)
            'userId' => $userId
        ];
        
        return JWT::encode($payload, $this->key, 'HS256');
    }
    
    public function validateToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
            return $decoded;
        } catch (\Exception $e) {
            return false;
        }
    }
}