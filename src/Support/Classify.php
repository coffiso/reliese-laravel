<?php

/**
 * Created by Cristian.
 * Date: 05/09/16 11:27 PM.
 */

namespace Reliese\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class Classify
{
    /**
     * @param string $name
     * @param string $value
     *
     * @return string
     */
    public function annotation($name, $value)
    {
        return "\n * @$name $value";
    }

    /**
     * Constant template.
     *
     * @param string $name
     * @param mixed  $value
     * @param string $comment
     * @param bool   $isFirst
     *
     * @return string
     */
    public function constant($name, $value, $comment, $isFirst = false)
    {
        $value = Dumper::export($value);

        $full = $isFirst ? '' : "\n";
        $full .= <<< EOL
            public const string {$name} = {$value};

        EOL;

        return $full;
    }

    /**
     * Field template.
     *
     * @param string $name
     * @param mixed $value
     * @param array $options
     *
     * @return string
     */
    public function field($name, $value, $options = [])
    {
        $value = Dumper::export($value);
        $visibility = Arr::get($options, 'visibility', 'protected');
        $after = Arr::get($options, 'after', "\n");

        return "\n\t/** {@inheritDoc} */\n\t$visibility \$$name = $value;$after";
    }

    /**
     * @param string $doc
     * @param string $name
     * @param string $body
     * @param array $options
     *
     * @return string
     */
    public function method($doc, $name, $body, $options = [])
    {
        $visibility = Arr::get($options, 'visibility', 'public');
        $returnType = Arr::get($options, 'returnType', null);
        $parameters = Arr::get($options, 'parameters', '');
        if (is_array($parameters) === true) {
            $parameters = new Collection($parameters);
            $parameters = $parameters->map(fn($v) => "{$v['type']} \${$v['name']}")->join(', ');
        }

        $formattedReturnType = $returnType ? ': '.$returnType : '';

        return "\n$doc\t$visibility function $name({$parameters})$formattedReturnType\n\t{\n\t\t$body\n\t}\n";
    }

    public function mixin($class)
    {
        if (Str::startsWith($class, '\\')) {
            $class = Str::replaceFirst('\\', '', $class);
        }

        return "\tuse \\$class;\n";
    }
}
