<?php
    use \Firebase\JWT\JWT;
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;   

    class Middleware
    {
        public static function validarToken(Request $req, Response $res){
            $packageReceived = $req->getHeader('token');
    
            if(empty($packageReceived[0]) || $packageReceived[0] === ""){
                throw new Exception("TOKEN VACIO");
            }
            
            try {
                $decode = JWT::decode($packageReceived[0],'serverkey',['HS256']);
                //$decode = JWT::decode('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJkYXRhIjoibG9naW5TdWNjZXNzZnVsbCJ9.UYj-KZJo5yTtdTd1motffP3yTVXzwdSHhGRZlkKuS5w','serverkey',['HS256']);
        
                return $decode;
                
    
            } catch ( Exception $th) {
                //throw new Exception("TOKEN INVALIDO"." ".$th->getMessage());
                return "invalido";
            }
    
        }
    }

?>