<?= $render('header', ['loggedUser' => $loggedUser]); ?>

<section class="container main">
    <?= $render('sidebar', ['activeMenu' => 'config']); ?>
    <section class="feed mt-10">
        <div class="row">
            <div class="column pr-5">
                <h2 class="mb-5">Configurações</h2>
                <?php if (!empty($flash)) : ?>
                    <div class="flash">
                        <span style="font-family: Helvetica; font-weight: 500;"><?= $flash; ?></span>
                    </div>
                <?php endif; ?>
                <form method="POST" enctype="multipart/form-data" action="<?=$base;?>/config" class="config-form">
                    <div class="form-config-images mb-20">
                        <label class="form-label-custom" for="avatar">
                            Novo Avatar: <br>
                            <input id="avatar" name="avatar" type="file" class="image-edit"> <br>
                            <img class="image-edit" src="<?= $base; ?>/media/avatars/<?= $user->avatar; ?>">
                        </label>
                        <label class="form-label-custom" for="avatar">
                            Nova Capa: <br>
                            <input id="cover" name="cover" type="file" class="image-edit"> <br>
                            <img class="image-edit" src="<?= $base; ?>/media/covers/<?= $user->cover; ?>">
                        </label>
                    </div>
                    <hr>
                    <div class="form-config-profile-info">
                        <label class="form-label-custom" for="name">
                            Nome Completo: <br>
                            <input id="name" type="text" name="name" value="<?= $user->name; ?>"> <br>
                        </label>
                        <label class="form-label-custom" for="birthdate">
                            Data de Nascimento: <br>
                            <input id="birthdate" type="text" name="birthdate" value="<?= DateTime::createFromFormat('Y-m-d', $user->birthdate)->format('d/m/Y'); ?>"> <br>
                        </label>
                        <label class="form-label-custom" for="email">
                            E-mail: <br>
                            <input id="email" type="email" name="email" value="<?= $user->email; ?>"> <br>
                        </label>
                        <label class="form-label-custom" for="city">
                            Cidade: <br>
                            <input id="city" type="text" name="city" value="<?= $user->city; ?>"> <br>
                        </label>
                        <label class="form-label-custom" for="work">
                            Trabalho: <br>
                            <input id="work" type="text" name="work" value="<?= $user->work; ?>"> <br>
                        </label>
                    </div>
                    <hr>
                    <div class="form-config-profile-password">
                        <label class="form-label-custom" for="password">
                            Senha: <br>
                            <input id="password" type="password" name="password"> <br>
                        </label>
                        <label class="form-label-custom" for="password_confirm">
                            Confirmar Nova Senha: <br>
                            <input id="password_confirm" type="password" name="password_confirm"> <br>
                        </label>
                    </div>
                    <button class="button">Salvar</button>
                </form>
            </div>
            <div class="column side pl-5">
                <?= $render('right-side'); ?>
            </div>
        </div>
    </section>
</section>

<script src="https://unpkg.com/imask"></script>
<script>
    IMask(
        document.getElementById('birthdate'), {
            mask: '00/00/0000',
        }
    );
</script>

<?= $render('footer'); ?>