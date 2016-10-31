<?php
/**
 * File name: StringLiteral.php
 * Project: project1
 * PHP version 5
 * @category  PHP
 * @package   Project1\Domain
 * @author    donbstringham <donbstringham@gmail.com>
 * @copyright 2016 © donbstringham
 * @license   http://opensource.org/licenses/MIT MIT
 * @version   GIT: <git_id>
 * $LastChangedDate$
 * $LastChangedBy$
 */

namespace RoadHome\Domain;

class StringLiteral implements ValueObject
{
    /** @var string */
    protected $value;
    /**
     * @param string $value
     * @throws \InvalidArgumentException
     */
    public function __construct($value)
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException(
                __CLASS__ . ': only accepts strings, ' . gettype($value) . ' passed'
            );
        }
        $this->value = $value;
    }
    /**
     * @return static
     * @throws \InvalidArgumentException
     */
    public static function fromNative()
    {
        $value = func_get_arg(0);
        return new static($value);
    }
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toNative();
    }
    /**
     * @return string
     */
    public function toNative()
    {
        return $this->value;
    }
    /**
     * @param \RoadHome\Domain\ValueObject $stringLiteral
     * @return mixed
     */
    public function equal(ValueObject $stringLiteral)
    {
        if (false === Util::equals($this, $stringLiteral)) {
            return false;
        }
        /** @var \RoadHome\Domain\StringLiteral $stringLiteral */
        return $this->toNative() === $stringLiteral->toNative();
    }
    /**
     * @return bool
     */
    public function isEmpty()
    {
        return '' === $this->toNative();
    }
    /**
     * @return string
     */
    public function jsonSerialize()
    {
        return (string)$this;
    }
}