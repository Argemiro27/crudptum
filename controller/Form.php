<?php
class Form
{
  public function __construct()
  {
    Transaction::open();
  }
  public function controller()
  {
    $form = new Template("view/form.html");
    $form->set("id","");
    $form->set("nome","");
    $form->set("local","");
    $form->set("quantidade","");
    $retorno["msg"] = $form->saida();
    return $retorno;
  }

  public function salvar()
  {
    if (isset($_POST["nome"]) && isset($_POST["local"]) && isset($_POST["quantidade"])) {
      try {
        $conexao = Transaction::get();
        $nome = $conexao->quote($_POST["nome"]);
        $local = $conexao->quote($_POST["local"]);
        $quantidade = $conexao->quote($_POST["quantidade"]);
        $crud = new Crud();
        if (empty($_POST["id"])) {
          $retorno = $crud->insert(
            "eventos",
            "nome,local,quantidade",
            "{$nome},{$local},{$quantidade}"
          );
        } else {
          $id = $conexao->quote($_POST["id"]);
          $retorno = $crud->update(
            "eventos",
            "nome={$nome}, local={$local}, quantidade={$quantidade}",
            "id={$id}"
          );
        }
      } catch (Exception $e) {
        $retorno["msg"] = "Ocorreu um erro! " . $e->getMessage();
        $retorno["erro"] = TRUE;
      }
    } else {
      $retorno["msg"] = "Preencha todos os campos! ";
      $retorno["erro"] = TRUE;
    }
    return $retorno;
  }

  public function __destruct()
  {
    Transaction::close();
  }
}
