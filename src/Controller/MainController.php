<?php


namespace App\Controller;
use App\Entity\User;
use App\Repository\UserRepository;

class MainController {
    
    public function __construct(private UserRepository $userRepo){

    }

    public function main($request, $response){

        $data = [
            "message" => "Hello world",
        ];

        $response->getBody()->write(json_encode($data));

        return $response->withHeader('Content-Type','application/json');
    }

    public function register($request, $response){
        
        $data = $request->getParsedBody();

        $user = new User();
        
        $username = $data["username"];
        $email = $data["email"];
        $password = $data["password"];
        $errorUsername = false;
        $errorPassword = false;
        $errorEmail = false;

        if(preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{1,32}$/", $username)){
            $user->setUsername($username);
        } else {
            $errorUsername = true;
        }

        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $user->setEmail($email);
        } else {
            $errorEmail = true;
        }

        if(preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,32}$/", $password)){
            $user->setPassword($password);
        } else {
            $errorPassword = true;
        }

        if($errorEmail AND $errorUsername AND $errorPassword){
            $response->getBody()->write(json_encode(["message" => "les données ne sont pas valident", "status" => 400]));
            return $response->WithStatus(400)->WithHeader('Content-Type','application/json');
        }

        if(!$this->userRepo->insert($user)){
            $response->getBody()->write(json_encode(["message" => "les données n'ont pas pu être inséré", "status" => 400]));
            return $response->WithStatus(400)->WithHeader('Content-Type','application/json');
        }

        $response->getBody()->write(json_encode(["message" => "ok", "status" => 201]));
        return $response->WithStatus(201)->WithHeader('Content-Type','application/json');

    }
}