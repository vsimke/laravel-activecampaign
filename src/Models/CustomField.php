<?php

declare(strict_types=1);

namespace Vsimke\ActiveCampaign\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * CustomField model.
 *
 * @property int $id
 * @property int $field_id
 * @property string $perstag
 * @property string $title
 * @property Carbon $cdate
 * @property Carbon $udate
 *
 * @author Vladimir Simic <vladimir.simic@prodevcon.ch>
 */
class CustomField extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'active_campaign_custom_fields';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'id',
        'field_id',
        'perstag',
        'title',
        'cdate',
        'udate',
    ];

    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected function casts(): array
    {
        return [
            'cdate' => 'datetime',
            'udate' => 'datetime',
        ];
    }
}
