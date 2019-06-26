<?php

require_once 'model/Cliente.php';

class ClienteController extends Controller
{
    private $cliente;

    public function __construct()
    {
        $this->cliente = new Cliente();
    }

    public function buscarListagem()
    {
        $listagemCliente = $this->cliente->listarTodos($_GET['pagina'], $_GET['totalPorPagina'], $_GET['termoPesquisa']);
        
        $this->json($listagemCliente);
    }

    public function buscarDadosEdicao()
    {
        $dadosCliente = $this->cliente->buscarDadosId($_GET['idcliente']);

        $this->json($dadosCliente);
    }

    public function salvarDados()
    {
        $retorno['status'] = $this->cliente->salvarDados($_POST);

        $this->json($retorno);
    }

    public function removerDados()
    {
        $retorno['status'] = $this->cliente->removerDados($_POST['idcliente']);
        
        $this->json($retorno);
    }
}