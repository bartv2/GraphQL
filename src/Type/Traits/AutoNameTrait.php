<?php
/*
* This file is a part of GraphQL project.
*
* @author Alexandr Viniychuk <a@viniychuk.com>
* created: 5/4/16 9:18 PM
*/

namespace Youshido\GraphQL\Type\Traits;

use Youshido\GraphQL\Field\FieldInterface;

/**
 * Class AutoNameTrait
 * @package Youshido\GraphQL\Type\Traits
 */
trait AutoNameTrait
{

    public function getName(): ?string
    {
        if (!empty($this->config?->getName())) {
            return $this->config->getName();
        }

        $className = get_called_class();

        if ($prevPos = strrpos($className, '\\')) {
            $className = substr($className, $prevPos + 1);
        }

        if (str_ends_with($className, 'Field')) {
            $className = lcfirst(substr($className, 0, -5));
        } elseif (str_ends_with($className, 'Type')) {
            $className = substr($className, 0, -4);
        }

        if ($this instanceof FieldInterface) {
            $className = lcfirst($className);
        }

        return $className;
    }
}