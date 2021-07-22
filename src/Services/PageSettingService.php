<?php


namespace Latus\UI\Services;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Latus\UI\Models\PageSetting;
use Latus\UI\Repositories\Contracts\PageSettingRepository;

class PageSettingService
{

    public static array $create_validation_rules = [
        'key' => 'required|string|min:5',
        'module' => 'required|string|min:3',
        'page' => 'required|string|min:3',
        'value' => 'sometimes|string|max:255|nullable',
        'value_long' => 'required_if:value,null'
    ];

    public function __construct(
        protected PageSettingRepository $pageSettingRepository
    )
    {
    }

    /**
     * @param array $attributes
     * @return Model
     */
    public function createPageSetting(array $attributes): Model
    {
        $validator = Validator::make($attributes, self::$create_validation_rules);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        return $this->pageSettingRepository->create($attributes);
    }

    public function deleteSetting(PageSetting $setting)
    {
        $this->pageSettingRepository->delete($setting);
    }

    public function find(int|string $id): Model|null
    {
        return $this->pageSettingRepository->find($id);
    }

    public function findByKey(string $module, string $page, string $key): Model|null
    {
        return $this->pageSettingRepository->findByKey($module, $page, $key);
    }
}