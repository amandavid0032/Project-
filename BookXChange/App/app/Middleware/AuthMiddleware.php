<?php
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\GetTokenFromDb\GetToken;
use App\Config\Db;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class AuthMiddleware
{
    protected $getToken;
    protected $con;
    protected $conn;

    public function __construct()
    {
        $this->con = new Db();
        $this->conn = $this->con->getConnection();
        $this->getToken = new GetToken($this->conn);
    }
    public function __invoke(Request $request, Response $response, $next)
    {
        $key = 'oxole@ideafoundation.in';
        $token = substr($request->getHeaderLine('Authorization'), 14);
        try {
            $decoded_jwt_tok_val = (array) JWT::decode($token, new Key($key, 'HS256'));
        } catch (\Exception $e) { 
            $jsonMessage = array(
                "isSuccess" => false,
                "message" => "invalid token",
            );
            $response->getBody()->write(json_encode($jsonMessage));
            return $response
            ->withHeader("content-type", "application/json")
            ->withStatus(200);
        }

        $jsonMessage = array(
            "isSuccess" => false,
            "message" => "invalid token",
        );
        if (isset($decoded_jwt_tok_val) && $decoded_jwt_tok_val != '') {
            $tokenFromDb = $this->getToken->getTokenFromDb($decoded_jwt_tok_val['userId']);
            if ($tokenFromDb == '') {
                $jsonMessage = array(
                    "isSuccess" => false,
                    "message" => "user not loggedIn",
                );
            } elseif ($tokenFromDb == $token) {
                $response = $next($request, $response);
                return $response;
            } else {
                $jsonMessage = array(
                    "isSuccess" => false,
                    "message" => "token mismatched",
                );
            }
        }

        $response->getBody()->write(json_encode($jsonMessage));
        return $response
        ->withHeader("content-type", "application/json")
        ->withStatus(200);
        
    }
}