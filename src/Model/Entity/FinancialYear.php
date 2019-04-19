<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FinancialYear Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenDate $fy_from
 * @property \Cake\I18n\FrozenDate $fy_to
 * @property string $status
 * @property int $company_id
 */
class FinancialYear extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
