<?php

/*
 * This file is part of the Prophecy.
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *     Marcello Duarte <marcello.duarte@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prophecy\Doubler\Generator\Node;

/**
 * Argument node.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class ArgumentNode
{
    private $name;
    private $default;
    private $optional = false;
    private $byReference = false;
    private $isVariadic = false;

    /** @var ArgumentTypeNode */
    private $typeNode;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->typeNode = new ArgumentTypeNode();
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTypeNode(): ArgumentTypeNode
    {
        return $this->typeNode;
    }

    public function setTypeNode(ArgumentTypeNode $typeNode)
    {
        $this->typeNode = $typeNode;
    }

    public function hasDefault()
    {
        return $this->isOptional() && !$this->isVariadic();
    }

    public function isOptional()
    {
        return $this->optional;
    }

    public function isVariadic()
    {
        return $this->isVariadic;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function setDefault($default = null)
    {
        $this->optional = true;
        $this->default = $default;
    }

    public function setAsPassedByReference($byReference = true)
    {
        $this->byReference = $byReference;
    }

    public function isPassedByReference()
    {
        return $this->byReference;
    }

    public function setAsVariadic($isVariadic = true)
    {
        $this->isVariadic = $isVariadic;
    }

    /**
     * @return string|null
     * @deprecated use getArgumentTypeNode instead
     */
    public function getTypeHint()
    {
        $type = $this->typeNode->getNonNullTypes() ? $this->typeNode->getNonNullTypes()[0] : null;

        return $type ? ltrim($type, '\\') : null;
    }

    /**
     * @param string|null $typeHint
     * @deprecated use setArgumentTypeNode instead
     */
    public function setTypeHint($typeHint = null)
    {
        $this->typeNode = ($typeHint === null) ? new ArgumentTypeNode() : new ArgumentTypeNode($typeHint);
    }

    /**
     * @return bool
     * @deprecated use getArgumentTypeNode instead
     */
    public function isNullable()
    {
        return $this->typeNode->canUseNullShorthand();
    }

    /**
     * @param bool $isNullable
     * @deprecated use getArgumentTypeNode instead
     */
    public function setAsNullable($isNullable = true)
    {
        $nonNullTypes = $this->typeNode->getNonNullTypes();
        $this->typeNode = $isNullable ? new ArgumentTypeNode('null', ...$nonNullTypes) : new ArgumentTypeNode(...$nonNullTypes);
    }
}
