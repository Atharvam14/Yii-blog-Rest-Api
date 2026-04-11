<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="my-1 mx-0" style="color:#999;">
                    If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
                    <br>
                    Need new verification email? <?= Html::a('Resend', ['site/resend-verification-email']) ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'id' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$this->registerJs("
(function () {
    $('#login-form').on('submit', function (event) {
        event.preventDefault();

        $.ajax({
            url: '" . Url::to(['site/login']) . "',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            success: function (response) {
                if (response.token) {
                    sessionStorage.setItem('token', response.token);
                    sessionStorage.setItem('token_expiry', String(new Date().getTime() + (response.expires_in * 1000)));
                }

                window.location.href = response.redirect || '" . Url::to(['site/index']) . "';
            },
            error: function (xhr) {
                var response = xhr.responseJSON || {};
                var message = 'Login failed!';

                if (response.errors) {
                    message = Object.values(response.errors).join('\\n');
                }

                alert(message);
            }
        });
    });
})();
");
?>
