<?php

declare(strict_types=1);

/* @var string $label */
/* @var User[] $users */
/* @var string $id */

use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="modal fade" id="<?= $id ?>" tabindex="-1" role="dialog"
     aria-labelledby="<?= $id ?>Label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="<?= $id ?>Label"><?= $label ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php foreach ($users as $user) : ?>
                        <div class="col-md-12">
                            <a href="<?= Url::to(['/user/profile/view', 'identifier' => $user['nickname'] ?: $user['id']]); ?>">
                                <?= Html::encode($user['username']); ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
