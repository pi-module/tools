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

class TokenDuplicate extends AbstractValidator
{
    const TAKEN     = 'tokenExists';
    const CHARACTER = 'tokenCharacter';
    const LENGTH    = 'tokenLength';

    public function __construct()
    {
        $this->messageTemplates = [
            self::TAKEN     => _a('This token already exists.'),
            self::CHARACTER => _a('Just [a-zA-Z0-9] supported'),
            self::LENGTH    => _a('Token shorter than 64 length'),
        ];

        parent::__construct();
    }

    /**
     * Token validate
     *
     * @param  mixed $value
     * @param  array $context
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        $this->setValue($value);

        if (null !== $value) {
            $where = ['token' => $value];
            if (!empty($context['id'])) {
                $where['id <> ?'] = $context['id'];
            }
            $rowset = Pi::model('tools/token')->select($where);
            if ($rowset->count()) {
                $this->error(static::TAKEN);
                return false;
            }
            if (preg_match('/^[a-zA-Z0-9]+$/', $value) == 0) {
                $this->error(static::CHARACTER);
                return false;
            }
            if (strlen($value) < 16) {
                $this->error(static::LENGTH);
                return false;
            }
        }

        return true;
    }
}
