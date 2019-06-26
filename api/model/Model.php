<?php

class Model
{
    private $objModel;
    private $nomeModel;
    private $tabela;
    private $colunas = [];
    private $colunasPrimaryKey = [];
    protected $db;

    public function __construct($inicializarClassePai, $objModel)
    {
        if ($inicializarClassePai)
        {
            $this->objModel  = $objModel;
            $this->nomeModel = get_class($objModel);
            $this->tabela    = toUnderscore($this->nomeModel);
            $this->conexaoBanco();
            $this->defineColunas();
        }
    }

    private function conexaoBanco()
    {
        $this->db = new mysqli(SERVIDOR_DB, USUARIO_DB, SENHA_DB, BANCO_DB);

        if ($this->db->connect_errno)
        {
            die("Falha na conexao db: ".$this->db->connect_error);
        }
    }

    private function defineColunas()
    {
        $sql = "SHOW COLUMNS FROM ".$this->tabela;
        $result = $this->db->query($sql);

        while ($row = $result->fetch_assoc())
        {
            array_push($this->colunas, $row['Field']);
        }
    }

    protected function fetchRow($sql)
    {
        return current($this->fetchRows($sql));
    }
    
    protected function fetchRows($sql)
    {
        $linhas = [];

        $result = $this->db->query($sql);

        if ($result)
        {
            //while ($objeto = $result->fetch_assoc())
            while ($objeto = $result->fetch_object($this->nomeModel, [false]))
            {
                $linhas[] = $objeto;
            }
        }

        return $linhas;
    }

    protected function salvar()
    {
        $colunasValores = $this->sqlColunasValores(true);
        $dadosSalvar    = implode(',', $colunasValores);

        $sql = "INSERT INTO {$this->tabela} SET $dadosSalvar ON DUPLICATE KEY UPDATE $dadosSalvar";
        $result = $this->db->query($sql);

        if ($result)
        {
            $ultimoId = $this->db->insert_id;

            if ($ultimoId)
            {
                return $ultimoId;
            }
        }

        return $result;
    }

    protected function remover()
    {
        $condicoes = $this->sqlColunasValores();

        $sql = "DELETE FROM {$this->tabela} WHERE ".implode(' AND ', $condicoes);
        $result = $this->db->query($sql);

        return $result;
    }

    private function sqlColunasValores($setarNull = false)
    {
        $colunasValores = [];

        foreach ($this->colunas as $coluna)
        {
            $metodoGetColuna = "get".ucfirst(toCamelCase($coluna));

            if (method_exists($this->objModel, $metodoGetColuna) && $this->objModel->$metodoGetColuna() !== '' && $this->objModel->$metodoGetColuna() !== NULL)
            {
                $colunasValores[] = $coluna." = '".$this->db->real_escape_string($this->objModel->$metodoGetColuna())."'";
            }
            else if ($setarNull)
            {
                $colunasValores[] = $coluna." = NULL";
            }
        }

        return $colunasValores;
    }
}