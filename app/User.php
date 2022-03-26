<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\PasswordResetNotification;
use App\Mail\BareMail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token, new BareMail()));
    }

    public function articles(): HasMany
    {
        return $this->hasMany('App\Article');
    }

    public function likes(): BelongsToMany
    {
        // likesテーブルはuserとarticleの中間テーブル
        return $this->belongsToMany('App\Article', 'likes')->withTimestamps();
    }

    // followsテーブルは、userとuserの中間テーブル
    // フォローされている側の人(followee)を軸に、フォローしている側の人(follower)を引っ張ってくる
    public function followers(): BelongsToMany
    {
        // 中間テーブルのカラム名が、リレーション元/先のテーブル名の単数形_idではないので、引数は省略不可。
        return $this->belongsToMany('App\User', 'follows', 'followee_id', 'follower_id')->withTimestamps();
    }

    // フォローしている側の人(follower)を軸に、フォローされている側の人(followee)を引っ張ってくる
    public function followings(): BelongsToMany
    {
        return $this->belongsToMany('App\User', 'follows', 'follower_id', 'followee_id')->withTimestamps();
    }

    // 引数のユーザーにフォローされているかどうか判定
    public function isFollowedBy(?User $user): bool
    {
        return $user
            ? (bool)$this->followers->where('id', $user->id)->count()
            : false;
    }

    // フォロワー人数取得
    public function getCountFollowersAttribute(): int
    {
        return $this->followers->count();
    }

    // フォロー人数取得
    public function getCountFollowingsAttribute(): int
    {
        // コレクション, クエリビルダ
        // dd($this->followings, $this->followings());

        return $this->followings->count();
    }
}
