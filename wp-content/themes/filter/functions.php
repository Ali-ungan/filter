<?php
session_start();

add_theme_support('post-thumbnails');

add_filter('rank_math/metabox/priority', function ($priority) {
    return 'low';
});

if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'menu_title' => 'Penta Panel',
        'menu_slug' => 'theme-general-settings',
        'capability' => 'edit_posts',
        'icon_url' => "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyOTQuMTQgMjcwLjQyIj48ZGVmcz48c3R5bGU+LmNscy0xe2ZpbGw6IzFkMWQxYjtmaWxsLXJ1bGU6ZXZlbm9kZDt9PC9zdHlsZT48L2RlZnM+PGcgaWQ9IkxheWVyXzIiIGRhdGEtbmFtZT0iTGF5ZXIgMiI+PGcgaWQ9ImthdG1hbl8xIiBkYXRhLW5hbWU9ImthdG1hbiAxIj48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik0zNi4wNSwwQTM2LDM2LDAsMCwwLDAsMzYuMDVWMjcwLjQySDI1OC4wOWEzNiwzNiwwLDAsMCwzNi4wNS0zNi4wNVYwWk0yNjkuMTYsMjQ1LjQ0SDI1VjI1SDI2OS4xNloiLz48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik03Ni43NywxMjcuNTJIMjQ4LjYyVjQxLjcxSDQ0LjcxdjE4OEg3Ni43N1ptMTQ4LTU4LjkzdjMyLjE0aC0xNDhWNjguNTlaIi8+PHBvbHlnb24gY2xhc3M9ImNscy0xIiBwb2ludHM9IjIxMC40NyAxMzguMjYgMTY0LjY5IDE3Ny40OSAxMjYuNDIgMTM5Ljc2IDg1IDEzOS43NiAxNjkuODcgMjI5Ljk0IDIxNy42IDIyOS42NyAxODUuMDggMTk3LjYgMjQ4LjYyIDEzOC4yNiAyMTAuNDcgMTM4LjI2Ii8+PC9nPjwvZz48L3N2Zz4=",
        'redirect' => true
    ));

    acf_add_options_sub_page(array(
        'page_title' => 'Genel Ayarlar',
        'menu_title' => 'Genel Ayarlar',
        'parent_slug' => 'theme-general-settings',
    ));

    acf_add_options_sub_page(array(
        'page_title' => 'Menü Ayarları',
        'menu_title' => 'Menü Ayarları',
        'parent_slug' => 'theme-general-settings',
    ));

    acf_add_options_sub_page(array(
        'page_title' => 'İletişim Ayarları',
        'menu_title' => 'İletişim Ayarları',
        'parent_slug' => 'theme-general-settings',
    ));

    acf_add_options_sub_page(array(
        'page_title' => 'Form Ayarları',
        'menu_title' => 'Form Ayarları',
        'parent_slug' => 'theme-general-settings',
    ));
}

function get_component($name): void
{
    include __DIR__ . '/components/' . $name . '.php';
}

function asset($url): string
{
    return bloginfo('template_url') . '/' . $url;
}

function my_custom_pagination_base(): void
{
    global $wp_rewrite;
    $wp_rewrite->pagination_base = 'sayfa';
    $wp_rewrite->flush_rules();
}

add_action('init', 'my_custom_pagination_base', 1);

function clear_phone_number(?string $number): ?string
{
    return preg_replace('/[^0-9+]/', '', $number);
}


add_filter('wpcf7_autop_or_not', '__return_false');


function get_alt_tag($data = null)
{
    if (isset($data))
        return bloginfo('name') . ' | ' . $data;
    else
        return bloginfo('name') . ' | ' . get_the_title();
}

function customPagination($maxShow = 3)
{
    global $wp_query;
    if ($wp_query->max_num_pages <= 1) return null;
    $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
    $max = intval($wp_query->max_num_pages);
    $html = '';
    $minPage = ($max - $paged) > $maxShow ? $paged : ($paged - (max($maxShow - ($max - $paged), 1)));
    $maxPage = $paged < $max ? $minPage + $maxShow : $paged;
    for ($i = $minPage; $i <= $maxPage; $i++) {
        if ($i < 1) continue;
        $html .= '<a class="social ' . ($i == $paged ? 'active' : '') . '" href="' . get_pagenum_link($i) . '">' . $i . '</a>';
    }

    return $html;
}

function add_favorite()
{
    $id = $_POST['id'];

    if ($id) {
        try {
            $favorites = $_SESSION['favorites'] ?? [];

            if (!in_array($id, $favorites)) {
                $_SESSION['favorites'][] = $id;

                exit(json_encode([
                    'error' => false,
                    'message' => 'Eklendi',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            } else {
                foreach ($_SESSION['favorites'] as $key => $favorite) {
                    if ($favorite === $id) {
                        unset($_SESSION['favorites'][$key]);
                    }
                }
                array_values($_SESSION['favorites']);

                exit(json_encode([
                    'error' => false,
                    'message' => 'Favorilerden kaldırıldı',
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            }
        } catch (Exception $e) {
        }
    } else {
        exit(json_encode([
            'error' => true,
            'message' => 'olumsuz',
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
}

add_action('wp_ajax_nopriv_add_favorite', 'add_favorite');
add_action('wp_ajax_add_favorite', 'add_favorite');
