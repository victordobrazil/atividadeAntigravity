<?php

Class Funcionario {
    public $id;
    public $nome;
    public $cpf;
    public $endereco;
    public $telefone;
    public $funcao;
    public $login;
    public $senha;
    public $imagem;
    public $bd;

    public function __construct($bd){
        $this->bd = $bd;
    }

    public function lerTodos(){
        $sql = "SELECT * FROM funcionarios";
        $resultado = $this->bd->query($sql);
        $resultado->execute();
        return $resultado->fetchAll(PDO::FETCH_OBJ);
    }

    public function pesquisaFuncionario($pesquisa, $tipo){
        if($tipo == "id"){
            $sql = "SELECT * FROM funcionarios WHERE id = :pesquisa";
        } else {
            $sql = "SELECT * FROM funcionarios WHERE nome LIKE :pesquisa";
            $pesquisa = "%".$pesquisa."%";
        }
        $resultado = $this->bd->prepare($sql);
        $resultado->bindParam(':pesquisa', $pesquisa);
        $resultado->execute();
        return $resultado->fetchAll(PDO::FETCH_OBJ);
    }

    public function cadastrar(){
        $sql = "INSERT INTO funcionarios (nome, cpf, endereco, telefone, funcao, login, senha, imagem) VALUES(:nome, :cpf, :endereco, :telefone, :funcao, :login, :senha, :imagem)";

        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(':nome', $this->nome, PDO::PARAM_STR);
        $stmt->bindParam(':cpf', $this->cpf, PDO::PARAM_STR);
        $stmt->bindParam(':endereco', $this->endereco, PDO::PARAM_STR);
        $stmt->bindParam(':telefone', $this->telefone, PDO::PARAM_STR);
        $stmt->bindParam(':funcao', $this->funcao, PDO::PARAM_STR);
        $stmt->bindParam(':login', $this->login, PDO::PARAM_STR);
        $stmt->bindParam(':senha', $this->senha, PDO::PARAM_STR);
        $stmt->bindParam(':imagem', $this->imagem, PDO::PARAM_STR);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    public function excluir(){
        $sql = "DELETE FROM funcionarios WHERE id = :id";
        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    public function atualizar(){
        $sql = "UPDATE funcionarios SET nome = :nome, cpf = :cpf, endereco = :endereco, 
                  telefone = :telefone, funcao = :funcao, login = :login, senha = :senha, imagem = :imagem WHERE id = :id";

        $stmt = $this->bd->prepare($sql);
        $stmt->bindParam(':nome', $this->nome, PDO::PARAM_STR);
        $stmt->bindParam(':cpf', $this->cpf, PDO::PARAM_STR);
        $stmt->bindParam(':endereco', $this->endereco, PDO::PARAM_STR);
        $stmt->bindParam(':telefone', $this->telefone, PDO::PARAM_STR);
        $stmt->bindParam(':funcao', $this->funcao, PDO::PARAM_STR);
        $stmt->bindParam(':login', $this->login, PDO::PARAM_STR);
        $stmt->bindParam(':senha', $this->senha, PDO::PARAM_STR);
        $stmt->bindParam(':imagem', $this->imagem, PDO::PARAM_STR);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    public function buscaFuncionario($id){
        $sql = "SELECT * FROM funcionarios WHERE id = :id";
        $resultado = $this->bd->prepare($sql);
        $resultado->bindParam(':id', $id);
        $resultado->execute();
        return $resultado->fetch(PDO::FETCH_OBJ);
    }

    public function buscarPorLogin($login){
        $sql = "SELECT * FROM funcionarios WHERE login = :login";
        $resultado = $this->bd->prepare($sql);
        $resultado->bindParam(':login', $login);
        $resultado->execute();
        return $resultado->fetch(PDO::FETCH_OBJ);
    }
}
