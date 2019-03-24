<?php declare(strict_types=1);

namespace Test\Unit;

use Fixtures\A;
use Fixtures\AA;
use Fixtures\B;
use Fixtures\BB;
use Fixtures\C;
use Fixtures\CFactory;
use Fixtures\D;
use Fixtures\DFactory;
use Fixtures\E;
use Fixtures\F;
use Fixtures\I1;
use Fixtures\I2;
use Omar\DependencyInjection\Config;
use Omar\DependencyInjection\Configuration\ConfigBuilder;
use Omar\DependencyInjection\Container;
use Omar\DependencyInjection\Exception\ContainerSetupError;
use Omar\DependencyInjection\Exception\NotFound;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ContainerTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/fixtures/classes.php';
    }

    public function testHasForClass(): void
    {
        $this->assertTrue($this->container()->has(A::class));
    }

    public function testHasForInterface(): void
    {
        $this->assertTrue($this->container()->has(I1::class));
    }

    public function testHasForInvalidClassName(): void
    {
        $this->assertFalse($this->container()->has('Invalid'));
    }

    public function testHasForNumber(): void
    {
        $this->assertFalse($this->container()->has(0));
    }

    public function testMakeClass(): void
    {
        $this->assertTrue($this->container()->get(A::class) instanceof A);
    }

    public function testNotFoundException(): void
    {
        try {
            $this->container()->get('Invalid');
        } catch (NotFound $e) {
            $this->assertEquals(
                'Missing class or interface: Invalid',
                $e->getMessage()
            );

            return;
        }
        $this->fail('Excepton not thrown');
    }

    public function testContainerSetupException(): void
    {
        try {
            $this->container()->get(I2::class);
        } catch (ContainerSetupError $e) {
            $this->assertEquals(
                'Cannot make class or interface: Fixtures\\I2',
                $e->getMessage()
            );

            return;
        }
        $this->fail('Excepton not thrown');
    }

    public function testBind(): void
    {
        $container = $this->container(
            Config::init()
                ->bind(I1::class, A::class)
                ->bind(I2::class, B::class)
        );
        $this->assertTrue($container->get(I1::class) instanceof A);
        $this->assertTrue($container->get(I2::class) instanceof B);
    }

    public function testBindAbstract(): void
    {
        $container = $this->container(
            Config::init()
                ->bind(AA::class, A::class)
                ->bind(BB::class, B::class)
        );
        $this->assertTrue($container->get(AA::class) instanceof A);
        $this->assertTrue($container->get(BB::class) instanceof B);
    }

    public function testWire(): void
    {
        $container = $this->container(
            Config::init()
                ->setup(C::class, Config::params()
                    ->wire('param1', A::class)
                    ->wire('param2', B::class))
        );
        $this->assertTrue($container->get(C::class) instanceof C);
    }

    public function testConfig(): void
    {
        $container = $this->container(
            Config::init()
                ->setup(D::class, Config::params()
                    ->config('num', 1)
                    ->config('str', 'STR'))
        );
        /** @var D $obj */
        $obj = $container->get(D::class);
        $this->assertTrue($obj instanceof D);
        $this->assertEquals(1, $obj->num);
        $this->assertEquals('STR', $obj->str);
    }

    public function testWireAndConfig(): void
    {
        $container = $this->container(
            Config::init()
                ->setup(E::class, Config::params()
                    ->config('num', 1)
                    ->wire('field1', A::class))
        );
        /** @var E $obj */
        $obj = $container->get(E::class);
        $this->assertTrue($obj instanceof E);
        $this->assertEquals(1, $obj->num);
    }

    public function testProvider(): void
    {
        $container = $this->container(
            Config::init()
                ->provider(D::class, static function () {
                    return new D(1, 'STR');
                })
        );
        /** @var D $obj */
        $obj = $container->get(D::class);
        $this->assertTrue($obj instanceof D);
        $this->assertEquals(1, $obj->num);
        $this->assertEquals('STR', $obj->str);
    }

    public function testProviderWithParams(): void
    {
        $container = $this->container(
            Config::init()
                ->provider(E::class, static function (A $param) {
                    return new E(1, $param);
                })
        );
        /** @var E $obj */
        $obj = $container->get(E::class);
        $this->assertTrue($obj instanceof E);
        $this->assertEquals(1, $obj->num);
    }

    public function testFactory(): void
    {
        $container = $this->container(
            Config::init()
                ->factory(D::class, DFactory::class)
        );
        /** @var D $obj */
        $obj = $container->get(D::class);
        $this->assertTrue($obj instanceof D);
        $this->assertEquals(1, $obj->num);
        $this->assertEquals('STR', $obj->str);
    }

    public function testFactoryWithParamsInConstructorAndInvoke(): void
    {
        $container = $this->container(
            Config::init()
                ->factory(C::class, CFactory::class)
                ->bind(I1::class, A::class)
                ->bind(I2::class, B::class)
        );
        $this->assertTrue($container->get(C::class) instanceof C);
    }

    public function testSingletonOnInterfaces(): void
    {
        $container = $this->container(
            Config::init()
                ->bind(I1::class, F::class)
        );
        /** @var F $obj1 */
        $obj1 = $container->get(I1::class);
        /** @var F $obj2 */
        $obj2 = $container->get(I1::class);
        $obj2->num = 1;
        $this->assertEquals(1, $obj1->num);
    }

    public function testSingletonOnInterfacesAndClasses(): void
    {
        $container = $this->container(
            Config::init()
                ->bind(I1::class, F::class)
        );
        /** @var F $obj1 */
        $obj1 = $container->get(I1::class);
        /** @var F $obj2 */
        $obj2 = $container->get(F::class);
        $obj2->num = 1;
        $this->assertEquals(1, $obj1->num);
    }

    public function testOverride(): void
    {
        $container = $this->container(
            Config::init()
                ->bind(I1::class, A::class)
                ->bind(I1::class, F::class)
        );
        $obj = $container->get(I1::class);
        $this->assertTrue($obj instanceof  F);
    }

    private function container(?ConfigBuilder $config = null): ContainerInterface
    {
        return Container::create($config);
    }
}
