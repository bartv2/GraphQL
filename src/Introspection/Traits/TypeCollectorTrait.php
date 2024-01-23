<?php
/**
 * Date: 04.12.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\GraphQL\Introspection\Traits;

use Youshido\GraphQL\Type\AbstractType;
use Youshido\GraphQL\Type\InputObject\AbstractInputObjectType;
use Youshido\GraphQL\Type\InterfaceType\AbstractInterfaceType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\TypeMap;
use Youshido\GraphQL\Type\Union\AbstractUnionType;

trait TypeCollectorTrait
{
    protected array $types = [];

    protected function collectTypes(?AbstractType $type): void
    {
        if (empty($type) || array_key_exists($type->getName(), $this->types)) return;

        switch ($type->getKind()) {
            case TypeMap::KIND_INTERFACE:
            case TypeMap::KIND_UNION:
            case TypeMap::KIND_ENUM:
            case TypeMap::KIND_SCALAR:
                $this->insertType($type->getName(), $type);

                if ($type->getKind() == TypeMap::KIND_UNION) {
                    /** @var AbstractUnionType $type */
                    foreach ($type->getTypes() as $subType) {
                        $this->collectTypes($subType);
                    }
                }

                break;

            case TypeMap::KIND_INPUT_OBJECT:
            case TypeMap::KIND_OBJECT:
                /** @var AbstractObjectType $namedType */
                $namedType = $type->getNamedType();
                $this->checkAndInsertInterfaces($namedType);

                if ($this->insertType($namedType->getName(), $namedType)) {
                    $this->collectFieldsArgsTypes($namedType);
                }

                break;

            case TypeMap::KIND_LIST:

            case TypeMap::KIND_NON_NULL:
                $this->collectTypes($type->getNamedType());

                break;
        }
    }

    private function checkAndInsertInterfaces($type): void
    {
        foreach ((array)$type->getConfig()->getInterfaces() as $interface) {
            $this->insertType($interface->getName(), $interface);

            if ($interface instanceof AbstractInterfaceType) {
                foreach ($interface->getImplementations() as $implementation) {
                    $this->insertType($implementation->getName(), $implementation);
                }
            }
        }
    }

    /**
     * @param AbstractObjectType|AbstractInputObjectType $type
     * @return void
     */
    private function collectFieldsArgsTypes(AbstractObjectType|AbstractInputObjectType $type): void
    {
        foreach ($type->getConfig()->getFields() as $field) {
            $arguments = $field->getConfig()->getArguments();

            if (is_array($arguments)) {
                foreach ($arguments as $argument) {
                    $this->collectTypes($argument->getType());
                }
            }

            $this->collectTypes($field->getType());
        }
    }

    private function insertType($name, $type): bool
    {
        if (!array_key_exists($name, $this->types)) {
            $this->types[$name] = $type;

            return true;
        }

        return false;
    }
}
