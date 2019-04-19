<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Company Entity
 *
 * @property int $id
 * @property string $name
 * @property int $state_id
 * @property \Cake\I18n\FrozenDate $financial_year_begins_from
 * @property \Cake\I18n\FrozenDate $books_beginning_from
 * @property string $address
 * @property string $phone_no
 * @property string $mobile
 * @property string $fax_no
 * @property string $email
 * @property string $gstin
 * @property string $pan
 *
 * @property \App\Model\Entity\State $state
 * @property \App\Model\Entity\CompanyUser[] $company_users
 */
class Company extends Entity
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
