<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Tag;
use App\Http\Requests\ArticleRequest;

class ArticleController extends Controller
{
    public function __construct()
    {
        // ポリシーを使用
        // モデルのクラス名とそのモデルのIDがセットされる、ルーティングのパラメータ名をセット。
        $this->authorizeResource(Article::class, 'article');
    }

    public function index()
    {
        $articles = Article::all()->sortByDesc('created_at');
        // dd($articles->first()->user());

        return view('articles.index', ['articles' => $articles]);
    }

    public function create()
    {
        // 全てのタグ情報を取得
        $allTagNames = Tag::all()->map(function ($tag)
        {
            return ['text' => $tag->name];
        });

        return view('articles.create', [
            'allTagNames' => $allTagNames,
        ]);
    }

    public function store(ArticleRequest $request, Article $article)
    {
        // dd($request, $article->user()->first());
        // $article->title = $request->title;
        // $article->body = $request->body;
        // Articleモデルの$fillableに記述することで、それ以外のパラメーターも含んだ不正なリクエストが送信される可能性があるので、この書き方により、それを制限。
        $article->fill($request->all());
        $article->user_id = $request->user()->id;
        $article->save();

        // $request->tagsは、ArticleRequestでコレクションになっている。
        // クロージャの第一引数にはコレクションの値が、第二引数にはコレクションのキーが入ります。今回は第二引数は省略。use ($article)はクロージャの中の処理で変数$articleを使うため記載する必要がある。
        $request->tags->each(function ($tagName) use ($article)
        {
            // firstOrCreateメソッドは、引数として渡した「カラム名と値のペア」を持つレコードがテーブルに存在するかどうかを探し、もし存在すればそのモデルを返します。テーブルに存在しなければ、そのレコードをテーブルに保存した上で、モデルを返します。
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            // 変数$tagにはタグモデルが代入。以下で記事とタグの紐付け(article_tagテーブルへのレコードの保存)が行われます。
            $article->tags()->attach($tag);
        });

        return redirect()->route('articles.index');
    }
    // クラス(ここではArticleクラス)に依存している
    // public function store(ArticleRequest $request) //-- ArticleクラスのDIを行わない
    // {
    //     $article = new Article(); //-- storeアクションメソッド内でArticleクラスのインスタンスを生成している

    //     //-- 以降の処理は同じ
    //     $article->title = $request->title;
    //     $article->body = $request->body;
    //     $article->user_id = $request->user()->id;
    //     $article->save();
    //     return redirect()->route('articles.index');
    // }

    public function edit(Article $article)
    {
        // この記事に登録されているタグを全て取得。textをキーとした連想配列として取得。
        $tagNames = $article->tags->map(function ($tag)
        {
            // ArticleTagsInput.vueにtextキーがある。
            return ['text' => $tag->name];
        });
        // dd($tagNames);

        // 全てのタグ情報を取得
        $allTagNames = Tag::all()->map(function ($tag)
        {
            return ['text' => $tag->name];
        });

        // ['article' => $article->id]でも可
        return view('articles.edit', [
            'article' => $article,
            'tagNames' => $tagNames,
            'allTagNames' => $allTagNames,
        ]);
    }

    public function update(ArticleRequest $request, Article $article)
    {
        // dd($article->fill($request->all()));
        $article->fill($request->all())->save();
        // dd($request->tags);
        // dd($article->tags());

        // detachメソッドを引数無しで使うと、そのリレーションを紐付ける中間テーブルのレコードが全削除される。
        $article->tags()->detach();

        $request->tags->each(function ($tagName) use ($article)
        {
            // 引数の値を登録し、モデルを返す。登録済みならモデルのみ返す
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            // dd($tag);
            // 中間テーブルに登録
            $article->tags()->attach($tag);
        });

        return redirect()->route('articles.index');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('articles.index');
    }

    public function show(Article $article)
    {
        return view('articles.show', ['article' => $article]);
    }

    public function like(Request $request, Article $article)
    {
        // 多対多のリレーションでは、attachメソッドとdetachメソッドが使用できる。
        // attachの逆。
        // 既にいいね済みの記事にいいねをした場合、既存のいいねが削除されて改めていいねが登録されるので、結果としていいねの数は変わらず、二重にいいねされることを防止する。Vue側でも対策するが、不正なリクエストが行うことも可能なので、サーバー側でも対策する。
        $article->likes()->detach($request->user()->id);
        // この記事モデルと、リクエストを送信したユーザーのユーザーモデルの両者を紐づけるlikesテーブルのレコードが新規登録される。
        $article->likes()->attach($request->user()->id);

        // Laravelでは、コントローラーのアクションメソッドで配列や連想配列を返すと、JSON形式に変換されてレスポンスされる。
        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }

    public function unlike(Request $request, Article $article)
    {
        $article->likes()->detach($request->user()->id);

        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }
}
