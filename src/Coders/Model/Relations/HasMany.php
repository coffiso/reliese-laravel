<?php

/**
 * Created by Cristian.
 * Date: 11/09/16 09:26 PM.
 */

namespace Reliese\Coders\Model\Relations;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

class HasMany extends HasOneOrMany
{
    /**
     * @return string
     */
    public function hint()
    {
        return '\\'.Collection::class.'<int, '.$this->related->getQualifiedUserClassName().'>';
    }

    /**
     * @return string
     */
    public function name()
    {
        switch ($this->parent->getRelationNameStrategy()) {
            case 'foreign_key':
                $relationName = RelationHelper::stripSuffixFromForeignKey(
                    $this->parent->usesSnakeAttributes(),
                    $this->localKey(),
                    $this->foreignKey()
                );
                if (Str::snake($relationName) === Str::snake($this->parent->getClassName())) {
                    $relationName = Str::plural($this->related->getClassName());
                } else {
                    $relationName = Str::plural($this->related->getClassName()) . 'Where' . ucfirst(Str::singular($relationName));
                }
                break;
            default:
            case 'related':
                $relationName = Str::plural($this->related->getClassName());
                break;
        }

        //if ($this->parent->usesSnakeAttributes()) {
        //    return Str::snake($relationName);
        //}

        return Str::camel($relationName);
    }

    /**
     * @return string
     */
    public function methodDocument()
    {
        return <<<EOL
            /**
             * {$this->related->getQualifiedUserClassName()} モデルクラスに対する One To Many リレーション
             *
             * {$this->parent->getQualifiedUserClassName()} (One) -> {$this->related->getQualifiedUserClassName()} (Many)
             *
             * @api
             *
             * @return \Illuminate\Database\Eloquent\Relations\HasMany<{$this->related->getQualifiedUserClassName()}, \$this>
             */

        EOL;
    }

    /**
     * @return string
     */
    public function propertyComment()
    {
        return "{$this->parent->getQualifiedUserClassName()} (One) -> {$this->related->getQualifiedUserClassName()} (Many)";
    }

    /**
     * @return string
     */
    public function method()
    {
        return 'hasMany';
    }

    /**
     * @return string
     */
    public function returnType()
    {
        return \Illuminate\Database\Eloquent\Relations\HasMany::class;
    }
}
