<?php
namespace app\components;

use Yii;
use yii\base\Component;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtComponent extends Component
{
    public $key = 'your-secret-key'; // Mude para uma chave segura
    
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