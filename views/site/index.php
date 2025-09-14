<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'API de Despesas Pessoais';
?>
<div class="site-index">
    <div class="jumbotron">
        <div class="pull-right">
        <?= Html::button('Sair', ['class' => 'btn btn-danger', 'id' => 'logout-btn']) ?>
    </div>
        <h1>API de Despesas Pessoais</h1>
        <p class="lead">Interface simples para gerenciar despesas pessoais</p>
    </div>
    
    <div class="body-content">
        <div class="row">
            <div class="col-lg-6">
                <h2>Adicionar Nova Despesa</h2>
                
                <?php $form = ActiveForm::begin(['id' => 'despesa-form']); ?>
                
                <?= $form->field($model, 'descricao')->textInput(['autofocus' => true]) ?>
                
                <?= $form->field($model, 'valor')->textInput(['type' => 'number']) ?>
                
                <?= $form->field($model, 'data')->textInput(['type' => 'date']) ?>
                
                <?= $form->field($model, 'categoria')->dropDownList([
                    'alimentação' => 'Alimentação',
                    'transporte' => 'Transporte',
                    'lazer' => 'Lazer',
                ]) ?>
                
                <div class="form-group">
                    <?= Html::submitButton('Adicionar Despesa', ['class' => 'btn btn-primary', 'id' => 'submit-btn']) ?>
                </div>
                
                <?php ActiveForm::end(); ?>
            </div>
            
            <div class="col-lg-6">
                <h2>Lista de Despesas</h2>
                <div id="despesas-list">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Descrição</th>
                                <th>Valor</th>
                                <th>Data</th>
                                <th>Categoria</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="despesas-tbody">
                            <!-- As despesas serão carregadas aqui via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
$(document).ready(function() {
    // Carregar despesas
    function loadDespesas() {
        $.ajax({
            url: 'api/despesas',
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            success: function(data) {
                var tbody = $('#despesas-tbody');
                tbody.empty();
                
                data.items.forEach(function(despesas) {
                    tbody.append(
                        '<tr>' +
                        '<td>' + despesas.id + '</td>' +
                        '<td>' + despesas.descricao + '</td>' +
                        '<td>' + despesas.valor + '</td>' +
                        '<td>' + despesas.data + '</td>' +
                        '<td>' + despesas.categoria + '</td>' +
                        '<td>' +
                        '<button class="btn btn-sm btn-danger delete-btn" data-id="' + despesas.id + '">Excluir</button>' +
                        '</td>' +
                        '</tr>'
                    );
                });
            },
            error: function() {
                // Se houver erro, redirecionar para login
                window.location.href = '/site/login';
            }
        });
    }
    
    // Carregar despesas ao iniciar
    loadDespesas();
    
    // Enviar formulário
    $('#despesa-form').on('beforeSubmit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var formData = form.serialize();
        
        $.ajax({
            url: 'api/despesas',
            method: 'POST',
            data: formData,
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            },
            success: function(response) {
                form[0].reset();
                loadDespesas();
                alert('Despesa adicionada com sucesso!');
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    window.location.href = '/site/login';
                } else {
                    alert('Erro ao adicionar despesa.');
                }
            }
        });
        
        return false;
    });
    
    // Excluir despesa
    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        
        if (confirm('Tem certeza que deseja excluir esta despesa?')) {
            $.ajax({
                url: 'api/despesas/' + id,
                method: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                success: function() {
                    loadDespesas();
                    alert('Despesa excluída com sucesso!');
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        window.location.href = '/site/login';
                    } else {
                        alert('Erro ao excluir despesa.');
                    }
                }
            });
        }
    });
});
// Logout
$('#logout-btn').on('click', function() {
    if (confirm('Tem certeza que deseja sair?')) {
        localStorage.removeItem('token');
        window.location.href = '/site/logout';
    }
});
<script>
document.addEventListener("DOMContentLoaded", function ()
{
    const token = localStorage.getItem("token");

    if (!token) {
        window.location.href = "/site/login";
        return;
    }

    fetch("/api/despesas", {
        headers: {
            "Authorization": "Bearer " + token
        }
    })
    .then(response => {
        if (response.status === 401) {
            localStorage.removeItem("token");
            window.location.href = "/site/login";
        }
        return response.json();
    })
    .then(data => {
        console.log("Despesas:", data);
        // TODO: renderizar lista no HTML
    })
    .catch(err => console.error("Erro ao carregar despesas", err));
})
</script>
JS
);
?>