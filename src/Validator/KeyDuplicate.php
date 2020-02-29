<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Tools\Validator;

use Pi;
use Zend\Validator\AbstractValidator;

class KeyDuplicate extends AbstractValidator
{
    const TAKEN     = 'keyExists';
    const CHARACTER = 'keyCharacter';

    /**
     * @var array
     */
    protected $messageTemplates = [];

    protected $options
        = [
            'id',
        ];

    public function __construct($options = null)
    {
        $this->messageTemplates = [
            self::TAKEN     => _a('This key already exists.'),
            self::CHARACTER => _a('Just [a-zA-Z0-9] supported'),
        ];

        parent::__construct($options);
    }

    /**
     * Key validate
     *
     * @param mixed $value
     * @param array $context
     *
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        $this->setValue($value);

        if (null !== $value) {
            $where = ['key' => $value];
            if (!empty($this->options['id'])) {
                $where['id <> ?'] = $this->options['id'];
            }
            $rowSet = Pi::model('tools/item')->select($where);
            if ($rowSet->count()) {
                $this->error(static::TAKEN);
                return false;
            }
            if (preg_match('/^[a-zA-Z0-9]+$/', $value) == 0) {
                $this->error(static::CHARACTER);
                return false;
            }
        }

        return true;
    }
}
