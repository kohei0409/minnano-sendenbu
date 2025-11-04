<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * サポートされている言語
     */
    protected array $supportedLocales = ['ja', 'en'];

    /**
     * デフォルトの言語
     */
    protected string $defaultLocale = 'ja';

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. URLパラメータから言語を取得
        if ($request->has('lang')) {
            $locale = $request->input('lang');

            // サポートされている言語かチェック
            if (in_array($locale, $this->supportedLocales)) {
                // セッションに保存
                Session::put('locale', $locale);
                App::setLocale($locale);

                return $next($request);
            }
        }

        // 2. セッションから言語を取得
        if (Session::has('locale')) {
            $locale = Session::get('locale');

            if (in_array($locale, $this->supportedLocales)) {
                App::setLocale($locale);

                return $next($request);
            }
        }

        // 3. ブラウザの言語設定から取得
        $browserLocale = $this->getBrowserLocale($request);
        if ($browserLocale) {
            Session::put('locale', $browserLocale);
            App::setLocale($browserLocale);

            return $next($request);
        }

        // 4. デフォルト言語を使用
        App::setLocale($this->defaultLocale);

        return $next($request);
    }

    /**
     * ブラウザの言語設定から適切な言語を取得
     */
    protected function getBrowserLocale(Request $request): ?string
    {
        $acceptLanguage = $request->header('Accept-Language');

        if (!$acceptLanguage) {
            return null;
        }

        // Accept-Languageヘッダーをパース
        $languages = [];
        foreach (explode(',', $acceptLanguage) as $lang) {
            $parts = explode(';q=', $lang);
            $locale = trim($parts[0]);
            $priority = isset($parts[1]) ? (float) $parts[1] : 1.0;

            // 言語コードの最初の2文字を取得（ja-JP -> ja）
            $localeCode = substr($locale, 0, 2);

            if (in_array($localeCode, $this->supportedLocales)) {
                $languages[$localeCode] = $priority;
            }
        }

        // 優先度でソート
        arsort($languages);

        // 最も優先度の高い言語を返す
        return !empty($languages) ? array_key_first($languages) : null;
    }
}
