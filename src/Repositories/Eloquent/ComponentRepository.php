<?php


namespace Latus\UI\Repositories\Eloquent;

use Illuminate\Contracts\Container\BindingResolutionException;
use Latus\Laravel\Http\Middleware\BuildPackageDependencies;
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

        if (!isset($this->{'definedModules'})) {
            self::$definedModules = [];
        }
    }

    public function getModuleInfo(string $moduleContract): array
    {

        $info = self::$definedModules[$moduleContract];

        if (!isset($info['alias'])) {
            $parts = explode('\\', $moduleContract);
            $info['alias'] = strtolower($parts[sizeof($parts) - 1]);
        }

        return $info;

    }

    public function defineModule(string $moduleContract, array $info)
    {
        if (!isset($moduleContract, self::$definedModules)) {
            self::$definedModules[$moduleContract] = $info;
        }
    }

    public function provideWidget(string $widgetClass, string $widgetName)
    {
        BuildPackageDependencies::addDependencyClosure(function () use ($widgetClass, $widgetName) {
            if (!app()->bound($widgetName)) {
                app()->bind($widgetName, $widgetClass);
            }
        });
    }

    public function createModuleBinding(string $moduleContract, string $moduleClass): ModuleComponent|null
    {

        try {
            /**
             * @var ModuleComponent $moduleInstance
             */
            $moduleInstance = app()->make($moduleClass);
            $moduleInstance->compose();

            app()->instance($moduleContract, $moduleInstance);

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
        $disabledModules = json_decode($setting->getValue(), true);

        if (!in_array($moduleContract, $disabledModules)) {
            $disabledModules[] = $moduleContract;
            $this->settingService->setSettingValue($setting, json_encode($disabledModules));
        }
    }

    public function enableModule(string $moduleContract)
    {
        /**
         * @var Setting $setting
         */
        $setting = $this->settingService->findByKey('disabled_modules');
        $disabledModules = json_decode($setting->getValue(), true);
        if ($key = array_search($moduleContract, $disabledModules)) {

            unset($disabledModules[$key]);
            $this->settingService->setSettingValue($setting, json_encode($disabledModules));
        }
    }

    public function getDisabledModules(): array
    {
        /**
         * @var Setting $setting
         */
        $setting = $this->settingService->findByKey('disabled_modules');
        return json_decode($setting->getValue(), true);
    }

    public function getActiveModules(): array
    {
        /**
         * @var Setting $setting
         */
        $setting = $this->settingService->findByKey('active_modules');
        return json_decode($setting->getValue(), true);
    }
}