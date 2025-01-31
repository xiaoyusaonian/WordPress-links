<?php
/*
Template Name: 友链模板
*/
if (isset($_POST['blink_form']) && $_POST['blink_form'] == 'send') {
    global $wpdb;

    $link_name = isset($_POST['blink_name']) ? trim(htmlspecialchars($_POST['blink_name'], ENT_QUOTES)) : '';
    $link_url = isset($_POST['blink_url']) ? trim(htmlspecialchars($_POST['blink_url'], ENT_QUOTES)) : '';
    $link_description = '图片链接：' . $_POST['blink_image'] . "\n" . '简介：' . $_POST['blink_jianjie'];
    
    // 表单验证
    if (empty($link_name) || mb_strlen($link_name) > 20) wp_die('连接名称必须填写，且长度不得超过30字');
    if (empty($link_url) || strlen($link_url) > 60 || !filter_var($link_url, FILTER_VALIDATE_URL)) wp_die('链接地址必须填写');
    
    // 插入数据
    $wpdb->insert($wpdb->links, [
        'link_name' => '【待审核】--- ' . $link_name,
        'link_url' => $link_url,
        'link_target' => '_blank',
        'link_notes' => $link_description,
        'link_visible' => 'N'
    ]);
    wp_die('友情链接提交成功，【等待站长审核】！<a href="/">点此返回</a>');
}

get_header();
?>

<div id="main">
    <div class="content content-link-application">
        <div class="form-header">
            <?php while (have_posts()) : the_post(); ?>
                <?php if(function_exists('cmp_breadcrumbs')) cmp_breadcrumbs(); ?>
                <div class="form-section">
                    <details><summary>✔️免责声明：</summary>
                        本博客遵守相关法律。友链可能包含第三方内容，存在潜在风险。请用户知悉并自行判断。
                    </details>
                    <details><summary>🧀友链交换：</summary>
                        <h2>友链交换须知📌</h2>
                        <ol>
                            <li>能正常访问且包含本站友链</li>
                            <li>网站内容健康，启用SSL证书</li>
                            <li>无logo即用本站logo代替</li>
                        </ol>
                        <h2>我的博客信息ℹ️</h2>
                        <blockquote>
                            <ul>
                                <li><strong>名称：</strong>铭铭的次元站</li>
                                <li><strong>网址：</strong><a href="https://mmxza.com">mmxza.com</a></li>
                                <li><strong>logo：</strong><a href="https://mmxza.com/wp-content/uploads/2025/01/1737090942665.gif" target="_blank">logo地址</a></li>
                                <li><strong>描述：</strong>热爱生活，热爱次元</li>
                            </ul>
                        </blockquote>
                    </details>
                    <details><summary>😼开源页面:</summary>
                    本页面已在github开源,虽然写的是💩
                    
                    <a href="https://github.com/xiaoyusaonian/WordPress-links">点击前往开源仓库</a>
                    
                    </details>
                    <h3>自助申请友链</h3>
                    <form method="post" action="<?= $_SERVER["REQUEST_URI"]; ?>">
                        <div class="form-group">
                            <label for="blink_name"><font color="red">*</font>名称:</label>
                            <input type="text" name="blink_name" placeholder="请输入链接名称" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="blink_url"><font color="red">*</font>链接:</label>
                            <input type="text" name="blink_url" placeholder="请输入链接" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="blink_jianjie">简介:</label>
                            <input type="text" name="blink_jianjie" placeholder="请输入简介" class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="blink_image">logo:</label>
                            <input type="text" name="blink_image" placeholder="请输入logo地址" class="form-control" />
                        </div>
                        <input type="hidden" name="blink_form" value="send" />
                        <button type="submit" class="btn btn-primary">提交申请</button>
                        <button type="reset" class="btn btn-default">重填</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="link-list">
        <?php
        $categories = get_terms('link_category');
        foreach ($categories as $category) :
        ?>
            <div class="category-section">
                <h3><?= $category->name; ?></h3>
                <div class="links-grid">
                    <?php
                    $links = get_bookmarks(['category' => $category->term_id, 'orderby' => 'name', 'hide_invisible' => 1]);
                    foreach ($links as $link) :
                    ?>
                        <div class="link-item">
                            <?php if ($link->link_image) : ?>
                                <a href="<?= $link->link_url; ?>" target="_blank" rel="nofollow">
                                    <img src="<?= $link->link_image; ?>" alt="<?= $link->link_name; ?>" class="link-logo">
                                </a>
                            <?php endif; ?>
                            <div class="link-info">
                                <a href="<?= $link->link_url; ?>" target="_blank" rel="nofollow"><?= $link->link_name; ?></a>
                                <?php if ($link->link_description) : ?>
                                    <p><?= $link->link_description; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
    .friendlink-container { max-width: 1200px; margin: 0 auto; padding: 20px; }
    .links-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; }
    .link-item { border: 1px solid #ddd; padding: 15px; border-radius: 8px; text-align: center; }
    .link-logo { max-width: 150px; height: auto; margin-bottom: 10px; }
    .form-section { background: #f5f5f5; padding: 30px; border-radius: 8px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
    .form-group input { width: 100%; padding: 8px; border: 1px solid #ddd; }
    .btn { padding: 10px 20px; background: #0073e6; color: white; border: none; border-radius: 5px; cursor: pointer; }
    .btn:hover { background: #005bb5; }
</style>

<?php get_footer(); ?>