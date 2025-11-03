<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レビューが承認されました</title>
    <style>
        body {
            font-family: 'Hiragino Sans', 'Hiragino Kaku Gothic ProN', 'Yu Gothic', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 0;
        }
        .header {
            background-color: #10b981;
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            margin-bottom: 20px;
        }
        .success-box {
            background-color: #d1fae5;
            border: 2px solid #10b981;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .success-box h2 {
            margin: 0 0 10px 0;
            color: #10b981;
            font-size: 20px;
        }
        .review-details {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .stars {
            color: #fbbf24;
            font-size: 20px;
            margin: 10px 0;
        }
        .review-content {
            background-color: #ffffff;
            border-left: 3px solid #10b981;
            padding: 15px;
            margin: 15px 0;
            font-style: italic;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background-color: #10b981;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px 30px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .footer-divider {
            border-top: 1px solid #e5e7eb;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>みんなの宣伝部</h1>
        </div>

        <div class="content">
            <div class="greeting">
                <p>{{ $review->customer->name }} 様</p>
            </div>

            <div class="success-box">
                <h2>レビューが承認されました</h2>
                <p style="margin: 0; font-size: 16px;">ご投稿いただいたレビューが公開されました。</p>
            </div>

            <p>この度は「{{ $review->shop->name }}」へのレビューをご投稿いただき、誠にありがとうございます。</p>
            <p>審査の結果、お客様のレビューが承認され、サイトに公開されました。</p>

            <div class="review-details">
                <h2 style="margin-top: 0; color: #10b981;">投稿内容</h2>
                <p style="margin: 5px 0;"><strong>店舗名：</strong>{{ $review->shop->name }}</p>
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $review->rating)
                            &#9733;
                        @else
                            &#9734;
                        @endif
                    @endfor
                    <span style="color: #6b7280; font-size: 16px; margin-left: 10px;">{{ $review->rating }}.0</span>
                </div>
                @if($review->comment)
                <div class="review-content">
                    {{ $review->comment }}
                </div>
                @endif
                <p style="margin: 10px 0 0; color: #6b7280; font-size: 14px;">
                    投稿日：{{ $review->created_at->format('Y年m月d日') }}
                </p>
            </div>

            <p>あなたのレビューは、他のお客様が店舗を選ぶ際の貴重な情報となります。<br>
            ご協力いただき、ありがとうございました。</p>

            <div class="button-container">
                <a href="{{ url('/shops/' . $review->shop_id) }}" class="button">店舗ページを見る</a>
            </div>

            <div class="footer-divider"></div>

            <p style="color: #6b7280; font-size: 14px;">
                今後もぜひ、ご利用いただいた店舗のレビューをお寄せください。<br>
                このメールに心当たりがない場合は、お手数ですが削除をお願いいたします。
            </p>
        </div>

        <div class="footer">
            <p style="margin: 0;">みんなの宣伝部</p>
            <p style="margin: 5px 0 0;">お問い合わせ：support@minna-sendenbu.jp</p>
        </div>
    </div>
</body>
</html>
