<?php

namespace App\Models;

use App\Helper\HasPermissionsTrait;
use AppHelper;
use \DateTimeInterface;
use Carbon\Carbon;
use Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements MustVerifyEmail
{
    use SoftDeletes;
    use Notifiable;
    use HasFactory;
    use HasPermissionsTrait;

    public $table = 'users';

    protected $hidden = [
        'remember_token',
        'password',
    ];

    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
        'expired_at',
        'date_of_birth',
    ];

    protected $fillable = [
        'teacher_id',
        'role_id',
        'company_id',
        'package_id',
        'is_verified',
        'first_name',
        'last_name',
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'country_id',
        'subject_id',
        'hourly_rate',
        'phone_number',
        'date_of_birth',
        'photo',
        'slug',
        'introduce_yourself',
        'describe_teaching_experience',
        'trial_lesson',
        'video_link',
        'timezone',
        'teaching_certificate',
        'account_type',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];

    const DAYS = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday'
    ];

    const LEVELS = [
        'A1' => 'A1',
        'A2' => 'A2',
        'B1' => 'B1',
        'B2' => 'B2',
        'C1' => 'C1',
        'Native' => 'Native'
    ];

    const ADMININSTRATOR        = 1;
    const MANAGER               = 2;
    const COMPANY_ADMIN         = 3;
    const COMPANY_MANAGER       = 4;
    const INDEPENDENT_TEACHER   = 5;
    const COMPANY_EDITOR        = 6;
    const COMPANY_TEACHER       = 7;
    const COMPANY_SUPPORTER     = 8;

    protected $casts = [
        'is_verified'=> 'boolean',
        'has_qualification'=> 'boolean',
    ];

    protected $appends = ['full_name','country_code','phone_no'];

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->teacher_id)) {
                $user->teacher_id = AppHelper::generateNumber();
            }
        });
    }
    public function getProfileTypeAttribute()
    {
        if ($this->role_id == self::ADMININSTRATOR) {
            return "Administrator";
        }
        else if ($this->role_id == self::COMPANY_ADMIN) {
            return "Company";
        }
        else if ($this->role_id == self::INDEPENDENT_TEACHER) {
            return "Individual";
        }
    }
    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' '. $this->last_name;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getPermissions()
    {
        $this->loadMissing('role.permissions');

        return $this->role->permissions->unique('name');
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function tutorToCategory()
    {
        return $this->hasMany(TutorToCategory::class, 'user_id');
    }

    public function tutorToSubject()
    {
        return $this->hasMany(TutorToSubject::class, 'user_id');
    }

    public function timezone()
    {
        return $this->belongsTo(Timezone::class, 'timezone_id');
    }

    public function languageSpokens()
    {
        return $this->hasMany(UserLanguageSpoken::class, 'user_id');
    }

    public function teachingCertifications()
    {
        return $this->hasMany(TeachingCertification::class, 'user_id');
    }

    public function lessonSchedules()
    {
        return $this->hasMany(LessonSchedule::class, 'user_id');
    }

    public function paymentInfo()
    {
        return $this->hasOne(UserPaymentInformation::class, 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
    public static function allTutors()
    {
        return self::where('role_id', 5)->select("*", DB::raw("CONCAT(users.first_name,' ',users.last_name) as full_name"))->get();
    }

    public function reviews()
    {
       return $this->hasMany(Review::class,'tutor_id')->where('status','Publish');
    }

    public function avgRating()
    {
        return $this->reviews()
        ->selectRaw('avg(rating) as aggregate, tutor_id')
        ->groupBy('tutor_id');
    }

    public function getAvgRatingAttribute()
    {
        if ( ! array_key_exists('avgRating', $this->relations)) {
        $this->load('avgRating');
        }

        $relation = $this->getRelation('avgRating')->first();

        return ($relation) ? $relation->aggregate : null;
    }

    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class,'user_id');
    }

    public function package()
    {
        return $this->belongsTo(UserPackage::class,'package_id');
    }

    public function getIsFeaturedAttribute()
    {
        $this->loadMissing('userSubscriptions');
        $relation = $this->getRelation( 'userSubscriptions' )
        ->where('type','fixed')
        ->where('expired_at','>',now())
        ->where('status','paid')
        // ->sortByDesc('expired_at')
        ->first();

        return !is_null($relation);
    }
    // public function getIsDueSubscriptionAttribute()
    // {
    //     if(!$this->relationLoaded('userSubscriptions')){
    //         $this->load('userSubscriptions');
    //     }
    //     $relation = $this->getRelation( 'userSubscriptions' )
    //     ->where('type','recurring')
    //     ->where('expired_at','>',now())
    //     ->where('status','paid')
    //     // ->sortByDesc('expired_at')
    //     ->first();

    //     return !is_null($relation);
    // }
    public function getIsElegibleTrialAttribute()
    {
        $this->loadMissing( 'userSubscriptions' );
        $relation = $this->getRelation( 'userSubscriptions' )->where('type','Trial')->first();

        return is_null($relation);
    }
    public function getIsDueSubscriptionAttribute()
    {
        return is_null($this->expired_at) || $this->expired_at < now();
    }

    public function getIsActiveSubscriptionAttribute()
    {
        if ($this->is_due_subscription) {
            return sprintf( '<span class="badge bg-danger">Inactive</span>' );
        }
        return sprintf( '<span class="badge bg-success">Active</span>' );
    }

    public function getRemainingTimeAttribute()
    {
        if (is_null($this->expired_at)) {
            return sprintf( '<span class="text-danger">Not Subscribed</span>' );
        }
        if (now()->diffInDays($this->expired_at, false) >= 1) {
            return sprintf('<span class="text-success">%d days</span>', now()->diffInDays($this->expired_at, false));
        } else {
            return sprintf('<span class="text-danger">Expired</span>');
        }
    }

    public function study_subject() {
        return $this->belongsToMany( StudySubject::class, 'study_tutor_subjects' ,'user_id', 'subject_id' )->withPivot('status');
    }

    public function getProfileProgressAttribute()
    {
        $this->loadMissing('teachingCertifications');
        $progress = 0;
        if( !empty($this->date_of_birth) ){
            $progress += 25;
        }
        if( !empty($this->company->address) ){
            $progress += 25;
        }
        if( $this->teachingCertifications->isNotEmpty() ){
            $progress += 25;
        }
        if( !empty($this->timezone) ){
            $progress += 15;
        }
        if( !empty($this->paymentInfo) ){
            $progress += 10;
        }

        return $progress;
    }

    public function getIsVideoFileAttribute()
    {
        return \Str::containsAll($this->video_link, ['youtube','vimeo']) == false;
    }

    public function schedulePayments()
    {
        return $this->hasMany(UserSchedulePayment::class,'user_id');
    }
    public function packagePayments()
    {
        return $this->hasMany(PackagePayment::class,'tutor_id');
    }


    public function metas()
    {
        return $this->hasMany(UserMeta::class,'user_id');
    }

    public function getMeta($key)
    {
        $this->loadMissing( 'metas' );
        $meta = $this->getRelation( 'metas' )->where('meta_key',$key)->first();
        return $meta ? $meta->meta_value : null;
    }
    public function getMetaDataAttribute()
    {
        $this->loadMissing( 'metas' );
        return $this->getRelation( 'metas' )->pluck( 'meta_value', 'meta_key' )->toArray();
    }
    public function getOffDaysAttribute()
    {
        $this->loadMissing( 'lessonSchedules' );

        $disableDays = [];
        if ($this->lessonSchedules->isNotEmpty()) {
            foreach ($this->lessonSchedules as $day) {
                if(!$day['is_active']){
                    $disableDays[] = array_search($day['day'], self::DAYS);
                }
            }
        }
        return $disableDays;
    }
    public function getOffDatesAttribute()
    {
        $this->loadMissing( 'metas' );
        $disabledates = $this->getMeta('individual_disable_dates');
        $disableDays = $disabledates ? array_map('trim',explode(',',$disabledates)) : [];
        // dump($disableDays);
        return $disableDays;
    }

    public function groupClasses()
    {
        return $this->hasMany(LiveClassSchedule::class,'user_id');
    }

    public function getParentTeacherIdAttribute()
    {
        if ($this->role_id == self::INDEPENDENT_TEACHER || $this->role_id == self::COMPANY_ADMIN) {
            return $this->id;
        } else {
            return $this->where( 'company_id', auth()->user()->company_id )->where('role_id',3)->first()->id;
        }
    }

    public function getTeamMembersAttribute()
    {
        return $this::where('company_id',$this->company_id);
    }

    public function subscriptionPayments()
    {
        return $this->hasManyThrough(Payment::class,UserSubscription::class,'user_id','paymentable_id','id','id');
    }
    public function getMaxTeamSizeAttribute()
    {
        $this->loadMissing( 'company' );
        return $this->company->team_size;
    }

    public function getHasMemberAddLimitAttribute()
    {
        return $this->team_size->count() < $this->max_team_size;
    }

    public function getOffDatesFormattedAttribute()
    {
        $offDates = $this->off_dates;
        $offDatesFormatted = [];
        if (empty($offDates)) {
            return 'No Off Days';
        }
        foreach ($offDates as $offDate) {
            $offDatesFormatted[] = date('d F', strtotime($offDate));
        }
        return implode(', ', $offDatesFormatted);
    }
    public function phoneCode()
    {
        return $this->belongsTo(Country::class,'phone_code_id');
    }
    public function getPhoneNumberWithCodeAttribute()
    {
        return explode('-', $this->phone_number,2);
    }
    public function getCountryCodeAttribute()
    {
        return $this->phone_number_with_code[0];
    }
    public function getPhoneNoAttribute()
    {
        return isset($this->phone_number_with_code[1]) ? $this->phone_number_with_code[1] : null;
    }
    public function getPhoneNoFormattedAttribute()
    {
        if (empty($this->phone_no)) {
            return null;
        }
        return "+" .explode('_',$this->country_code)[1]. " " .substr($this->phone_no, 0, 3)." ".substr($this->phone_no, 3, 3)." ".substr($this->phone_no,6);
    }
}
