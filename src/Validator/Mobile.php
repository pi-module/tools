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
use Laminas\Db\Sql\Predicate\Expression;
use Laminas\Validator\AbstractValidator;

class Mobile extends AbstractValidator
{
    const FORMAT = 'elementFormat';
    const TAKEN  = 'elementExists';

    /**
     * @var array
     */
    protected $messageTemplates = [];

    protected $options
        = [
            'checkFormat' => true,
            'checkTaken'  => true,
        ];

    /**
     * {@inheritDoc}
     */
    public function __construct($options = null)
    {
        $this->messageTemplates = [
            self::FORMAT => __('Mobile number should be like 09121234567'),
            self::TAKEN  => __('This mobile number taken before'),
        ];
        parent::__construct($options);
    }

    /**
     * Element validate
     *
     * @param mixed $value
     * @param array $context
     *
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        // Set value
        $this->setValue($value);

        // Check mobile foramt
        if ($this->options['checkFormat']) {
            if (!is_numeric($value)) {
                $this->error(static::FORMAT);
                return false;
            }

            if (strlen($value) != 11) {
                $this->error(static::FORMAT);
                return false;
            }

            if (substr($value, 0, 2) != '09') {
                $this->error(static::FORMAT);
                return false;
            }
        }

        // Check mobile taken
        if ($this->options['checkTaken']) {

            // Set query information
            $columns = ['count' => new Expression('count(*)')];
            $where   = ['mobile' => $value];
            if (isset($context['uid']) && !empty($context['uid'])) {
                $where['uid <> ?'] = $context['uid'];
            }

            // Make query
            $select = Pi::model('profile', 'user')->select()->columns($columns)->where($where);
            $count  = Pi::model('profile', 'user')->selectWith($select)->current()->count;

            // Check
            if ($count > 0) {
                $this->error(static::TAKEN);
                return false;
            }
        }

        return true;
    }
}