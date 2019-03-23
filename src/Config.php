<?php declare(strict_types=1);

namespace Lencse\Omar\DependencyInjection;

use Closure;
use Lencse\Omar\DependencyInjection\Configuration\ConfigBuilder;
use Lencse\Omar\DependencyInjection\Configuration\ConfigurationAssembler;
use Lencse\Omar\DependencyInjection\Configuration\ParamSetupConfiguration;
use Lencse\Omar\DependencyInjection\Configuration\Setting;
use Lencse\Omar\DependencyInjection\Setup\Bind;
use Lencse\Omar\DependencyInjection\Setup\Factory;
use Lencse\Omar\DependencyInjection\Setup\ParamSetupCollection;
use Lencse\Omar\DependencyInjection\Setup\Provider;
use Lencse\Omar\DependencyInjection\Setup\SettingCollection;
use Lencse\Omar\DependencyInjection\Setup\Setup;

final class Config implements ConfigBuilder
{
    /** @var SettingCollection */
    private $settings;

    public static function init(): ConfigBuilder
    {
        return new self(new SettingCollection());
    }

    public static function params(): ParamSetupConfiguration
    {
        return new ParamSetupCollection();
    }

    private function __construct(SettingCollection $settings)
    {
        $this->settings = $settings;
    }

    public function apply(ConfigurationAssembler $assembler): void
    {
        $this->settings->apply($assembler);
    }

    private function add(Setting $setting): ConfigBuilder
    {
        return new self($this->settings->add($setting));
    }

    public function bind(string $target, string $className): ConfigBuilder
    {
        return $this->add(new Bind($target, $className));
    }

    public function setup(string $target, ParamSetupConfiguration $paramSetup): ConfigBuilder
    {
        return $this->add(new Setup($target, $paramSetup));
    }

    public function provider(string $target, Closure $provider): ConfigBuilder
    {
        return $this->add(new Provider($target, $provider));
    }

    public function factory(string $target, string $factoryClassName): ConfigBuilder
    {
        return $this->add(new Factory($target, $factoryClassName));
    }
}
