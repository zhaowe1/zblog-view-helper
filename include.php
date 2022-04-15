<?php

RegisterPlugin('ViewHelper', 'ActivePlugin_ViewHelper');

function ActivePlugin_ViewHelper()
{
    Add_Filter_Plugin('Filter_Plugin_Edit_Response3', 'ViewHelper_AddArticleEditField');
    Add_Filter_Plugin('Filter_Plugin_PostArticle_Succeed', 'ViewHelper_SaveArticleAddViewNum');

    Add_Filter_Plugin('Filter_Plugin_ViewPost_Template', 'ViewHelper_updatePageViewOnPost');
    Add_Filter_Plugin('Filter_Plugin_ViewList_Template', 'ViewHelper_updatePageViewOnListAndSearch');
    Add_Filter_Plugin('Filter_Plugin_ViewSearch_Template', 'ViewHelper_updatePageViewOnListAndSearch');
}

/**
 * 前台文章页浏览量修改
 * @param $template
 */
function ViewHelper_updatePageViewOnPost(&$template)
{
    $template->templateTags['article']->ViewNums += ViewHelper_AddViewNum($template->templateTags['article']);
}

/**
 * 前台列表和搜索页流量量修改
 * @param $template
 */
function ViewHelper_updatePageViewOnListAndSearch(&$template)
{
    foreach ($template->templateTags['articles'] as &$article) {
        $article->ViewNums += ViewHelper_AddViewNum($article);
    }
}

/**
 * 后台文章编辑页输出字段
 */
function ViewHelper_AddArticleEditField()
{
    global $zbp;
    global $article;

    $configKey = 'article_' . $article->ID;
    $addViewNum = $zbp->config('ViewHelper')->HasKey($configKey) ? $zbp->config('ViewHelper')->$configKey : '';

    echo '<div class="editmod">
    <label class="editinputname" style="text-overflow:ellipsis;">
        实际浏览量
    </label>
    <input type="text" id="viewHelperArticleRealViewNum" value="' . $article->ViewNums . '" disabled style="width:140px;" />
</div>';

    echo '<div class="editmod">
    <label for="viewHelperArticleAddViewNum" class="editinputname" style="text-overflow:ellipsis;">
        增加浏览量
    </label>
    <input type="text" name="ArticleAddViewNum" id="viewHelperArticleAddViewNum" value="' . $addViewNum . '" style="width:140px;" />
</div>';
}

/**
 * 后台文章编辑页保存
 */
function ViewHelper_SaveArticleAddViewNum()
{
    global $zbp;

    $configKey = 'article_' . GetVars('ID');

    if (GetVars('ArticleAddViewNum') === '') {
        $zbp->config('ViewHelper')->Delkey($configKey);
    } else {
        $zbp->config('ViewHelper')->$configKey = intval(GetVars('ArticleAddViewNum'));
    }
    $zbp->SaveConfig('ViewHelper');
}

/**
 * 判断指定文章增加多少浏览量
 * @param $article
 * @return int
 */
function ViewHelper_AddViewNum($article)
{
    global $zbp;

    $keyArr = array(
        'article_' . $article->ID,
        'category_' . $article->CateID,
        'global'
    );

    foreach ($keyArr as $key) {
        if ($zbp->config('ViewHelper')->hasKey($key)) {
            return intval($zbp->config('ViewHelper')->$key);
        }
    }

    return 0;
}
