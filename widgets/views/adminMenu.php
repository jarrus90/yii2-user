<?php if (Yii::$app->user->isGuest) { ?>
    <li>
        <a href="<?= \yii\helpers\Url::to(['/admin/login']) ?>">
            <i class="fa fa-sign-in"></i> <?= \Yii::t('user', 'Log in') ?>
        </a>
    </li>
<?php } else { ?>
    <?php foreach ($items AS $mainItem) { 
        $isActive = $mainItem['active'];
        $isList = ISSET($mainItem['childs']);
        ?>
        <li class="<?= $isList ? 'treeview': ''; ?><?= $isActive ? ' active' : '' ?>">
            <a href="<?= $isList ? '#' : (ISSET($mainItem['url']) ? $mainItem['url'] : '#' ); ?>">
                <?= $mainItem['icon']; ?><span><?= $mainItem['label'] ?></span>
                <?php if($isList) { ?>
                    <i class="fa fa-angle-left pull-right"></i>
                <?php } ?>
            </a>
            <?php if ($isList) { ?>
                <ul class="treeview-menu<?= $isActive ? ' menu-open' : '' ?>">
                    <?php foreach ($mainItem['childs'] AS $child) { ?>
                        <li>
                            <a href="<?= $child['url'] ?>">
                                <span class="fa fa-list"></span> <?= $child['title'] ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
        </li>
    <?php } ?>
<?php } ?>