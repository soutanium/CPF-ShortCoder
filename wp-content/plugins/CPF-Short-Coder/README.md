# Web Template

Webサイト制作用のテンプレートです。

## 使用にあたっての環境構築

以下はWindows環境を例にしています。  

* CSSプリプロセッサの用意
* タスクランナーの用意

は最初の一回のみ必要です。一度インストールした後はアップデートを行うだけで大丈夫です。  

* Gulpプラグインの各種インストール

のみ、毎回リポジトリをクローンした時に行います。

### CSSプリプロセッサの用意

CSSプリプロセッサには「__Sass__」を採用しています。

#### 1.Rubyのインストール

Sass, Compass, SusyのインストールにはRubyが必要です。  
インストール方法は[こちら](https://www.ruby-lang.org/ja/documentation/installation/ "Rubyのインストール")を参考にしてください。（Windowsなら[こちら](https://rubyinstaller.org/ "RubyInstaller for Windows")のリンクから）

Rubyのインストールが終わったら、パッケージマネージャの「RubyGems」をアップデートしておきます。

```
gem update --system
```

#### 2.Sassのインストール

```
gem install sass
```

### タスクランナーの用意

タスクランナーには「__Gulp__」を採用しています。

#### 1.Node.jsのインストール

[公式サイト](https://nodejs.org/en/ "Node.js")からインストーラをダウンロードし、そのままインストールします。（ダウンロードは「vX.XX.X LTS」という安定板をインストールしてください。「vX.XX.X Current」という最新版だとGulpプラグインが安定して実行されない可能性が高いです）

#### 2.パッケージのバージョン管理プラグインをインストール

「npm-check-updates」を使うことで、Gulpで使用するプラグインの一括アップデートがコマンド一つで可能になります。  
[使い方などの詳細はこちら。](https://github.com/tjunnone/npm-check-updates "tjunnone/npm-check-updates: Find newer versions of package dependencies than what your package.json or bower.json allows")

```
npm install -g npm-check-updates
```

#### 3.Gulpのインストール

本命のタスクランナー「Gulp」のインストールです。

```
npm install gulp -g
```

以上で環境構築は完了です。

### Gulpプラグインの各種インストール

package.jsonに必要なGulpプラグインが既に記述されているので、以下の一行で完了します。

```
npm install
```

### ESLintの設定
1. PhpStormの`File`->`Settings...`を開く。
1. 設定の検索に`ESLint`と入力し`JavaScript`->`Code Quality Tools`->`ESLint`を開く。
1. `Enable`にチェックを入れます。（Node.jsなどがインストール済みなら自動で設定が入力されます）
1. `Inspections`を開きます。
1. `JavaScript`->`Code quality tools`->`ESLint`のチェックを入れます。

### Composerで各種ツールのインストール（1度行えばそれ以降は不要）

[Windows向けのComposer](https://getcomposer.org/download/)をインストールしてください。
インストーラをダウンロードしてインストールを行えば使えるようになります。
なお、PHPの環境がないとインストールを完了できません。

PHPの環境がない場合、[こちら](http://php.net/manual/ja/install.windows.manual.php)を参考にPHPをインストールしましょう。

1. エクスプローラで次のフォルダ（%APPDATA%\Composer）を開いてweb-templateの直下にあるcomposer-global.jsonをコピーしcomposer.jsonにリネームしてください。

composer.jsonに記述されている各種ツールをインストールします。
また、`PHP Code Sniffer`に`WordPress Coding Standards`のルールを追加する。

```
composer global install
phpcs --config-set installed_paths %APPDATA%\Composer\vendor\wp-coding-standards\wpcs
```

#### PhpStormでPHPCS/PHPMDを有効化（1度行えばそれ以降は不要）

PHP Code Sniffer Validation 編

1. PhpStormの`File`->`Settings...`を開く。
1. 設定の検索に`Code Sniffer`と入力し`...`を開く。
1. `composer global install`が成功していれば`%APPDATA%\Composer\vendor\bin\phpcs.bat`があるので
  PHP Code Sniffer (phpcs) path: にパスを入力する。
  その際、`%APPDATA%`（環境変数）は展開しておく。
  例、 C:\Users\ユーザ名\AppData\Roaming\Composer\vendor\bin\phpcs.bat
1. 設定の検索に`Code Sniffer`と入力し`Editor`->`Inspections`を開く。
1. `▶PHP`を開き、`PHP Code Sniffer validation`にチェックを入れる。
1. `Show sniff name`にチェックを入れる。
1. `Codeing Standard`で利用したいルール（WordPressを推奨）を選択する。

PHP Mess Detector Validation 編

1. PhpStormの`File`->`Settings...`を開く。
1. 設定の検索に`Mess Detector`と入力し`...`を開く。
1. `composer global install`が成功していれば`%APPDATA%\Composer\vendor\bin\phpmd.bat`があるので
  PHP Mess Detector (phpmd) path: にパスを入力する。
  その際、`%APPDATA%`（環境変数）は展開しておく。
  例、 C:\Users\ユーザ名\AppData\Roaming\Composer\vendor\bin\phpmd.bat
1. 設定の検索に`Mess Detector`と入力し`Editor`->`Inspections`を開く。
1. `▶PHP`を開き、`PHP Mess Detector validation`にチェックを入れる。
1. `Code Size Rules``Design Rules``Naming Rules``Unused Code Rules`にチェックを入れる。

## ディレクトリ・ファイル構造

以下、テンプレートのディレクトリ・ファイル構造です。  
要らないファイルは削除します。  
// TODO: まだ完ぺきではないので、空いた時間に追加・修正する

```
web-template/
├── assets/
│   ├── css/
│   │   └── main.css
│   ├── fonts/
│   │   └── font-awesome/
│   │       ├── FontAwesome.otf
│   │       ├── fontawesome-webfont.eot
│   │       ├── fontawesome-webfont.svg
│   │       ├── fontawesome-webfont.ttf
│   │       ├── fontawesome-webfont.woff
│   │       └── fontawesome-webfont.woff2
│   ├── images/
│   │   └── touch/
│   │       ├── apple-touch-icon.png
│   │       ├── chrome-touch-icon-192x192.png
│   │       ├── icon-128x128.png
│   │       └── ms-touch-icon-144x144-precomposed.png
│   ├── js/
│   │   ├── vendor/
│   │   │   ├── jquery-1.11.2.min.js
│   │   │   ├── jquery.easing.min.js
│   │   │   ├── velocity.min.js
│   │   │   ├── mobile-detect.min.js
│   │   │   └── modernizr-2.8.3.min.js
│   │   ├── main.js
│   │   └── plugins.js
│   ├── scss/   # Atomic Design
│   │   ├── 00_foundation/
│   │   │   ├── lib/
│   │   │   │   ├── normalize/      # From from "normalize.css v5".
│   │   │   │   ├── bourbon/        # Bourbon v5.0.0.beta.7
│   │   │   │   ├── neat/           # Neat v2.0.0
│   │   │   │   ├── font-awesome/   #
│   │   │   ├── variables/
│   │   │   │   ├── _color-settings.scss
│   │   │   │   ├── _layer-settings.scss
│   │   │   │   ├── _bourbon-settings.scss
│   │   │   │   └── _neat-settings.scss
│   │   │   └── _typography.scss
│   │   ├── 01_atom/
│   │   ├── 02_molecule/
│   │   ├── 03_organism/
│   │   ├── 04_template/
│   │   ├── 05_page/
│   │   │   ├── jquery-1.11.2.min.js
│   │   │   ├── jquery.easing.min.js
│   │   │   ├── velocity.min.js
│   │   │   ├── mobile-detect.min.js
│   │   │   └── modernizr-2.8.3.min.js
│   │   ├── main.js
│   │   └── plugins.js
│   └── svg/
│       └── sprites/
│   　   　   ├── facebook.svg
│   　   　   ├── facebook_icon.svg
│   　   　   ├── feedly.svg
│   　   　   ├── feedly_a.svg
│   　   　   ├── gplus.svg
│   　   　   ├── hatena.svg
│   　   　   ├── hatena_icon.svg
│   　   　   ├── line.svg
│   　   　   ├── line_a.svg
│   　   　   ├── line_icon.svg
│   　   　   └── twitter.svg
├── README.md              # 説明用MarkDownファイル
├── .gitignore             # Gitで管理しないファイルを無視する設定用
├── bower.json             # ライブラリ管理マネージャ「Bower」の設定ファイル
├── package.json           # Gulpプラグインの一括インストール用設定ファイル
├── .csscomb.json          # CSSのプロパティ順を最適化するGulpプラグイン「CSScomb」の設定ファイル
├── gulpfile.js            # Gulpの実行ファイル
├── .eslint.rc             # ESLintの設定ファイル
├── composer-global.json   # Composerのグローバルインストール用設定ファイル（独自）
├── style.css              # WordPressテーマ製作用ファイル（テーマコメント記入済）
└── index.html
```

## 使用しているツール

### HTML
* [HTML5 Boilerplate](https://html5boilerplate.com/ "HTML5 Boilerplate: The web’s most popular front-end template")
* [Web Starter Kit](https://developers.google.com/web/tools/starter-kit/ "Web Starter Kit  |  Web  |  Google Developers")
* [HTML5 Bones](https://html5bones.com/ "HTML5 Bones: The template that goes back to basics")

### CSSプリプロセッサ関連
* [Sass](http://sass-lang.com/ "Sass: Syntactically Awesome Style Sheets")
* [Bourbon](http://bourbon.io/ "Bourbon - A Lightweight Sass Tool Set")
* [Neat](http://neat.bourbon.io/ "Neat - A Lightweight and flexible Sass grid")

### タスクランナー
* [Ruby](https://www.ruby-lang.org/ "Ruby Programming Language")
* [Node.js](https://nodejs.org/ "Node.js")
* [Gulp](http://gulpjs.com/ "gulp.js - the streaming build system")

### その他
* [ESLint](http://eslint.org/)
* [Composer](https://getcomposer.org/)
* [PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer)
* [PHP Mess Detector](https://phpmd.org/)
* [WordPress Coding Standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards)

### 各ツールの簡易説明

#### 1.CSSプリプロセッサ関連

CSSプリプロセッサには「__Sass__」を採用しています（SCSS記法）。
Sassのライブラリには「__Bourbon__」と、グリッドレイアウト用の「__Neat__」を使用しています。

##### Sassのメリット

* CSSと同様の記法が可能（SCSS記法の場合）
* 効率的にスタイルを記述できる（変数や関数など一部プログラムを使用できる）
* スタイルガイドと合わせてディレクトリ構造を作ると保守管理がしやすい

SassファイルをコンパイルするとCSSファイルが書き出されます。  
使い方は[公式サイト](http://sass-lang.com/guide "Sass: Sass Basics")にあります。

##### Bourbonのメリット

* 必要最小限の機能提供なので学習コストが低い
* モジュラースケールによるタイポグラフィ管理が楽

等など。Bourbonの公式ドキュメントは[こちら。](http://bourbon.io/docs/ "Bourbon - Documentation")

##### Neatのメリット

* フレキシブルグリッドシステム機能がミニマルなので学習コストが低い

Susy 2の公式ドキュメントは[こちら。](http://susydocs.oddbird.net/en/latest/ "Neat - A Lightweight and flexible Sass grid")

##### ESLintのメリット

* JavaScriptの記述を様々なルールを用いてチェックします。例えば、行末にセミコロンがなければエラーなどです。

##### Composerのメリット

* PHPの各種ライブラリ・コマンドをプロジェクト単位で管理できる。

##### PHP Code Snifferのメリット

* 様々な記法に基づいてソースコードの記述の正当性をチェックできます。例えば、WordPress Coding Standardsを利用するとWordPressのコーディング規約に適合した記述になります。

##### PHP Mess Detectorのメリット

* バグの温床となるようなソースコードのチェックを行えます。例えば未使用変数・関数などの警告です。他にも命名ルールなどのチェックも可能です。

##### WordPress Coding Standards

* WordPressのコーディング規約をPHP Code Sniffer用のルールにまとめたものです。