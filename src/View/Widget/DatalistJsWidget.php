<?php
namespace Datalist\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\Widget\SelectBoxWidget;

class DatalistJsWidget extends SelectBoxWidget
{
    /** @var  \Cake\View\StringTemplate $_templates */
    protected $_templates;

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
        $default = isset($data['val']) ? $data['val'] : null;
        unset($data['name'], $data['options'], $data['empty'], $data['val'], $data['escape']);
        if (isset($data['disabled']) && is_array($data['disabled'])) {
            unset($data['disabled']);
        }

        $inputData['value'] = $default;
        $inputAttrs = $this->_templates->formatAttributes($inputData);
        $datalistAttrs = $this->_templates->formatAttributes($data);

        return $this->_templates->format(
            'datalistJs',
            [
                'name' => $name,
                'inputAttrs' => $inputAttrs,
                'datalistAttrs' => $datalistAttrs,
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
