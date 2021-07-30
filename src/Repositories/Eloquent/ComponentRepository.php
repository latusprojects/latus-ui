<?php


namespace Latus\UI\Repositories\Eloquent;

use Illuminate\Contracts\Container\BindingResolutionException;
use Latus\Settings\Models\Setting;
use Latus\Settings\Services\SettingService;
use Latus\UI\Components\Contracts\ModuleComponent;
use Latus\UI\Repositories\Contracts\ComponentRepository as ComponentRepositoryContract;

class ComponentRepository implements ComponentRepositoryContract
{

    protected static array $definedModules;

    public function __construct(
        protected SettingService $settingService
    )
    {

        if (!self::$definedModules) {
            self::$definedModules = [];
        }
    }

    public function defineModule(string $moduleContract)
    {
        if (!in_array($moduleContract, self::$definedModules)) {
            self::$definedModules[] = $moduleContract;
        }
    }

    public function provideWidget(string $widgetClass, string $widgetName)
    {
        if (!app()->bound($widgetName)) {
            app()->bind($widgetName, $widgetClass);
        }
    }

    protected function createModuleBinding(string $moduleContract, string $moduleClass): ModuleComponent|null
    {

        try {
            /**
             * @var ModuleComponent $moduleInstance
             */
            $moduleInstance = app()->make($moduleClass);
            $moduleInstance->compose();

            app()->singleton($moduleContract, $moduleInstance);

            return $moduleInstance;

        } catch (BindingResolutionException $e) {
            return null;
        }
    }

    public function getActiveModule(string $moduleContract): ModuleComponent|bool|null
    {

        $activeModules = $this->getActiveModules();

        if (in_array($moduleContract, $this->getDisabledModules()) || !isset($activeModules[$moduleContract])) {
            return false;
        }

        try {
            $moduleInstance = app()->make($moduleContract);

            if (get_class($moduleInstance) === $activeModules[$moduleContract]) {
                return $moduleInstance;
            }

            return $this->createModuleBinding($moduleContract, $activeModules[$moduleContract]);

        } catch (BindingResolutionException $e) {
            return null;
        }

    }


    public function disableModule(string $moduleContract)
    {
        /**
         * @var Setting $setting
         */
        $setting = $this->settingService->findByKey('disabled_modules');
        $disabledModules = unserialize($setting->getValue());

        if (!in_array($moduleContract, $disabledModules)) {
            $disabledModules[] = $moduleContract;
            $this->settingService->setSettingValue($setting, serialize($disabledModules));
        }
    }

    public function enableModule(string $moduleContract)
    {
        /**
         * @var Setting $setting
         */
        $setting = $this->settingService->findByKey('disabled_modules');
        $disabledModules = unserialize($setting->getValue());
        if ($key = array_search($moduleContract, $disabledModules)) {

            unset($disabledModules[$key]);
            $this->settingService->setSettingValue($setting, serialize($disabledModules));
        }
    }

    public function getDisabledModules(): array
    {
        /**
         * @var Setting $setting
         */
        $setting = $this->settingService->findByKey('disabled_modules');
        return unserialize($setting->getValue());
    }

    public function getActiveModules(): array
    {
        /**
         * @var Setting $setting
         */
        $setting = $this->settingService->findByKey('active_modules');
        return unserialize($setting->getValue());
    }
}