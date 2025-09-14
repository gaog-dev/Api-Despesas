<?php
use yii\helpers\Html;

$this->title = 'Gerenciar Despesas';
?>

<h2>Adicionar Nova Despesa</h2>

<form id="despesa-form">
    <div class="form-group">
        <label>Descrição</label>
        <input type="text" name="descricao" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Valor</label>
        <input type="number" step="0.01" name="valor" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Data</label>
        <input type="date" name="data" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Categoria</label>
        <select name="categoria" class="form-control" required>
            <option value="">Selecione uma categoria</option>
            <option value="alimentação">Alimentação</option>
            <option value="transporte">Transporte</option>
            <option value="lazer">Lazer</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Salvar Despesa</button>
</form>

<hr>

<div id="mensagem"></div>

<script>
    document.getElementById('despesa-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const token = localStorage.getItem('token'); // token JWT salvo no login

        const response = await fetch('/api/despesas/create', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
            },
            body: formData
        });

        const data = await response.json();

        if (response.ok) {
            document.getElementById('mensagem').innerHTML =
                '<div class="alert alert-success">Despesa salva com sucesso!</div>';
            this.reset();
        } else {
            document.getElementById('mensagem').innerHTML =
                '<div class="alert alert-danger">Erro: ' + JSON.stringify(data.errors || data) + '</div>';
        }
    });
</script>
