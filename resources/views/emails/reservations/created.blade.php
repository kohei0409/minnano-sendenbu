<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ご予約を承りました</title>
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
            background-color: #2563eb;
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
        .reservation-details {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: bold;
            color: #6b7280;
            display: inline-block;
            width: 120px;
        }
        .detail-value {
            color: #111827;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background-color: #2563eb;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }
        .notice {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
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
                <p>{{ $reservation->customer_name }} 様</p>
                <p>この度は、「{{ $reservation->shop->name }}」のご予約をいただき、誠にありがとうございます。</p>
                <p>以下の内容でご予約を承りました。</p>
            </div>

            <div class="reservation-details">
                <h2 style="margin-top: 0; color: #2563eb;">予約詳細</h2>
                <div class="detail-row">
                    <span class="detail-label">店舗名：</span>
                    <span class="detail-value">{{ $reservation->shop->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">予約日時：</span>
                    <span class="detail-value">{{ $reservation->reservation_date->format('Y年m月d日 H:i') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">人数：</span>
                    <span class="detail-value">{{ $reservation->number_of_people }}名</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">お名前：</span>
                    <span class="detail-value">{{ $reservation->customer_name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">電話番号：</span>
                    <span class="detail-value">{{ $reservation->customer_phone }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">メールアドレス：</span>
                    <span class="detail-value">{{ $reservation->customer_email }}</span>
                </div>
                @if($reservation->notes)
                <div class="detail-row">
                    <span class="detail-label">備考：</span>
                    <span class="detail-value">{{ $reservation->notes }}</span>
                </div>
                @endif
            </div>

            <div class="notice">
                <strong>ご注意：</strong>
                <p style="margin: 5px 0 0;">店舗からの確認連絡をお待ちください。予約の確定は店舗からの連絡をもって完了となります。</p>
            </div>

            <div class="button-container">
                <a href="{{ url('/customer/reservations') }}" class="button">予約一覧を見る</a>
            </div>

            <div class="footer-divider"></div>

            <p style="color: #6b7280;">
                ご不明な点がございましたら、店舗まで直接お問い合わせください。<br>
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
