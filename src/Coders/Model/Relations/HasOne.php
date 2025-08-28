<?php

/**
 * Created by Cristian.
 * Date: 11/09/16 09:26 PM.
 */

namespace Reliese\Coders\Model\Relations;

use Illuminate\Support\Str;

class HasOne extends HasOneOrMany
{
    /**
     * @return string
     */
    public function hint()
    {
        return $this->related->getQualifiedUserClassName();
    }

    /**
     * @return string
     */
    public function name()
    {
        //if ($this->parent->usesSnakeAttributes()) {
        //    return Str::snake($this->related->getClassName());
        //}

        return Str::camel($this->related->getClassName());
    }

    /**
     * @return string
     */
    public function methodDocument()
    {
        return <<<EOL
            /**
             * {$this->related->getQualifiedUserClassName()} モデルクラスに対する One To One リレーション
             *
             * {$this->parent->getQualifiedUserClassName()} (One) -> {$this->related->getQualifiedUserClassName()} (One)
             *
             * @api
             *
             * @return \Illuminate\Database\Eloquent\Relations\HasOne<{$this->related->getQualifiedUserClassName()}, \$this>
             */

        EOL;
    }

    /**
     * @return string
     */
    public function propertyComment()
    {
        return "{$this->parent->getQualifiedUserClassName()} (One) -> {$this->related->getQualifiedUserClassName()} (One)";
    }

    /**
     * @return string
     */
    public function method()
    {
        return 'hasOne';
    }

    /**
     * @return string
     */
    public function returnType()
    {
        return \Illuminate\Database\Eloquent\Relations\HasOne::class;
    }
}
