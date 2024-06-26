<?php
/*
 * This file is a part of GraphQL project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 2:00 PM 5/15/16
 */

namespace Youshido\Tests\DataProvider;


use Youshido\GraphQL\Config\Object\ObjectTypeConfig;
use Youshido\GraphQL\Type\Object\AbstractMutationObjectType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;

class TestMutationObjectType extends AbstractMutationObjectType
{
    public function getOutputType(): \Youshido\GraphQL\Type\Scalar\StringType
    {
        return new StringType();
    }

    public function build(ObjectTypeConfig $config): void
    {
        $this->addArgument('increment', new IntType());
    }


}