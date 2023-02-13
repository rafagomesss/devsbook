<?= $render('header', ['loggedUser' => $loggedUser]); ?>

<section class="container main">
    <?= $render('sidebar', ['activeMenu' => 'profile']); ?>
    <section class="feed">

        <?= $render('perfil-header',['loggedUser' => $loggedUser, 'user' => $user, 'isFollowing' => $isFollowing]); ?>

        <div class="row">

            <div class="column side pr-5">

                <div class="box">
                    <div class="box-body">

                        <div class="user-info-mini">
                            <img src="<?= $base; ?>/assets/images/calendar.png" />
                            <?= (new DateTime($user->birthdate, new DateTimeZone('America/Sao_Paulo')))->format('d/m/Y'); ?> (<?= $user->ageYears; ?> anos)
                        </div>
                        <?php if (!empty($user->city)) : ?>
                            <div class="user-info-mini">
                                <img src="<?= $base; ?>/assets/images/pin.png" />
                                <?= $user->city; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($user->work)) : ?>
                            <div class="user-info-mini">
                                <img src="<?= $base; ?>/assets/images/work.png" />
                                <?= $user->work; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="box">
                    <div class="box-header m-10">
                        <div class="box-header-text">
                            Seguindo
                            <span>(<?= count($user->following); ?>)</span>
                        </div>
                        <div class="box-header-buttons">
                            <a href="<?= $base; ?>/perfil/<?= $user->id; ?>/amigos">ver todos</a>
                        </div>
                    </div>
                    <div class="box-body friend-list">
                        <?php foreach ($user->following as $follower) : ?>
                            <div class="friend-icon">
                                <a href="<?= $base; ?>/perfil/<?= $follower->id; ?>">
                                    <div class="friend-icon-avatar">
                                        <img src="<?= $base; ?>/media/avatars/<?= $follower->avatar; ?>" />
                                    </div>
                                    <div class="friend-icon-name">
                                        <?= $follower->name; ?>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>

            </div>
            <div class="column pl-5">

                <div class="box">
                    <div class="box-header m-10">
                        <div class="box-header-text">
                            Fotos
                            <span>(<?= count($user->photos); ?>)</span>
                        </div>
                        <div class="box-header-buttons">
                            <a href="<?= $base; ?>/perfil/<?= $user->id; ?>/fotos">ver todos</a>
                        </div>
                    </div>
                    <div class="box-body row m-20">
                        <?php for ($x = 0; $x < 4; $x++) : ?>
                            <?php if (isset($user->photos[$x])) : ?>
                                <div class="user-photo-item">
                                    <a href="#modal-<?= $user->photos[$x]->id; ?>" rel="modal:open">
                                        <img src="<?= $base; ?>/media/uploads/<?= $user->photos[$x]->body; ?>" />
                                    </a>
                                    <div id="modal-<?= $user->photos[$x]->id; ?>" style="display:none">
                                        <img src="<?= $base; ?>/media/uploads/<?= $user->photos[$x]->body; ?>" />
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                </div>

                <?php if ($user->id === $loggedUser->id) : ?>
                    <?= $render('feed-editor', ['user' => $loggedUser]); ?>
                <?php endif; ?>

                <?php foreach ($feed['posts'] as $feedItem) : ?>
                    <?= $render('feed-item', [
                        'data' => $feedItem,
                        'loggedUser' => $loggedUser,
                    ]); ?>
                <?php endforeach; ?>

                <div class="feed-pagination">
                    <?php for ($i = 0; $i < $feed['pageCount']; $i++) : ?>
                        <a class="<?= $i == $feed['currentPage'] ? 'active' : ''; ?>" href="<?= $base; ?>/perfil/<?= $user->id; ?>?page=<?= $i; ?>">
                            <?= $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>

                TOTAL DE PÁGINAS: <?= $feed['pageCount']; ?>


            </div>

        </div>

    </section>
</section>

<?= $render('footer'); ?>