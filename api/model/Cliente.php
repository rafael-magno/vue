<?php

class Cliente extends Model implements JsonSerializable
{
    private $idcliente;
    private $nomeCliente;
    
    private $total;

    public function __construct($inicializarClassePai = true)
    {
        parent::__construct($inicializarClassePai, $this);
    }

    public function listarTodos($pagina, $totalPorPagina, $termoPesquisa)
    {
        $sql = 'SELECT {campos} FROM cliente';
        if (trim($termoPesquisa)) {
            $termoPesquisa = $this->db->real_escape_string($termoPesquisa);
            $sql .= " WHERE nome_cliente LIKE '%$termoPesquisa%'";
        }
        $sqlTotal = str_replace('{campos}', 'COUNT(idcliente) AS total', $sql);
        $sqlDados = str_replace('{campos}', 'idcliente, nome_cliente AS nomeCliente', $sql);
        $sqlDados .= ' LIMIT '.(($pagina - 1) * $totalPorPagina).', '.$totalPorPagina;
        return [
            'total' => $this->fetchRow($sqlTotal)->getTotal(), 
            'dados' => $this->fetchRows($sqlDados)
        ];
    }

    public function buscarDadosId($id)
    {
        $sql = 'SELECT idcliente, nome_cliente AS nomeCliente FROM cliente WHERE idcliente = '.(int)$id;
        return $this->fetchRow($sql);
    }

    public function salvarDados($dados)
    {
        $this->setIdcliente($dados['idcliente']);
        $this->setNomeCliente($dados['nomeCliente']);
        return $this->salvar();
    }

    public function removerDados($id)
    {
        $this->setIdcliente($id);
        return $this->remover();
    }
    
    public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }

    /**
     * @return mixed
     */
    public function getIdcliente()
    {
        return $this->idcliente;
    }

    /**
     * @param mixed $idcliente
     *
     * @return self
     */
    public function setIdcliente($idcliente)
    {
        $this->idcliente = $idcliente;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNomeCliente()
    {
        return $this->nomeCliente;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $nomeCliente
     *
     * @return self
     */
    public function setNomeCliente($nomeCliente)
    {
        $this->nomeCliente = $nomeCliente;

        return $this;
    }
}