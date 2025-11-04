# Google Maps API セットアップガイド

このガイドでは、みんなの宣伝部のWebサイトでGoogle Maps機能を有効にするための手順を説明します。

## 目次

1. [Google Cloud Platformでプロジェクトを作成](#1-google-cloud-platformでプロジェクトを作成)
2. [必要なAPIを有効化](#2-必要なapiを有効化)
3. [APIキーを作成](#3-apiキーを作成)
4. [APIキーに制限を設定](#4-apiキーに制限を設定)
5. [環境変数に設定](#5-環境変数に設定)
6. [動作確認](#6-動作確認)
7. [料金と使用制限について](#7-料金と使用制限について)
8. [トラブルシューティング](#8-トラブルシューティング)

---

## 1. Google Cloud Platformでプロジェクトを作成

1. [Google Cloud Console](https://console.cloud.google.com/) にアクセス
2. 右上の「プロジェクトを選択」をクリック
3. 「新しいプロジェクト」をクリック
4. プロジェクト名を入力（例：「minna-no-sendenbu」）
5. 「作成」をクリック

## 2. 必要なAPIを有効化

以下の2つのAPIを有効化する必要があります。

### Maps JavaScript API

1. Google Cloud Consoleで「APIとサービス」→「ライブラリ」を選択
2. 検索ボックスに「Maps JavaScript API」と入力
3. 「Maps JavaScript API」を選択
4. 「有効にする」ボタンをクリック

### Geocoding API（オプション、推奨）

店舗の緯度経度が登録されていない場合、住所から自動的に位置を取得するために使用します。

1. 検索ボックスに「Geocoding API」と入力
2. 「Geocoding API」を選択
3. 「有効にする」ボタンをクリック

## 3. APIキーを作成

1. Google Cloud Consoleで「APIとサービス」→「認証情報」を選択
2. 「認証情報を作成」→「APIキー」をクリック
3. APIキーが自動生成されます
4. 表示されたAPIキーをコピーして安全な場所に保存

> **重要**: APIキーは機密情報です。絶対にGitリポジトリにコミットしないでください。

## 4. APIキーに制限を設定

セキュリティのため、APIキーに制限を設定することを強く推奨します。

### アプリケーションの制限

1. 作成したAPIキーの右側にある「編集」アイコンをクリック
2. 「アプリケーションの制限」セクションで「HTTPリファラー（ウェブサイト）」を選択
3. 「ウェブサイトの制限」に以下を追加：

```
# 本番環境のドメイン
https://yourdomain.com/*

# 開発環境（必要に応じて）
http://localhost:8000/*
http://127.0.0.1:8000/*
```

### API の制限

1. 「APIの制限」セクションで「キーを制限」を選択
2. 以下のAPIのみを選択：
   - Maps JavaScript API
   - Geocoding API

3. 「保存」ボタンをクリック

## 5. 環境変数に設定

### .envファイルの編集

プロジェクトのルートディレクトリにある`.env`ファイルを開き、以下を追加または編集します：

```bash
# Google Maps API
GOOGLE_MAPS_API_KEY=your_api_key_here
```

`your_api_key_here`の部分を、ステップ3で取得したAPIキーに置き換えてください。

### 設定の確認

```bash
# .envファイルが正しく読み込まれているか確認
php artisan tinker
>>> env('GOOGLE_MAPS_API_KEY')
```

APIキーが正しく表示されればOKです。

## 6. 動作確認

### 店舗詳細ページで確認

1. Webブラウザで店舗詳細ページにアクセス
2. ページ下部に地図が表示されることを確認
3. マーカーをクリックして情報ウィンドウが表示されることを確認
4. 「Google Mapsで開く」リンクが正しく動作することを確認

### 店舗一覧ページで確認

1. 店舗一覧ページにアクセス
2. 「地図を表示」ボタンをクリック
3. 複数の店舗マーカーが表示されることを確認
4. マーカーをクリックして店舗情報が表示されることを確認

## 7. 料金と使用制限について

### 無料枠

Google Maps Platformには、毎月$200分の無料クレジットが提供されます。

### 各APIの料金（2024年時点）

#### Maps JavaScript API
- 動的マップの読み込み: $7 / 1,000リクエスト
- **無料枠**: 最初の28,000リクエスト/月は無料

#### Geocoding API
- ジオコーディング: $5 / 1,000リクエスト
- **無料枠**: 最初の40,000リクエスト/月は無料

### 想定される月間コスト

**想定シナリオ**: 月間10,000PVのWebサイト

- 店舗詳細ページ閲覧: 5,000回
- 店舗一覧で地図表示: 1,000回
- ジオコーディング（キャッシュなし）: 500回

**概算コスト**:
- Maps JavaScript API: $0（無料枠内）
- Geocoding API: $0（無料枠内）

**合計**: $0/月

> **ヒント**: 店舗の緯度経度をデータベースに保存することで、Geocoding APIの使用を減らし、コストを削減できます。

### 使用量の監視

1. Google Cloud Consoleで「APIとサービス」→「ダッシュボード」を選択
2. 各APIの使用状況グラフを確認
3. 予期しない使用量増加がないか定期的にチェック

### 使用量の上限設定（推奨）

1. Google Cloud Consoleで「APIとサービス」→「割り当て」を選択
2. 各APIの割り当てを制限することで、予期しない高額請求を防ぐことができます

例：
- Maps JavaScript API: 1,000リクエスト/日
- Geocoding API: 100リクエスト/日

## 8. トラブルシューティング

### 地図が表示されない

**症状**: 地図エリアに「Google Maps APIキーが設定されていません」と表示される

**解決策**:
1. `.env`ファイルに`GOOGLE_MAPS_API_KEY`が設定されているか確認
2. アプリケーションを再起動（`php artisan serve`を再実行）
3. キャッシュをクリア: `php artisan config:clear`

### 地図は表示されるがマーカーが表示されない

**症状**: 地図は表示されるが、マーカーや情報ウィンドウが表示されない

**解決策**:
1. ブラウザの開発者ツール（F12）でコンソールエラーを確認
2. 店舗の緯度経度がデータベースに正しく保存されているか確認
   ```sql
   SELECT id, store_name, latitude, longitude FROM stores;
   ```
3. Geocoding APIが有効になっているか確認

### "This page can't load Google Maps correctly"というエラー

**症状**: 地図エリアにエラーメッセージが表示される

**解決策**:
1. APIキーが正しいか確認
2. Maps JavaScript APIが有効になっているか確認
3. APIキーの制限設定を確認（HTTPリファラーが正しいか）
4. ブラウザの開発者ツールでJavaScriptエラーを確認

### ジオコーディングが失敗する

**症状**: 緯度経度のない店舗の地図が表示されない

**解決策**:
1. Geocoding APIが有効になっているか確認
2. 住所が正しく入力されているか確認（郵便番号、都道府県、市区町村、番地）
3. APIキーの制限で Geocoding API が許可されているか確認

### APIキーの制限エラー

**症状**: "This API project is not authorized to use this API"というエラー

**解決策**:
1. APIキーの「APIの制限」設定を確認
2. 必要なAPIが選択されているか確認
3. 設定変更後、数分待ってから再試行

## 参考リンク

- [Google Maps Platform ドキュメント](https://developers.google.com/maps/documentation)
- [Maps JavaScript API ガイド](https://developers.google.com/maps/documentation/javascript/tutorial)
- [Geocoding API ガイド](https://developers.google.com/maps/documentation/geocoding/overview)
- [Google Maps Platform 料金](https://mapsplatform.google.com/pricing/)
- [無料トライアルとクレジット](https://cloud.google.com/maps-platform/pricing)

## サポート

問題が解決しない場合は、以下の情報を含めてサポートにお問い合わせください：

1. エラーメッセージ（あれば）
2. ブラウザの開発者ツールのコンソールログ
3. 使用しているブラウザとバージョン
4. 実行した手順

---

最終更新日: 2025年11月4日
