<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace yii\adminUi\widget;

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
class Nav extends \yii\bootstrap\Nav {

    use WidgetTrait;

    /**
     * Renders the widget.
     */
    public function run() {
        AdminUiAsset::register($this->getView());
        return $this->renderItems();
    }

    /**
     * Renders widget items.
     */
    public function renderItems() {
        $items = [];
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
    public function renderItem($item) {
        if (is_string($item)) {
            return $item;
        }
        if (isset($item['content'])) {
            return Html::tag('li', $item['content'], $item['options']);
        }
        if (!isset($item['label'])) {
            throw new InvalidConfigException("The 'label' option is required.");
        }
        $label        = $this->encodeLabels ? Html::encode($item['label']) : $item['label'];
        $options      = ArrayHelper::getValue($item, 'options', []);
        $items        = ArrayHelper::getValue($item, 'items');
        $url          = ArrayHelper::getValue($item, 'url', '#');
        $linkOptions  = ArrayHelper::getValue($item, 'linkOptions', []);
        $badgeOptions = ArrayHelper::getValue($item, 'badgeOptions', []);

        if (array_key_exists('active', $item)) {
            $active = ArrayHelper::remove($item, 'active', false);
        } else {
            $active = $this->isItemActive($item);
        }

        if ($items !== null) {
            //$linkOptions['data-toggle'] = 'treeview';
            Html::addCssClass($options, 'treeview');
            //Html::addCssClass($linkOptions, 'treeview-menu');
            //$label .= ' ' . Html::tag('i', '', ['class' => 'fa fa-angle-left pull-right']);
            if (is_array($items)) {
                if ($this->activateItems) {
                    $items = $this->processChildren($items, $active);
                }
                $items = Dropdown::widget([
                    'items'         => $items,
                    'encodeLabels'  => $this->encodeLabels,
                    'clientOptions' => false,
                    'type'          => Dropdown::NAV,
                    'view'          => $this->getView(),
                ]);
            }
        }

        if ($this->activateItems && $active) {
            Html::addCssClass($options, 'active');
        }

        $label = Html::tag('i', '', $linkOptions).Html::tag('span', $label);
        $label .= $this->renderBadge($badgeOptions);
        if ($items !== null) {
            $label .= Html::tag('i', '', ['class' => 'fa fa-angle-left pull-right']);
        }

        return Html::tag('li', Html::a($label, $url).$items, $options);
    }

    /**
     * Check to see if a child item is active optionally activating the parent.
     * @param array   $items  @see items
     * @param boolean $active should the parent be active too
     * @return array @see items
     */
    protected function processChildren($items, &$active) {
        foreach ($items as $i => $child) {
            if (array_key_exists('items', $child) && is_array($child['items'])) {
                $childActive        = false;
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
}
