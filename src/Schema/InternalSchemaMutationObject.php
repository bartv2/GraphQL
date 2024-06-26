<?php
/*
* This file is a part of GraphQL project.
*
* @author Alexandr Viniychuk <a@viniychuk.com>
* created: 5/14/16 9:28 AM
*/

namespace Youshido\GraphQL\Schema;


use Youshido\GraphQL\Config\Object\ObjectTypeConfig;
use Youshido\GraphQL\Type\Object\AbstractObjectType;

class InternalSchemaMutationObject extends AbstractObjectType
{
    public function build(ObjectTypeConfig $config): void
    {
    }

}
