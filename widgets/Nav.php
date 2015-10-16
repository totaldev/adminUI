<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace yii\adminUi\widgets;

use Yii;
use yii\adminUi\assetsBundle\AdminUiAsset as AdminUiAsset;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Nav renders a nav HTML component.
 * For example:
 * ```php
 * echo Nav::widget([
 *     'items' => [
 *         [
 *             'label' => 'Home',
 *             'url' => ['site/index'],
 *             'linkOptions' => [...],
 *         ],
 *         [
 *             'label' => 'Dropdown',
 *             'items' => [
 *                  ['label' => 'Level 1 - Dropdown A', 'url' => '#'],
 *                  '<li class="divider"></li>',
 *                  '<li class="dropdown-header">Dropdown Header</li>',
 *                  ['label' => 'Level 1 - Dropdown B', 'url' => '#'],
 *             ],
 *         ],
 *     ],
 * ]);
 * ```
 * Note: Multilevel dropdowns beyond Level 1 are not supported in Bootstrap 3.
 * @see    http://getbootstrap.com/components/#dropdowns
 * @see    http://getbootstrap.com/components/#nav
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @since  2.0
 */
class Nav extends \yii\bootstrap\Nav
{

    use WidgetTrait;
    public $header;
    public $headerOptions;

    /**
     * Renders the widget.
     */
    public function run()
    {
        AdminUiAsset::register($this->getView());
        return $this->renderItems();
    }

    /**
     * Renders widget items.
     */
    public function renderItems()
    {
        $items = [];
        if ($this->header) {
            $items[] = Html::tag('li', $this->header, $this->headerOptions);
        }

        foreach ($this->items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                unset($items[$i]);
                continue;
            }
            $items[] = $this->renderItem($item);
        }

        return Html::tag('ul', implode("\n", $items), $this->options);
    }

    /**
     * Renders a widget's item.
     * @param string|array $item the item to render.
     * @return string the rendering result.
     * @throws InvalidConfigException
     */
    public function renderItem($item)
    {
        if (is_string($item)) {
            return $item;
        }
        if (isset($item['content'])) {
            return Html::tag('li', $item['content'], $item['options']);
        }
        if (!isset($item['label'])) {
            throw new InvalidConfigException("The 'label' option is required.");
        }
        $label = $this->encodeLabels ? Html::encode($item['label']) : $item['label'];
        $options = ArrayHelper::getValue($item, 'options', []);
        $items = ArrayHelper::getValue($item, 'items');
        $url = ArrayHelper::getValue($item, 'url', '#');
        $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);
        $badgeOptions = ArrayHelper::getValue($item, 'badgeOptions', []);

        if (array_key_exists('active', $item)) {
            $active = ArrayHelper::remove($item, 'active', false);
        } else {
            $active = $this->isItemActive($item);
        }

        $subMenu = '';
        if (!empty($items)) {
            //$linkOptions['data-toggle'] = 'treeview';
            Html::addCssClass($options, 'treeview');
            //Html::addCssClass($linkOptions, 'treeview-menu');
            //$label .= ' ' . Html::tag('i', '', ['class' => 'fa fa-angle-left pull-right']);
            if (!empty($items)) {
                if ($this->activateItems) {
                    $items = $this->processChildren($items, $active);
                }
                $subMenu = Dropdown::widget([
                    'items' => $items,
                    'encodeLabels' => $this->encodeLabels,
                    'clientOptions' => false,
                    'type' => Dropdown::NAV,
                    'view' => $this->getView(),
                ]);
            }
        }

        if ($this->activateItems && $active) {
            Html::addCssClass($options, 'active');
        }

        $label = Html::tag('i', '', (array)$linkOptions) . Html::tag('span', $label);
        $label .= $this->renderBadge((array)$badgeOptions);
        if (!empty($items)) {
            $label .= Html::tag('i', '', ['class' => 'fa fa-angle-left pull-right']);
        }

        return Html::tag('li', Html::a($label, $url) . $subMenu, (array)$options);
    }

    /**
     * Check to see if a child item is active optionally activating the parent.
     * @param array $items @see items
     * @param boolean $active should the parent be active too
     * @return array @see items
     */
    protected function processChildren($items, &$active)
    {
        foreach ($items as $i => $child) {
            if (array_key_exists('items', $child) && is_array($child['items'])) {
                $childActive = false;
                $items[$i]['items'] = $this->processChildren($child['items'], $childActive);
                if ($childActive) {
                    Html::addCssClass($items[$i]['options'], 'active');
                    $active = $childActive * $this->activateParents;
                }
            } elseif (ArrayHelper::remove($items[$i], 'active') || $this->isItemActive($child)) {
                Html::addCssClass($items[$i]['options'], 'active');
                if ($this->activateParents) {
                    $active = true;
                }
            }
        }
        return $items;
    }


    /**
     * @inheritdoc
     */
    protected function isItemActive($item)
    {
        if (isset($item['url']) && is_array($item['url']) && isset($item['url'][0])) {
            $route = $item['url'][0];
            if ($route[0] !== '/' && Yii::$app->controller) {
                $route = Yii::$app->controller->module->getUniqueId() . '/' . $route;
            }

            $route = ltrim($route, '/');
            if (!$route || !substr_count(ltrim($this->route, '/'), $route)) {
                return false;
            }

            unset($item['url']['#']);
            if (count($item['url']) > 1) {
                $params = $item['url'];
                unset($params[0]);
                foreach ($params as $name => $value) {
                    if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] != $value)) {
                        return false;
                    }
                }
            }

            return true;
        }

        return false;
    }
}
