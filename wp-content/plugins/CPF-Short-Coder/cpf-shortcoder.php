<?php
/*
Plugin Name: CPF Shortcoder
Author: Sota K.
Description: With this plug-in, you can intuitively display the menu in a place you like.
Version: 1.0
*/

class Can_Placement_Free_Shortcoder
{

    public function __construct()
    {
        add_shortcode('cpf_menu', array($this, 'render_shortcode'));

        global $pagenow;
        if (is_admin() && ($pagenow == 'nav-menus.php')) {
            add_action('admin_footer', array($this, 'shortcoder'));
        }

        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    /**
     * ショートコードの出力を行います。
     *
     * @param array $atts ショートコードの属性値
     * @param null $content ショートコードのコンテンツ
     *
     * @return string ショートコードから生成したHTML
     */
    function render_shortcode($atts, $content = null)
    {
        ob_start();
        extract(shortcode_atts(array(
            'menu_id' => null,
        ), $atts));

        ?>
        <section class="front-section">
            <div class="basic-operation-menu">
                <?php
                $args = array(
                    "menu" => $menu_id,
                    'container' => false,
                    'menu_id' => 'basic-operation-menu-list',
                    'menu_class' => 'basic-operation-menu__list',
                    'theme_location' => 'basic-operation-menu',
                    'link_before' => '<svg class="svg-icon svg-icon--main-color svg-icon--big">
                                         <use xlink:href="' . plugins_url('/assets/svg/sprites.svg#wordpress-logo-simplified', dirname(__FILE__)) . '" /></svg>'
                );
                wp_nav_menu($args);
                ?>
            </div>
        </section>

        <?php
        return ob_get_clean();
    }

    /**
     * 管理画面に値を取得したショートコードの表示
     */
    function shortcoder()
    { ?>
        <script>
            var $menu_name = jQuery('#menu-name');
            if ($menu_name[0]) {
                $menu_name.after(
                    '<label class="menu-name-label" for="cpf-menu">CPF Shortcoder</label>' +
                    '<input id="cpf-menu" type="text" readonly="readonly" onclick="this.select(0,this.value.length)" value="[cpf_menu menu_id=' + jQuery('#select-menu-to-edit').val() + ']">'
                );
            }
        </script>
        <?php
    }

    /**
     * @param string $hook 現在表示している管理ページの名前
     */
    function enqueue_admin_scripts($hook)
    {
        if ('nav-menus.php' != $hook)
            return;
        wp_enqueue_style(
            'cpf_custom_menu',
            plugins_url("/assets/css/admin-custom-menu.css", dirname(__FILE__)),
            array()
        );
    }
}

new Can_Placement_Free_Shortcoder();