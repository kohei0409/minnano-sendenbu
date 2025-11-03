<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新しいレビューが投稿されました</title>
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
            background-color: #7c3aed;
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
        .alert-box {
            background-color: #ede9fe;
            border: 2px solid #7c3aed;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .alert-box h2 {
            margin: 0 0 10px 0;
            color: #7c3aed;
            font-size: 20px;
        }
        .review-details {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .customer-info {
            background-color: #ffffff;
            border-left: 3px solid #7c3aed;
            padding: 15px;
            margin: 15px 0;
        }
        .stars {
            color: #fbbf24;
            font-size: 24px;
            margin: 10px 0;
        }
        .review-content {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
            font-style: italic;
        }
        .status-pending {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background-color: #7c3aed;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>みんなの宣伝部</h1>
        </div>

        <div class="content">
            <div class="greeting">
                <p>{{ $review->shop->name }} 様</p>
            </div>

            <div class="alert-box">
                <h2>新しいレビューが投稿されました</h2>
                <p style="margin: 0; font-size: 16px;">お客様から新しいレビューが投稿されました。</p>
            </div>

            <p>お客様から貴店へのレビューが投稿されました。<br>
            内容をご確認いただき、承認または却下の対応をお願いいたします。</p>

            <div class="review-details">
                <h2 style="margin-top: 0; color: #7c3aed;">レビュー内容</h2>

                <div class="customer-info">
                    <p style="margin: 5px 0;"><strong>投稿者：</strong>{{ $review->customer->name }}</p>
                    <p style="margin: 5px 0;"><strong>投稿日時：</strong>{{ $review->created_at->format('Y年m月d日 H:i') }}</p>
                </div>

                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $review->rating)
                            &#9733;
                        @else
                            &#9734;
                        @endif
                    @endfor
                    <span style="color: #6b7280; font-size: 18px; margin-left: 10px;">{{ $review->rating }}.0 / 5.0</span>
                </div>

                @if($review->comment)
                <div>
                    <p style="margin: 10px 0 5px; font-weight: bold; color: #6b7280;">コメント：</p>
                    <div class="review-content">
                        {{ $review->comment }}
                    </div>
                </div>
                @else
                <p style="color: #6b7280; font-style: italic;">※ コメントは投稿されていません</p>
                @endif
            </div>

            <div class="status-pending">
                <strong>ステータス：審査待ち</strong>
                <p style="margin: 5px 0 0;">このレビューは現在審査待ちの状態です。承認されるまで一般には公開されません。</p>
            </div>

            <p>レビュー管理画面から、このレビューを承認または却下することができます。<br>
            お客様の貴重なご意見として、適切にご対応くださいますようお願いいたします。</p>

            <div class="button-container">
                <a href="{{ url('/shop/reviews') }}" class="button">レビュー管理画面へ</a>
            </div>

            <div style="border-top: 1px solid #e5e7eb; margin: 30px 0; padding-top: 20px;">
                <h3 style="color: #7c3aed; margin-top: 0;">レビュー対応のポイント</h3>
                <ul style="color: #6b7280;">
                    <li>速やかにレビュー内容を確認してください</li>
                    <li>ガイドラインに沿った内容であれば承認をお願いします</li>
                    <li>不適切な内容の場合は、理由を明記して却下してください</li>
                    <li>良いレビューはお店の信頼性向上に繋がります</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p style="margin: 0;">みんなの宣伝部</p>
            <p style="margin: 5px 0 0;">お問い合わせ：support@minna-sendenbu.jp</p>
        </div>
    </div>
</body>
</html>
