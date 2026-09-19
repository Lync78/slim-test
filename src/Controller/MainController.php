<?php


namespace App\Controller;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Enum\Role;
use App\Service\Jwt;
use Dotenv\Dotenv;

class MainController {
    
    private UserRepository $userRepo;

    public function __construct(){
        $this->userRepo = new UserRepository();
    }

    public function main($request, $response){

        $data = ["message" => "Hello world",];

        $response->getBody()->write(json_encode($data));

        return $response->withHeader('Content-Type','application/json');
    }

    public function register($request, $response){
        
        //reception des données
        $data = $request->getParsedBody();

        //création d'un utilisateur
        $user = new User();
        
        //assignation des données
        $username = $data["username"];
        $email = $data["email"];
        $password = $data["password"];
        $errorUsername = false;
        $errorPassword = false;
        $errorEmail = false;
        //validation des données.
        if(preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{1,32}$/", $username)){
            $user->setUsername($username);
        } else {
            $errorUsername = true;
        }

        if($this->checkEmail($email)){
            $user->setEmail($email);
        } else {
            $errorEmail = true;
        }

        if(preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,32}$/", $password)){
            $user->setPassword($password);
        } else {
            $errorPassword = true;
        }

        //si l'un des trois est faux alors on renvoie du json avec un message et un status en 400
        if($errorEmail AND $errorUsername AND $errorPassword){
            $response->getBody()->write(json_encode(["message" => "les données ne sont pas valident"]));
            return $response->WithStatus(400)->WithHeader('Content-Type','application/json');
        }
        
        //si l'insertion échoue
        if(!$this->userRepo->insert($user)){
            $response->getBody()->write(json_encode(["message" => "les données n'ont pas pu être inséré"]));
            return $response->WithStatus(400)->WithHeader('Content-Type','application/json');
        }

        //si tout es ok
        $response->getBody()->write(json_encode(["message" => "ok", "status" => 201]));
        return $response->WithStatus(201)->WithHeader('Content-Type','application/json');

    }

    public function login($request, $response){

        $data = $request->getParsedBody();

        $email = $data["email"];
        $password = $data["password"];

        if(!$this->checkEmail($email)){
            $response->getBody()->write(json_encode(["message" => "Le mot de passe ou l'email est incorrect"]));
            return $response->WithStatus(400)->WithHeader('Content-Type','application/json');
        }

        $user = $this->userRepo->getUser($email);

        if(is_null($user)){
            $response->getBody()->write(json_encode(["message" => "Le mot de passe ou l'email est incorrect"]));
            return $response->WithStatus(400)->WithHeader('Content-Type','application/json');
        }

        if(!password_verify($password,$user->getPassword())){
            $response->getBody()->write(json_encode(["message" => "Le mot de passe ou l'email est incorrect"]));
            return $reponse->WithStatus(400)->WithHeader('content-Type','application/json');
        }

        $jwt = new Jwt();
        
        setcookie('token',$token->create(
            [
                "id"=>$user->getId(),
                "email" => $user->getEmail(),
                "role" => $user->getRole(),
            ]),
            [
                "expires" => time() + 3600,
                "path" => "/",
                "secure" => true,
                "httponly" => $_ENV["ENVIRONNEMENT"],
                'samesite' => 'Lax',
            ]);
        
        $response->getBody()->write(json_encode(["message" => "connexion réussi"]));
        return $response->WithStatus(200)->WithHeader('Content-Type',"application/json");

    }

    private function checkEmail(string $email): string {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}