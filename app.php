<?php

    class Dashboard
    {
        public $data_inicio;
        public $data_fim;
        public $numeroVendas;
        public $totalVendas;

        public function __get($atributo)
        {
            return $this->atributo;
        }

        public function __set($atributo, $valor)
        {
            $this->$atributo = $valor;
            return $this;
        }
    }


    class Connect
    {
        private $host = 'localhost';
        private $dbname = 'dashboard';
        private $user = 'root';
        private $pass = '';

        public function connect()
        {
            try {
                 
                $connection = new PDO(
                    "mysql:host=$this->host;dbname=$this->dbname",
                    "$this->user",
                    "$this->pass"
                );

                $connection->exec('set charset set utf8');

                return $connection;

            } catch (PDOException $e) {
                 echo '<p>'.$e->getMessage().'</p>';
            }
        }
    }

    class Db
    {
        private $connection;
        private $dashboard;

        public function __construct(Connect $connection, Dashboard $dashboard)
        {
            $this->connection = $connection->connect();
            $this->dashboard = $dashboard;
        }

        public function getSalesAmount()
        {
            $query = 'SELECT 
                        COUNT(*) AS sales_amount 
                      FROM 
                        `tb_vendas` 
                      WHERE data_venda BETWEEN :data_inicio AND :data_fim';

            $stmt = $this->connection->prepare($query);
            $stmt->bindValue(':data_inicio', '2018-08-01');
            $stmt->bindValue(':data_fim', '2018-08-31');
            $stmt->execute();

            $salesAmount = $stmt->fetch(PDO::FETCH_OBJ)->sales_amount;
            echo "Volume de vendas: $salesAmount unidades<br>"; 
        }
    }

    $dashboard = new Dashboard();
    $connection = new Connect();

    $db = new Db($connection, $dashboard);

    $dashboard->__set('salesAmount', $db->getSalesAmount());
    print_r($dashboard);
    
?>