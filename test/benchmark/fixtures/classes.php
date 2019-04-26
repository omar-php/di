<?php declare(strict_types=1);

namespace Fixtures;

interface I1 {}

interface I2 {}

abstract class AA {}

abstract class BB {}

class A extends AA implements I1 {}

class B extends BB implements I2 {}

class C
{
    public function __construct(I1 $param1, I2 $param2) {}
}

class CFactory
{
    /**
     * @var I1
     */
    private $param1;

    public function __construct(I1 $param1)
    {
        $this->param1 = $param1;
    }

    public function __invoke(I2 $param2): C
    {
        return new C($this->param1, $param2);
    }


}

class D
{
    /** @var int */
    public $num;

    /** @var string */
    public $str;

    public function __construct(int $num, string $str)
    {
        $this->num = $num;
        $this->str = $str;
    }
}

class DFactory
{
    public function __invoke(): D
    {
        return new D(1, 'STR');
    }
}

class E
{
    /** @var int */
    public $num;

    /** @var I1 */
    public $field1;

    public function __construct(int $num, I1 $field1)
    {
        $this->num = $num;
        $this->field1 = $field1;
    }
}

class F implements I1
{
    public $num = 0;
}
