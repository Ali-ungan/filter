<?php get_header() ?>


<div class="d-flex justify-content-between p-5">
    <div class="d-flex flex-column bg-dark flex-shrink-0 p-3 bg-light" style="width: 280px;">
        <div class="d-flex">
            <a href="javscript:;" class="d-flex text-white w-50 align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                <span class="fs-4">Filtrele</span>
            </a>
            <a href="<?= home_url() ?>" class="d-flex text-white w-50 align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                <span class="fs-4">Filtreleri Temizle</span>
            </a>
        </div>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <form action="" method="GET">
                <?php $categories = get_categories();
                if (is_array($categories) && !empty($categories)) : ?>
                    <select name="category" class="form-select mt-2 rounded border-2 border-success p-1 w-100" aria-label="Default select example">
                        <option disabled selected>Kategori Seçin</option>
                        <?php foreach ($categories as $category) : ?>
                            <option <?= $category->term_id == (isset($_GET['category']) && is_numeric($_GET['category']) ? $_GET['category'] : null) ? 'selected' : null ?> value="<?= $category->term_id ?>"><?= $category->name ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
                <?php $alts = get_terms([
                    'taxonomy' => 'blog_cat',
                    'hide_empty' => false
                ]);
                if (is_array($alts) && !empty($alts)) : ?>
                    <select name="alt" class="form-select mt-2 rounded border-2 border-success p-1 w-100" aria-label="Default select example">
                        <option disabled selected>Alt Kategori Seçin</option>
                        <?php foreach ($alts as $alt) : ?>
                            <option <?= $alt->term_id == (isset($_GET['alt']) && is_numeric($_GET['alt']) ? $_GET['alt'] : null) ? 'selected' : null ?> value="<?= $alt->term_id ?>"><?= $alt->name ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>

                
                <button type="submit" class="btn btn-success p-1 mt-5 w-100">Filtrele</button>
            </form>
        </ul>
        <hr>
    </div>
    <?php $args = [
        'post_type' => 'post',
        'posts_per_page' => -1,
        'tax_query' => [
            'relation' => 'AND'
        ]
    ];

    if (isset($_GET) && isset($_GET['category'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'category',
            'field' => 'term_id',
            'terms' => [$_GET['category']]
        ];
    }

    if (isset($_GET) && isset($_GET['alt'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'blog_cat',
            'field' => 'term_id',
            'terms' => [$_GET['alt']]
        ];
    }
    
    $wp_query = new WP_Query($args);
    $posts = $wp_query->get_posts();
    if (is_array($posts) && !empty($posts)) :
        foreach ($posts as $key => $post) : ?>
            <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                <div class="card-header w-full">
                    <?= $post->post_title ?>
                    <a class="fav_button text-white fav float-right <?= in_array($post->ID, $_SESSION['favorites']) ? 'active' : 'not_favorite' ?>" href="javascript:;" data-id="<?= $post->ID ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                            <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z" />
                        </svg>
                    </a>
                </div>
                <div class="card-body">
                    <p class="card-text"><?= strip_tags($post->post_content) ?></p>
                </div>
            </div>
    <?php endforeach;
    endif; ?>
</div>
<div class="container">
    <div class="d-flex">
        <h1>FAVORİLER</h1>
        <button id="download" <?= !empty($_SESSION['favorites']) ? null : 'disabled' ?> class="btn btn-success p-1 ml-5">Favorileri İndir</button>
    </div>
    <div id="print">

        <div class="center justify-content-between p-5 myfavs">
            <?php
            if (is_array($_SESSION['favorites']) && !empty($_SESSION['favorites'])) :
                foreach ($_SESSION['favorites'] as $favorite) : ?>
                    <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                        <div class="card-header w-full">
                            <?= get_the_title($favorite) ?>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?= strip_tags(get_the_content($favorite)) ?></p>
                        </div>
                    </div>
            <?php endforeach;
            endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>


<script>
    input = $('.fav');
    input.click(function() {
        let ajaxURL = '<?php echo esc_js(admin_url('admin-ajax.php')); ?>';
        id = $(this).data('id');
        $.ajax({
            url: ajaxURL,
            method: 'post',
            datatype: 'json',
            data: {
                action: 'add_favorite',
                id: id,
            },
            success: function(data) {
                data = JSON.parse(data)
                var fav_button = $('.fav_button')
                fav_button.click(function() {
                    if (data.error == false) {
                        toastr.success(data.message)
                    } else if (data.error == 'not_favorite') {
                        toastr.success(data.message)
                    } else {
                        toastr.error(data.message)
                    }
                })
                location.reload();
            }
        })
    })
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js" integrity="sha512-ToRWKKOvhBSS8EtqSflysM/S7v9bB9V0X3B1+E7xo7XZBEZCPL3VX5SFIp8zxY19r7Sz0svqQVbAOx+QcLQSAQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $('#download').click(function() {
        var doc = new jsPDF();
        var div = document.querySelector('#print')
        doc.fromHTML(div, 50, 60)
        doc.save("favorites.pdf")
    })
</script>