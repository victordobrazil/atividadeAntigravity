<?php
include_once __DIR__ . "/../config/database.php";
include_once "funcionario.php";

Class FuncionarioController {
    private $bd;
    private $funcionario;

    public function __construct() {
        $banco = new Database();
        $this->bd = $banco->conectar();
        $this->funcionario = new Funcionario($this->bd);
    }

    public function index() {
        return $this->funcionario->lerTodos();
    }

    public function pesquisaFuncionario($pesquisa, $tipo){
        return $this->funcionario->pesquisaFuncionario($pesquisa, $tipo);
    }

    public function cadastrarFuncionario($dados){
        $this->funcionario->nome = trim($dados["nome"]);
        $this->funcionario->cpf = trim($dados["cpf"]);
        $this->funcionario->endereco = trim($dados["endereco"]);
        $this->funcionario->telefone = trim($dados["telefone"]);
        $this->funcionario->funcao = trim($dados["funcao"]);
        $this->funcionario->login = trim($dados["login"]);
        $this->funcionario->senha = password_hash(trim($dados["senha"]), PASSWORD_DEFAULT);

        $imagemNome = null;
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            if (!is_dir('uploads')) { mkdir('uploads', 0777, true); }
            $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $imagemNome = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['imagem']['tmp_name'], 'uploads/' . $imagemNome);
        }
        $this->funcionario->imagem = $imagemNome;

        if($this->funcionario->cadastrar()){
            header("location:index_funcionario.php");
            exit();
        }
    }

    public function excluirFuncionario($id){
        $old = $this->funcionario->buscaFuncionario($id);
        if ($old && $old->imagem && file_exists('uploads/' . $old->imagem)) {
            unlink('uploads/' . $old->imagem);
        }

        $this->funcionario->id = $id;

        if($this->funcionario->excluir()){
            header("location:index_funcionario.php");
        }
    }

    public function atualizarFuncionario($dados){
        $this->funcionario->id = $dados["id"];
        $this->funcionario->nome = trim($dados["nome"]);
        $this->funcionario->cpf = trim($dados["cpf"]);
        $this->funcionario->endereco = trim($dados["endereco"]);
        $this->funcionario->telefone = trim($dados["telefone"]);
        $this->funcionario->funcao = trim($dados["funcao"]);
        $this->funcionario->login = trim($dados["login"]);

        // Se a senha estiver preenchida, altera a senha aplicando o hash
        if(!empty(trim($dados["senha"]))){
            $this->funcionario->senha = password_hash(trim($dados["senha"]), PASSWORD_DEFAULT);
        } else {
            // Senão, mantém a senha atual buscando no banco
            $user_antigo = $this->funcionario->buscaFuncionario($dados["id"]);
            $this->funcionario->senha = $user_antigo->senha;
        }

        $imagemNome = null;
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            if (!is_dir('uploads')) { mkdir('uploads', 0777, true); }
            $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $imagemNome = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['imagem']['tmp_name'], 'uploads/' . $imagemNome);
            
            $old = $this->funcionario->buscaFuncionario($dados["id"]);
            if ($old && $old->imagem && file_exists('uploads/' . $old->imagem)) {
                unlink('uploads/' . $old->imagem);
            }
            $this->funcionario->imagem = $imagemNome;
        } else {
            $old = $this->funcionario->buscaFuncionario($dados["id"]);
            $this->funcionario->imagem = $old->imagem ?? null;
        }

        if($this->funcionario->atualizar()){
            header("location:index_funcionario.php");
            exit();
        }
    }

    public function localizarFuncionario($id){
        return $this->funcionario->buscaFuncionario($id);
    }

    public function autenticar($login, $senha){
        $user = $this->funcionario->buscarPorLogin(trim($login));
        
        if($user){
            $senha_fornecida = trim($senha);
            
            // Verifica usando hash OU se for a senha antiga em texto plano (para migração)
            $is_password_valid = password_verify($senha_fornecida, $user->senha) || $senha_fornecida === $user->senha;

            if($is_password_valid){
                
                // Se o login foi por texto plano, converte para Hash automaticamente no banco de forma silenciosa
                if($senha_fornecida === $user->senha && !password_verify($senha_fornecida, $user->senha)) {
                    $this->funcionario->id = $user->id;
                    $this->funcionario->nome = $user->nome;
                    $this->funcionario->cpf = $user->cpf;
                    $this->funcionario->endereco = $user->endereco;
                    $this->funcionario->telefone = $user->telefone;
                    $this->funcionario->funcao = $user->funcao;
                    $this->funcionario->login = $user->login;
                    $this->funcionario->senha = password_hash($senha_fornecida, PASSWORD_DEFAULT);
                    $this->funcionario->atualizar();
                }

                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['logado'] = true;
                $_SESSION['usuario_id'] = $user->id;
                $_SESSION['usuario_nome'] = $user->nome;
                $_SESSION['usuario_funcao'] = strtolower(trim($user->funcao));
                
                header("Location: index_produtos.php");
                exit();
            }
        }
        return false;
    }
}
