<?php
namespace yii\adminUi\widget;

use Yii;
use yii\helpers\Json;
use yii\adminUi\assetsBundle\AdminUiAsset as AdminUiAsset;
use yii\helpers\Html;

/**
 * \yii\bootstrap\Widget is the base class for all bootstrap widgets.
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since  2.0
 */
trait WidgetTrait {

    /**
     * Initializes the widget.
     * This method will register the bootstrap asset bundle. If you override this method,
     * make sure you call the parent implementation first.
     */
    public function init() {
        parent::init();
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
    }

    /**
     * Registers a specific Bootstrap plugin and the related events
     * @param string $name the name of the Bootstrap plugin
     */
    protected function registerPlugin($name, $element = null, $callback = null, $callbackCon = null) {
        $id   = $this->options['id'];
        $view = $this->getView();

        AdminUiAsset::register($view);
        if ($this->clientOptions !== false) {
            $options = empty($this->clientOptions) ? '' : Json::encode($this->clientOptions);
            $view->registerJs("jQuery('#$id').$name($options);");
        }

        if (!empty($this->clientEvents)) {
            $js = [];
            foreach ($this->clientEvents as $event => $handler) {
                $js[] = "jQuery('#$id').on('$event', $handler);";
            }
            $view->registerJs(implode("\n", $js));
        }
    }

    protected function renderBadge($badgeOptions) {
        return ($badgeOptions) ? Html::tag('small', $badgeOptions['text'], ['class' => $this->getBadgeClass($badgeOptions['type'])]) : '';
    }

    protected function getBadgeClass($type) {
        $class = '';
        switch ($type) {
            case 'new':
                $class = 'badge pull-right bg-green';
                break;
            case 'notification1':
                $class = 'badge pull-right bg-red';
                break;
            case 'notification2':
                $class = 'badge pull-right bg-yellow';
                break;
        }
        return $class;
    }
}
