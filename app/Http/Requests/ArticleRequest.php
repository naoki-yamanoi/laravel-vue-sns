<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:50',
            'body' => 'required|max:500',
            // ArticleTagsInputコンポーネントでJSON形式の文字列に変換された上でLaravelへリクエストされている。
            // /^(?!.*\s).+$/uは、PHPにおいて半角スペースが無いこと、/^(?!.*\/).*$/uは/が無いことをチェックする正規表現。
            'tags' => 'json|regex:/^(?!.*\s).+$/u|regex:/^(?!.*\/).*$/u',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'タイトル',
            'body' => '本文',
            'tags' => 'タグ',

        ];
    }

    // フォームリクエストのバリデーションが成功した後に自動的に呼ばれるメソッド。
    public function passedValidation()
    {
        // json_decode($this->tags)で、JSON形式の文字列であるタグ情報をPHPのjson_decode関数を使って連想配列に変換。さらにLaravelのcollect関数を使ってコレクションに変換。sliceメソッドやmapメソッドといった、便利なコレクションメソッドを使うため。
        $this->tags = collect(json_decode($this->tags))
            // slice(0, 5)とすると、もしコレクションの要素が6個以上あったとしても、最初の5個だけが残ります。タグ入力フォームに"タグを5個まで入力できます"と表示するようにしましたが、その対応をここで行なっています。
            ->slice(0, 5)
            // mapメソッドは、コレクションの各要素に対して順に処理を行い、新しいコレクションを作成します。引数に関数を渡すことができます。このような、引数に渡す関数のことをコールバックと呼びます。
            ->map(function ($requestTag)
            {
                return $requestTag->text;
            });
    }
}
