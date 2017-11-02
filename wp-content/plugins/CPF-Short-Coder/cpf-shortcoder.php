<?php
/*
Plugin Name: CPF Shortcoder
Author: Sota Kisuke.
Description: どこでも好きなところあなたの作ったメニューが置けるんです。置けちゃうんです。
Version: 0.1
*/

class Can_Placement_Free_Shortcoder {

	/** 設定値 */
	private $options;

	/**
	 * 初期化処理です。
	 */
	public function __construct() {

		//ショートコードの追加
		add_shortcode( 'cpf_menu', array( $this, 'render_shortcode' ) );

		//メニューの追加
		add_action( 'admin_menu', array( $this, 'cpf_admin_menu' ) );

		global $pagenow;
		if ( is_admin() && ( $pagenow == 'nav-menus.php' ) ) {
			add_action( 'admin_footer', array( $this, 'shortcoder' ) );
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	}

	/**
	 * ショートコードの出力を行います。
	 *
	 * @param array $atts ショートコードの属性値
	 * @param null $content ショートコードのコンテンツ
	 *
	 * @return string ショートコードから生成したHTML
	 */
	function render_shortcode( $atts, $content = null ) {
		ob_start();
		extract( shortcode_atts( array(
			'menu_id' => null,
		), $atts ) );

		?>
        <section class="front-section">
            <div class="basic-operation-menu">
				<?php
				$args = array(
					"menu"           => $menu_id,
					'container'      => false,
					'menu_id'        => 'basic-operation-menu-list',
					'menu_class'     => 'basic-operation-menu__list',
					'theme_location' => 'basic-operation-menu',
					'link_before'    => '<svg class="svg-icon svg-icon--main-color svg-icon--big">
                                         <use xlink:href="' . plugins_url( '/assets/svg/sprites.svg#wordpress-logo-simplified', dirname( __FILE__ ) ) . '" /></svg>'
				);
				wp_nav_menu( $args );
				?>
            </div>
        </section>

		<?php
		return ob_get_clean();
	}

	/**
	 * 管理画面に値を取得したショートコードの表示
	 */
	function shortcoder() { ?>
        <script>
            var $menu_name = jQuery('#menu-name');
            var $select_menu_to_edit = jQuery('#select-menu-to-edit');

            //メニューに名前があり、かつ、メニューのvalueが空でないとき
            if ($menu_name[0] && $menu_name.val() !== "") {

                //メニューの数が1つか2つ以上か
                if (!$select_menu_to_edit[0]) {

                    //一個の時
                    $menu_name.after(
                        '<label class="menu-name-label" for="cpf-menu">CPF Shortcoder</label>' +
                        '<input id="cpf-menu" type="text" readonly="readonly" onclick="this.select(0,this.value.length)" value="[cpf_menu menu_id=' + $menu_name.val() + ']">'
                    );
                } else {
                    console.log($select_menu_to_edit.find("option:selected").text());

                    //二個以上の時
                    $menu_name.after(
                        '<label class="menu-name-label" for="cpf-menu">CPF Shortcoder</label>' +
                        '<input id="cpf-menu" type="text" readonly="readonly" onclick="this.select(0,this.value.length)" value="[cpf_menu menu_id=' + $select_menu_to_edit.find("option:selected").text().trim() + ']">'
                    );
                }
            } else {

                //メニューが存在しないとき
                $menu_name.after(
                    '<label class="menu-name-label" for="cpf-menu">CPF Shortcoder</label>' +
                    '<input id="cpf-menu" type="text" disabled="disabled" onclick="this.select(0,this.value.length)" value="メニューを追加してください">'
                );
            }
        </script>
		<?php
	}

	/**
	 * @param string $hook 現在表示している管理ページの名前
	 */
	function enqueue_admin_scripts( $hook ) {
		if ( 'nav-menus.php' != $hook ) {
			return;
		}
		wp_enqueue_style(
			'cpf_custom_menu',
			plugins_url( "CPF Shortcoder/assets/css/cpf-shortcoder.css", dirname( __FILE__ ) ),
			array()
		);
	}

	/**
	 *  管理画面にCPF Short Coderのメニューを追加する
	 */
	function cpf_admin_menu()
    {

		//ページタイトルと、管理画面の名称設定と国際化
		add_menu_page(
			__( 'CPF Short Coder', 'cpf-custom-admin' ),
			__( 'CPF Short Coder', 'cpf-custom-admin' ),
			'administrator',
			'cpf-custom-admin',
			array( $this, 'cpf_custom_admin' ),
			'dashicons-editor-code'
		);

		//サブメニューの追加
		add_submenu_page(
			'cpf-custom-admin',
			__( 'Media', 'cpf-custom-admin' ),
			__( 'Media', 'cpf-custom-admin' ),
			'administrator',
			'cpf-submenu',
			array( $this, 'cpf_submenu' )
		);

		/**
		 * 設定ページの初期化を行います。
		 */

		//ページの初期化
		add_action( 'admin_init', array( $this, 'cpf_init' ) );

		function cpf_init()
        {
			// 設定を登録します(入力値チェック用)。
			// register_setting( $option_group, $option_name, $sanitize_callback )
			//   $option_group      : 設定のグループ名
			//   $option_name       : 設定項目名(DBに保存する名前)
			//   $sanitize_callback : 入力値調整をする際に呼ばれる関数
			register_setting( 'cpf-custom-admin', 'cpf-custom-admin', array( $this, 'sanitize' ) );

			// 入力項目のセクションを追加します。
			// add_settings_section( $id, $title, $callback, $page )
			//   $id       : セクションのID
			//   $title    : セクション名
			//   $callback : セクションの説明などを出力するための関数
			//   $page     : 設定ページのslug (add_menu_page()の$menu_slugと同じものにする)
			add_settings_section( 'cpf_setting_section_id', '', '', 'cpf-custom-admin' );

			// 入力項目のセクションに項目を1つ追加します(今回は「メッセージ」というテキスト項目)。
			// add_settings_field( $id, $title, $callback, $page, $section, $args )
			//   $id       : 入力項目のID
			//   $title    : 入力項目名
			//   $callback : 入力項目のHTMLを出力する関数
			//   $page     : 設定ページのslug (add_menu_page()の$menu_slugと同じものにする)
			//   $section  : セクションのID (add_settings_section()の$idと同じものにする)
			//   $args     : $callbackの追加引数 (必要な場合のみ指定)
			add_settings_field( 'message', 'メッセージ', array(
				$this,
				'message_callback'
			), 'cpf-custom-admin', 'cpf_setting_section_id' );
		}

		/**
		 * 設定ページのHTMLを出力します。
		 */

        function cpf_custom_admin()
        {

			// 設定値を取得します。
			$this->options = get_option( 'cpf-custom-admin' );
			?>
            <div class="wrap">
                <h2>CPF Short Coder 設定</h2>
				<?php
				// add_options_page()で設定のサブメニューとして追加している場合は
				// 問題ありませんが、add_menu_page()で追加している場合
				// options-head.phpが読み込まれずメッセージが出ない(※)ため
				// メッセージが出るようにします。
				// ※ add_menu_page()の場合親ファイルがoptions-general.phpではない
				global $parent_file;
				if ( $parent_file != 'options-general.php' ) {
					require( ABSPATH . 'wp-admin/options-head.php' );
				}
				?>
                <form method="post" action="options.php">
					<?php
					// 隠しフィールドなどを出力します(register_setting()の$option_groupと同じものを指定)。
					settings_fields( 'cpf-custom-admin' );

					// 入力項目を出力します(設定ページのslugを指定)。
					do_settings_sections( 'cpf-custom-admin' );

					// 送信ボタンを出力します。
					submit_button();
					?>
                </form>
            </div>
			<?php
		}

		function cpf_submenu()
        {
			?>
            <div class="wrap">
                <h1>Media</h1>
            </div>
			<?php
		}

		/**
		 * 入力項目(「メッセージ」)のHTMLを出力します。
		 */
       function message_callback()
        {
			// 値を取得
			$message = isset( $this->options['message'] ) ? $this->options['message'] : '';
			// nameの[]より前の部分はregister_setting()の$option_nameと同じ名前にします。
			?><input type="text" id="message" name="cpf-custom-admin[message]"
                     value="<?php esc_attr_e( $message ) ?>" /><?php
		}

		/**
		 * 送信された入力値の調整を行います。
		 *
		 * @param array $input 設定値
		 *
		 * @return $new_input 設定値
		 */
        function sanitize( $input )
        {
			// DBの設定値を取得します。
			$this->options = get_option( 'test_setting' );

			$new_input = array();

			// メッセージがある場合値を調整
			if ( isset( $input['message'] ) && trim( $input['message'] ) !== '' ) {
				$new_input['message'] = sanitize_text_field( $input['message'] );
			} // メッセージがない場合エラーを出力
			else {
				// add_settings_error( $setting, $code, $message, $type )
				//   $setting : 設定のslug
				//   $code    : エラーコードのslug (HTMLで'setting-error-{$code}'のような形でidが設定されます)
				//   $message : エラーメッセージの内容
				//   $type    : メッセージのタイプ。'updated' (成功) か 'error' (エラー) のどちらか
				add_settings_error( 'cpf-custom-admin', 'message', 'メッセージを入力して下さい。' );

				// 値をDBの設定値に戻します。
				$new_input['message'] = isset( $this->options['message'] ) ? $this->options['message'] : '';
			}

			return $new_input;
		}
	}
}

// 管理画面を表示している場合のみ実行します。
if ( is_admin() )
{
	$test_settings_page = new Can_Placement_Free_Shortcoder();
}

new Can_Placement_Free_Shortcoder();
