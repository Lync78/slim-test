<?php


class Jwt 
{ 

    private string $secret;

    public function __construct(  ) {
        $this->secret = $_ENV["JWT"];
    }

    public function create(array $payload): string { 
        $header = [ 'alg' => 'HS256', 'typ' => 'JWT', ];

        $payload['iat'] = time(); $payload['exp'] = time() + 3600; 

        $headerEncoded = $this->base64UrlEncode( json_encode($header) ); 

        $payloadEncoded = $this->base64UrlEncode( json_encode($payload) ); 

        $data = $headerEncoded . '.' . $payloadEncoded; 

        $signature = hash_hmac( 'sha256', $data, $this->secret, true ); 

        $signatureEncoded = $this->base64UrlEncode($signature); 
        
        return $data . '.' . $signatureEncoded; 
    }

    private function base64UrlEncode(string $data): string { 
        return rtrim( strtr(base64_encode($data), '+/', '-_'), '=' ); 
    } 
}