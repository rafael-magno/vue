<?php

class Controller
{
    public function json($dados)
    {
        echo json_encode($dados);
    }
}