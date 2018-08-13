<?php

namespace Itstructure\MultiMenu;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Itstructure\MultiMenu\exceptions\InvalidConfigException;

/**
 * Class MultiMenuWidget
 *
 * @package Itstructure\MultiMenu
 *
 * @author Andrey Girnik <girnikandrey@gmail.com>
 */
class MultiMenuWidget {

    /**
     * Primary key name.
     *
     * @var string
     */
    private $primaryKeyName = 'id';

    /**
     * Relation key name.
     *
     * @var string
     */
    private $parentKeyName = 'parentId';

    /**
     * Main container template to display widget elements.
     *
     * @var string|array
     */
    private $mainTemplate = 'main';

    /**
     * Item container template to display widget elements.
     *
     * @var string|array
     */
    private $itemTemplate = 'item';

    /**
     * Addition cross cutting data.
     *
     * @var array
     */
    private $additionData = [];

    /**
     * MultiMenuWidget constructor.
     */
    public function __construct()
    {
        $this->setAttributes(config('multiMenu'));
    }

    /**
     * Starts the output widget of the multi level view records according with the menu type.
     *
     * @param Collection $data
     * @param array $additionData
     *
     * @return string
     */
    public function run(Collection $data, array $additionData = []): string
    {
        $this->additionData = $additionData;

        return $this->renderItems($this->groupLevels($data));
    }

    /**
     * Check whether a particular record can be used as a parent.
     *
     * @param Model $mainModel
     * @param int $newParentId
     * @param string $primaryKeyName
     * @param string $parentKeyName
     *
     * @return bool
     */
    public static function checkNewParentId(
        Model $mainModel,
        int $newParentId,
        string $primaryKeyName = 'id',
        string $parentKeyName = 'parentId'
    ): bool {

        $parentRecord = $mainModel::where($primaryKeyName, $newParentId)
            ->select($primaryKeyName, $parentKeyName)
            ->first();

        if ($mainModel->{$primaryKeyName} === $parentRecord->{$primaryKeyName}) {
            return false;
        }

        if (null === $parentRecord->{$parentKeyName}) {
            return true;
        }

        return static::checkNewParentId($mainModel, $parentRecord->{$parentKeyName});
    }

    /**
     * Reassigning child objects to their new parent after delete the main model record.
     *
     * @param Model $mainModel
     * @param string $primaryKeyName
     * @param string $parentKeyName
     *
     * @return void
     */
    public static function afterDeleteMainModel(
        Model $mainModel,
        string $primaryKeyName = 'id',
        string $parentKeyName = 'parentId'
    ): void {

        $mainModel::where($parentKeyName, $mainModel->{$primaryKeyName})
            ->update([
                $parentKeyName => $mainModel->{$parentKeyName}
            ]);
    }

    /**
     * @param array $config
     *
     * @return void
     */
    private function setAttributes(array $config): void
    {
        foreach ($config as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * Group records in to sub levels according with the relation to parent records.
     *
     * @param Collection $models
     *
     * @return array
     *
     * @throws InvalidConfigException
     */
    private function groupLevels(Collection $models): array
    {
        if (!is_string($this->parentKeyName) || empty($this->primaryKeyName)) {
            throw new InvalidConfigException('The parent key name is nod defined correctly.');
        }

        if (!is_string($this->primaryKeyName) || empty($this->primaryKeyName)) {
            throw new InvalidConfigException('The primary key name is nod defined correctly.');
        }

        if ($models->count() == 0) {
            return [];
        }

        $items = [];

        foreach ($models as $model) {
            $items[$model->{$this->primaryKeyName}]['data'] = $model;
        }

        foreach($items as $row) {
            $data = $row['data'];
            $parentKey = !isset($data->{$this->parentKeyName}) || empty($data->{$this->parentKeyName}) ?
                0 : $data->{$this->parentKeyName};
            $items[$parentKey]['items'][$data->{$this->primaryKeyName}] = &$items[$data->{$this->primaryKeyName}];
        }

        return $items[0]['items'];
    }

    /**
     * Base render.
     *
     * @param array $items
     * @param int $level
     *
     * @return string
     */
    private function renderItems(array $items, int $level = 0): string
    {
        if (count($items) == 0) {
            return '';
        }

        $itemsContent = '';

        /** @var array $item */
        foreach ($items as $item) {

            $itemsContent .= view('multiMenuWidget::'.$this->levelAttributeValue($this->itemTemplate, $level),
                array_merge([
                    'data' => $item['data']
                ], $this->levelAttributeValue($this->additionData, $level))
            );

            if (isset($item['items'])) {
                $itemsContent .= $this->renderItems($item['items'], $level + 1);
            }
        }

        return view('multiMenuWidget::'.$this->levelAttributeValue($this->mainTemplate, $level), array_merge([
                'items' => $itemsContent,
                'level' => $level
            ], $this->levelAttributeValue($this->additionData, $level))
        );
    }

    /**
     * Get attribute values in current level.
     *
     * @param string|array $attributeValue
     * @param int $level
     *
     * @throws InvalidConfigException
     *
     * @return mixed
     */
    private function levelAttributeValue($attributeValue, int $level)
    {
        if (is_string($attributeValue)) {
            return $attributeValue;
        }

        if (is_array($attributeValue) && !isset($attributeValue['levels'])) {
            return $attributeValue;
        }

        if (is_array($attributeValue) && isset($attributeValue['levels'])) {

            $countLevels = count($attributeValue['levels']);

            if ($countLevels == 0) {
                throw new InvalidConfigException('Level values are not defined for attribute.');
            }

            return isset($attributeValue['levels'][$level]) ?
                $attributeValue['levels'][$level] : $attributeValue['levels'][($countLevels-1)];
        }

        throw new InvalidConfigException('Attribute is not defined correctly.');
    }
}
