<?php
namespace Datalist\Model\Behavior;

use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\Utility\Inflector;

/**
 * Datalist behavior
 */
class DatalistBehavior extends Behavior
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * Changing data from datalist to CakePHP's reqirements
     *
     * @param \Cake\Event\Event $event
     * @param \ArrayObject      $data
     * @param \ArrayObject      $options
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        $keysToRemove = [];
        foreach ($data as $key => $value) {
            $model = substr($key, 0, -3);
            if (substr($key, -3) == '_id' && !intval($value) && strpos($model, '__') !== 0) {
                if ($field = $this->getConfig(ucfirst(Inflector::pluralize($model)))) {
                    $data[$model] = [$field => $value];
                }
                $keysToRemove[] = $key;
            }

            if (is_array($value) && array_key_exists('_ids', $value)
                && !is_array($value['_ids']) && !intval($value['_ids'])) {
                if ($field = $this->getConfig(ucfirst(Inflector::pluralize($key)))) {
                    $data[$key][] = [
                        $field => $value['_ids']
                    ];
                    unset($data[$key]['_ids']);
                } else {
                    $keysToRemove[] = $key;
                }
            }

            if (is_array($value) && array_key_exists('_ids', $value)
                && !is_array($value['_ids']) && intval($value['_ids'])) {
                $data[$key]['_ids'] = [$value['_ids']];
            }
        }

        foreach ($keysToRemove as $key) {
            unset($data[$key]);
        }
    }
}
