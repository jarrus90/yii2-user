<?php

namespace jarrus90\User\Widgets;

use yii\helpers\Html;

class Share extends \bigpaulie\social\share\Share {

    public $url;
    public $tag = 'span';
    public $text = 'Share on {network}';
    public $template = "<span class='icon'>{button}</span>";
    public $htmlOptions = [
        'class' => 'share-widget-content'
    ];
    public $containerOptions = [
        'class' => 'share-widget'
    ];
    public $titleOptions = [
        'class' => 'share-widget-title'
    ];
    public $title;
    protected $networks = [
        'facebook' => 'https://www.facebook.com/sharer/sharer.php?u={url}',
        'google-plus' => 'https://plus.google.com/share?url={url}',
        'twitter' => 'https://twitter.com/home?status={url}',
        'linkedin' => 'https://www.linkedin.com/shareArticle?mini=true&url={url}',
        'vk' => 'http://vk.com/share.php?url={url}',
    ];

    public function run() {
        if(!$this->title) {
            $this->title = \Yii::t('user', 'Share on');
        }
        echo Html::beginTag('div', $this->containerOptions);
        echo Html::tag('span', $this->title, $this->titleOptions);
        parent::run();
        echo Html::endTag('div');
    }

}
