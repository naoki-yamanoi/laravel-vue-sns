<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    // モデルがこの属性以外持たない
    protected $fillable = [
        'title',
        'body',
    ];

    // 相手が一の場合、BelongsTo
    // : BelongsToで戻り値の型を指定。別の型を返そうとしたらエラー。
    public function user(): BelongsTo
    {
        // usersテーブルの主キーはid、articlesテーブルの外部キーは関連するテーブル名の単数形_id(つまりuser_id)であるという前提。
        return $this->belongsTo('App\User');
    }

    // 多対多の場合、BelongsToMany
    public function likes(): BelongsToMany
    {
        // 第二引数を省略すると、中間テーブル名は2つのモデル名の単数形をアルファベット順に結合した名前であるという前提で処理されてしまう(この場合article_userという中間テーブル)。しかし今回はlikesというテーブル名。
        return $this->belongsToMany('App\User', 'likes')->withTimestamps();
    }

    // 記事をいいね済みかどうかを判定
    public function isLikedBy(?User $user): bool
    {
        return $user
            // $userがnullでない場合
            // (bool)で戻り値はtrueかfalseのみ
            ? (bool)$this->likes->where('id', $user->id)->count()
            // $userがnullの場合
            : false;
    }

    // この記事にいいねをした全ユーザーモデルがコレクションで返る。
    // モデルに持たせるget...Attributeという形式の名前のメソッドをLaravelではアクセサ。
    // 実際にこのメソッドを使う時は、$article->count_likesとする。
    public function getCountLikesAttribute(): int
    {
        return $this->likes->count();
    }

    // 相手は多。
    public function tags(): BelongsToMany
    {
        // 第二引数は中間テーブル。今回は、article_tagという名前なので、省略可。
        return $this->belongsToMany('App\Tag')->withTimestamps();
    }
}
