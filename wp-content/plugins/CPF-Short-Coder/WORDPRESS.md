# WordPress化

HTML/CSS/JavaScriptなどによる静的なWebサイトデータをWordPressテーマにする簡易的な手順を記します。

WordPressテーマとして最低限必要なファイルは2ファイルです。

- index.php
- style.css

## 簡易的なWordPressテーマを作成する手順

テーマとして動く最低限のディレクトリ構造以下です。

```
web-template/   # テーマディレクトリ。テーマの名前や任意のテキストで可能。
├── index.php   # テーマのデフォルトテンプレート（インデックス）。全てのページのフォールバック用テンプレートでもある。
└── style.css   # テーマスタイルシート。コメント形式で必ず「テーマ詳細」を記述しなくてはならない。
```

この2つのファイルがあるディレクトリを`wp-content/themes/`内に配置するとWordPressがテーマとして認識します。

### インデックス（index.php）テンプレート
これは`index.html`の拡張子「html」を「php」に変更するだけです。`index.php`に変更してテーマディレクトリに配置してください。（これだけでは表示崩れが発生します。ディレクトリ構造が変化するためCSSや画像、JavaScriptの読み込みに失敗する可能性がほぼ100%になるからです）

### テーマスタイルシート
テーマスタイルシート`style.css`にコメントで「テーマ詳細」を記述します。分かりやすくファイルの先頭に記述するのが良いでしょう。（マルチバイト、日本語などでコメントをする場合はファイルの1行目に`@charset "UTF-8";`が必須で、これがないと文字化けが発生します）

テーマ詳細のコメント形式は以下です。

```
@charset "UTF-8";
/*
    Theme Name:     # テーマ名。（必須）
    Theme URI:      # テーマ配布のURL。（オプション）
    Author:         # 作成者名。（必須）
    Author URI:     # 作成者のWebサイトURLなど。（オプション）
    Description:    # テーマの説明文。（必須）
    Version:        # テーマのバージョン。（必須）
    License:        # テーマのライセンス形式（必須）
    License URI:    # テーマのライセンス文書が分かるURLやディレクトリ。（必須）
    Text Domain:    # テーマテンプレートにあるテキストを翻訳する場合に必要な識別文字列。（必須）
    Tags:           # WordPressのテーマ配布リポジトリでフィルタリングする場合に使うタグ。（オプション）
*/
```

「必須」「オプション」とありますが、これはWordPress公式のテーマリポジトリに登録する場合の条件です。実際には
- Theme Name:

だけで動くかもしれません。（もしかしたら名前を書かなくても認識する可能性あり。**要検証**）

一つ、「**style.cssに記述するコメントの"テーマ詳細"は他テーマと同じ内容になってはいけない**」が注意するところです。同じ内容だとテーマとして認識されません。
詳しくは公式Codex「[テーマの作成 - WordPress Codex 日本語版](https://wpdocs.osdn.jp/%E3%83%86%E3%83%BC%E3%83%9E%E3%81%AE%E4%BD%9C%E6%88%90#.E3.83.86.E3.83.BC.E3.83.9E.E3.82.B9.E3.82.BF.E3.82.A4.E3.83.AB.E3.82.B7.E3.83.BC.E3.83.88)」を参照してください。英語版の更なる詳細なページは「[Main Stylesheet (style.css) | Theme Developer Handbook | WordPress Developer Resources](https://developer.wordpress.org/themes/basics/main-stylesheet-style-css/)」です。

「Tags:」項目に使えるタグの文字列は「[Theme Tags – Theme Review Team — WordPress](https://make.wordpress.org/themes/handbook/review/required/theme-tags/)」を参照してください。

以上で最低限なWordPressテーマが作成できます。

## テンプレートの読み込み優先度一覧表

公式ドキュメントにあるこちらの画像が参考になります。
「[template-hierarchy.png (2880×1800)](https://developer.wordpress.org/files/2014/10/template-hierarchy.png)」