<?php
namespace Datalist\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\Widget\SelectBoxWidget;
use Cake\View\Widget\WidgetInterface;

class DatalistWidget extends SelectBoxWidget implements WidgetInterface
{

    protected $_templates;

    public function __construct($templates)
    {
        $this->_templates = $templates;
    }

    /**
     * Render method form SelectBoxWidget
     *  the following differences is
     *      1. the 1st parameter of $this->_templates->format() call
     *      2. value are replaced to data-value to prevent browsers displaing it
     *          with javascript we change them back to value on submit
     *      3. id is returned
     *
     * @param array                            $data
     * @param \Cake\View\Form\ContextInterface $context
     * @return null|string
     */
    public function render(array $data, ContextInterface $context)
    {
        $data += [
            'name' => '',
            'empty' => false,
            'escape' => true,
            'options' => [],
            'disabled' => null,
            'val' => null,
        ];

        $options = str_replace(
            'value',
            'data-value',
            $this->_renderContent($data)
        );

        $name = $data['name'];
        unset($data['name'], $data['options'], $data['empty'], $data['val'], $data['escape']);
        if (isset($data['disabled']) && is_array($data['disabled'])) {
            unset($data['disabled']);
        }

        $attrs = $this->_templates->formatAttributes($data);
        return $this->_templates->format(
            'datalist',
            [
                'name' => $name,
                'attrs' => $attrs,
                'content' => implode('', $options),
                'id' => $data['id']
            ]
        );
    }

    public function secureFields(array $data)
    {
        return [$data['name']];
    }
}
