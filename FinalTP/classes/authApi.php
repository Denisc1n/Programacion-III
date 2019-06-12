<?php
    use \Firebase\JWT\JWT;
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;   
    class authApi 
    {
        public function login ($req, $res){
            $request = $req->getParsedBody();
           
            $usuario = $request['user'];
            $password = $request['password'];
            $currentTime = time();
            if( strtolower($usuario) == 'user' && strtolower($password) == 'password'){
                $payload = array(
                   'iat' => $currentTime,
                  
                   'data' => 'loginSuccessful'
                );
                $token = JWT::encode($payload,'serverkey');

                return "USUARIO LOGUEADO"." "."TOKEN:"." ".$res->withJson($token,200);
            }
        }

        public static function validate(Request $req, Response $res){
            $packageReceived = $req->getHeader('token');
    
            if(empty($packageReceived[0]) || $packageReceived[0] === ""){
                throw new Exception("TOKEN VACIO");
            }

            try {
                $decode = JWT::decode($packageReceived[0],'serverkey',['HS256']);
                //$decode = JWT::decode('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJkYXRhIjoibG9naW5TdWNjZXNzZnVsbCJ9.UYj-KZJo5yTtdTd1motffP3yTVXzwdSHhGRZlkKuS5w','serverkey',['HS256']);
                return "token ok";
                

            } catch ( Exception $th) {
                //throw new Exception("TOKEN INVALIDO"." ".$th->getMessage());
                return "invalido";
            }

        }
    }

?>