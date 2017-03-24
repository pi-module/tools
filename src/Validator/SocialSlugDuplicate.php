<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Tools\Validator;

use Pi;
use Zend\Validator\AbstractValidator;

class SocialSlugDuplicate extends AbstractValidator
{
    const TAKEN        = 'socialExists';
    const CHARACTER    = 'socialCharacter';

    public function __construct()
    {
        $this->messageTemplates = array(
            self::TAKEN => _a('Social slug already exists.'),
            self::CHARACTER => _a('Just [a-zA-Z0-9] supported'),
        );

        parent::__construct();
    }

    /**
     * Social slug validate
     *
     * @param  mixed $value
     * @param  array $context
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        $this->setValue($value);

        if (null !== $value) {
            $value = strval($value);
            $where = array('slug' => $value);
            if (!empty($context['id'])) {
                $where['id <> ?'] = $context['id'];
            }
            $rowset = Pi::model('tools/social')->select($where);
            if ($rowset->count()) {
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
