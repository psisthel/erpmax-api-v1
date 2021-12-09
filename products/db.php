<?php

class DB{
	
    private $host;
    private $db;
    private $user;
    private $password;
    private $charset;

    public function __construct(){
        $this->host     = '107.180.46.150';
        $this->db       = 'erpmax_demo_pe';
        $this->user     = 'demo_pe';
        $this->password = "d5xkWMc@WGly";
        $this->charset  = 'utf8mb4';
    }

    //mysql -e "USE todolistdb; select*from todolist" --user=azure --password=6#vWHD_$ --port=49175 --bind-address=52.176.6.0

    function connect(){
    
        try{

            
            $connection = "mysql:host=".$this->host.";dbname=" . $this->db . ";charset=" . $this->charset;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            //$pdo = new PDO($connection, $this->user, $this->password, $options);
            $pdo = new PDO($connection,$this->user,$this->password);
        
            return $pdo;


        }catch(PDOException $e){
            print_r('Error connection: ' . $e->getMessage());
        }   
    }
}






?>