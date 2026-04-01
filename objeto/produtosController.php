<?php
include_once "config/database.php";
include_once "produtos.php";
Class produtosController {
    private $bd;
    private $produto;

    public function __construct() {
        $banco = new Database();
        $this->bd = $banco->conectar();
        $this->produto = new Produtos($this->bd);
    }

    public function index() {
        return $this->produto->lerTodos();
    }

    public function pesquisaProduto($pesquisa, $tipo){
        return $this->produto->pesquisaProduto($pesquisa, $tipo);
    }


    public function cadastrarProduto($dados){
        $this->produto->nome = $dados["nome"];
        $this->produto->descricao = $dados["descricao"];
        $this->produto->quantidade = $dados["quantidade"];
        $this->produto->preco = $dados["preco"];

        $imagemNome = null;
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            if (!is_dir('uploads')) { mkdir('uploads', 0777, true); }
            $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $imagemNome = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['imagem']['tmp_name'], 'uploads/' . $imagemNome);
        }
        $this->produto->imagem = $imagemNome;

        if($this->produto->cadastrar()){
            header("location:index_produtos.php");
            exit();
        }
    }

    public function excluirProduto($id){
        $old = $this->produto->buscaProduto($id);
        if ($old && $old->imagem && file_exists('uploads/' . $old->imagem)) {
            unlink('uploads/' . $old->imagem);
        }

        $this->produto->id = $id;

        if($this->produto->excluir()){
            header("location:index_produtos.php");
        }
    }

    public function atualizarProduto($dados){
        $this->produto->id = $dados["id"];
        $this->produto->nome = $dados["nome"];
        $this->produto->descricao = $dados["descricao"];
        $this->produto->quantidade = $dados["quantidade"];
        $this->produto->preco = $dados["preco"];

        $imagemNome = null;
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            if (!is_dir('uploads')) { mkdir('uploads', 0777, true); }
            $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $imagemNome = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['imagem']['tmp_name'], 'uploads/' . $imagemNome);
            
            $old = $this->produto->buscaProduto($dados["id"]);
            if ($old && $old->imagem && file_exists('uploads/' . $old->imagem)) {
                unlink('uploads/' . $old->imagem);
            }
            $this->produto->imagem = $imagemNome;
        } else {
            $old = $this->produto->buscaProduto($dados["id"]);
            $this->produto->imagem = $old->imagem ?? null;
        }

        if($this->produto->atualizar()){
            header("location:index_produtos.php");
            exit();
        }
    }

    public function localizarProduto($id){
        return $this->produto->buscaProduto($id);
    }


}