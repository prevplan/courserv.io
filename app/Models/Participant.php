<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Participant
 *
 * @property int $id
 * @property int $course_id
 * @property int|null $contact_id
 * @property string $lastname
 * @property string $firstname
 * @property string $date_of_birth
 * @property string|null $company
 * @property string|null $street
 * @property string|null $zipcode
 * @property string|null $location
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $email_reminder
 * @property int $rating
 * @property string|null $payee
 * @property int $participated
 * @property string $price_net
 * @property string $price_gross
 * @property string $currency
 * @property string $payment
 * @property int $price_id
 * @property int $payed
 * @property string|null $transaction_id
 * @property int $cancelled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Course $course
 * @method static \Illuminate\Database\Eloquent\Builder|Participant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Participant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Participant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereCancelled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereEmailReminder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereParticipated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant wherePayed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant wherePayee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant wherePayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant wherePriceGross($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant wherePriceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant wherePriceNet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Participant whereZipcode($value)
 * @mixin \Eloquent
 */
class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'contact_id',
        'lastname',
        'firstname',
        'date_of_birth',
        'company',
        'street',
        'zipcode',
        'location',
        'phone',
        'email',
        'price_net',
        'price_gross',
        'currency',
        'payment',
        'price_id',
    ];

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function trainer(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TrainerDay::class);
    }
}
