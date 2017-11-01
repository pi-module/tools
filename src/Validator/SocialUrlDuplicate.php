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

class SocialUrlDuplicate extends AbstractValidator
{
    const TAKEN = 'urlExists';

    public function __construct()
    {
        $this->messageTemplates = [
            self::TAKEN => _a('Social url already exists.'),
        ];

        parent::__construct();
    }

    /**
     * Social url validate
     *
     * @param  mixed $value
     * @param  array $context
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        $this->setValue($value);

        if (null !== $value) {
            $where = ['url' => $value];
            if (!empty($context['id'])) {
                $where['id <> ?'] = $context['id'];
            }
            $rowset = Pi::model('tools/social')->select($where);
            if ($rowset->count()) {
                $this->error(static::TAKEN);
                return false;
            }
        }

        return true;
    }
}
