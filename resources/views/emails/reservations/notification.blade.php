<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規予約が入りました</title>
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
            background-color: #059669;
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
            background-color: #dcfce7;
            border: 2px solid #059669;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .alert-box h2 {
            margin: 0 0 10px 0;
            color: #059669;
            font-size: 20px;
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
            background-color: #059669;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>みんなの宣伝部</h1>
        </div>

        <div class="content">
            <div class="greeting">
                <p>{{ $reservation->shop->name }} 様</p>
            </div>

            <div class="alert-box">
                <h2>新規予約が入りました</h2>
                <p style="margin: 0; font-size: 16px;">至急、お客様への確認連絡をお願いいたします。</p>
            </div>

            <div class="reservation-details">
                <h2 style="margin-top: 0; color: #059669;">予約詳細</h2>
                <div class="detail-row">
                    <span class="detail-label">予約番号：</span>
                    <span class="detail-value">#{{ $reservation->id }}</span>
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
                    <span class="detail-label">お客様名：</span>
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
                <div class="detail-row">
                    <span class="detail-label">予約受付日時：</span>
                    <span class="detail-value">{{ $reservation->created_at->format('Y年m月d日 H:i') }}</span>
                </div>
            </div>

            <div class="notice">
                <strong>対応のお願い：</strong>
                <p style="margin: 5px 0 0;">お客様への確認連絡を速やかに行い、予約の確定または変更をお願いいたします。</p>
            </div>

            <div class="button-container">
                <a href="{{ url('/shop/reservations') }}" class="button">予約管理画面へ</a>
            </div>
        </div>

        <div class="footer">
            <p style="margin: 0;">みんなの宣伝部</p>
            <p style="margin: 5px 0 0;">お問い合わせ：support@minna-sendenbu.jp</p>
        </div>
    </div>
</body>
</html>
