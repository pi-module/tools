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
use Laminas\Validator\AbstractValidator;

class SlugDuplicate extends AbstractValidator
{
    const TAKEN     = 'slugExists';
    const CHARACTER = 'slugCharacter';

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
            self::TAKEN     => _a('This slug already exists.'),
            self::CHARACTER => _a('Just [a-zA-Z0-9] supported'),
        ];

        parent::__construct($options);
    }

    /**
     * Slug validate
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
            $where = ['slug' => $value];
            if (!empty($this->options['id'])) {
                $where['id <> ?'] = $this->options['id'];
            }
            $rowSet = Pi::model('tools/category')->select($where);
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
