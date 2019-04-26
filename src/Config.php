<?php declare(strict_types=1);

namespace Omar\DependencyInjection;

use Closure;
use Omar\DependencyInjection\Configuration\ConfigBuilder;
use Omar\DependencyInjection\Configuration\ConfigurationAssembler;
use Omar\DependencyInjection\Configuration\ParamSetupConfiguration;
use Omar\DependencyInjection\Configuration\Setting;
use Omar\DependencyInjection\Setup\Bind;
use Omar\DependencyInjection\Setup\Factory;
use Omar\DependencyInjection\Setup\ParamSetupCollection;
use Omar\DependencyInjection\Setup\Provider;
use Omar\DependencyInjection\Setup\SettingCollection;
use Omar\DependencyInjection\Setup\Setup;

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
