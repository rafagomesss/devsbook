<div class="box feed-item" data-id="<?= $data->id; ?>">
    <div class="box-body">
        <div class="feed-item-head row mt-20 m-width-20">
            <div class="feed-item-head-photo">
                <a href="<?= $base; ?>/perfil/<?= $data->user->id; ?>"><img src="<?= $base; ?>/media/avatars/<?= $data->user->avatar; ?>" /></a>
            </div>
            <div class="feed-item-head-info">
                <a href="<?= $base; ?>/perfil/<?= $data->user->id ?>"><span class="fidi-name"><?= $data->user->name; ?></span></a>
                <span class="fidi-action"><?= $data->type === 'text' ? 'fez um post' : 'postou uma foto'; ?></span>
                <br />
                <span class="fidi-date"><?= (new DateTime($data->createdAt, new DateTimeZone('America/Sao_Paulo')))->format('d/m/Y'); ?></span>
            </div>
            <?php if ($data->mine) : ?>
                <div class="feed-item-head-btn">
                    <img src="<?= $base; ?>/assets/images/more.png" />
                    <div class="feed-item-more-window">
                        <a href="<?= $base; ?>/post/<?= $data->id; ?>/delete">Exlcuir Post</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="feed-item-body mt-10 m-width-20">
            <?php
                switch ($data->type) {
                    case 'photo':
                        echo '<img = src="' . $base . '/media/uploads/' . $data->body . '">';
                        break;
                    default:
                        echo nl2br($data->body);
                        break;
                }
            ?>
        </div>
        <div class="feed-item-buttons row mt-20 m-width-20">
            <div class="like-btn <?= $data->liked ? 'on' : 'off'; ?>"><?= $data->likeCount; ?></div>
            <div class="msg-btn"><?= count($data->comments); ?></div>
        </div>
        <div class="feed-item-comments">
            <div class="feed-item-comments-area">
                <?php if (count($data->comments)> 0) : ?>
                    <?php foreach($data->comments as $comment) : ?>
                        <div class="fic-item row m-height-10 m-width-20">
                            <div class="fic-item-photo">
                                <a href="<?= $base; ?>/perfil/<?= $comment['user']['id']; ?>"><img src="<?= $base; ?>/media/avatars/<?= $comment['user']['avatar']; ?>" /></a>
                            </div>
                            <div class="fic-item-info">
                                <a href="<?= $base; ?>/perfil/<?= $comment['user']['id']; ?>"><?= $comment['user']['name']; ?></a>
                                <?= $comment['body']; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="fic-answer row m-height-10 m-width-20">
                <div class="fic-item-photo">
                    <a href="<?= $base; ?>/perfil/<?= $loggedUser->id; ?>"><img src="<?= $base; ?>/media/avatars/<?= $loggedUser->avatar; ?>" /></a>
                </div>
                <input type="text" class="fic-item-field" placeholder="Escreva um coment??rio" />
            </div>
        </div>
    </div>
</div>