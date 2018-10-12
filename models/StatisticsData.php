<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "StatisticsData".
 *
 * @property int $id
 * @property int $TestTypeId
 * @property string $RegionId
 * @property string $DistrictId
 * @property double $Latitude
 * @property double $Longitude
 * @property int $CultureId
 * @property int $HybridId
 * @property double $ProductivityStandardHumidity
 * @property double $HumidityHarvesting
 * @property int $Year
 */
class StatisticsData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'StatisticsData';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['TestTypeId', 'RegionId', 'DistrictId', 'Latitude', 'Longitude', 'CultureId', 'HybridId', 'ProductivityStandardHumidity', 'HumidityHarvesting'], 'required'],
            [['TestTypeId', 'CultureId', 'HybridId', 'Year'], 'integer'],
            [['Latitude', 'Longitude', 'ProductivityStandardHumidity', 'HumidityHarvesting'], 'number'],
            [['RegionId', 'DistrictId'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'TestTypeId' => 'Test Type ID',
            'RegionId' => 'Region ID',
            'DistrictId' => 'District ID',
            'Latitude' => 'Latitude',
            'Longitude' => 'Longitude',
            'CultureId' => 'Culture ID',
            'HybridId' => 'Hybrid ID',
            'ProductivityStandardHumidity' => 'Productivity Standard Humidity',
            'HumidityHarvesting' => 'Humidity Harvesting',
            'Year' => 'Year',
        ];
    }
}
