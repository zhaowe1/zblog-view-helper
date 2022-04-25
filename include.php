<?php

define('VIEWHELPER_ARTICLE_VIEW_NUM', 'ViewHelper_AddViewNum');

RegisterPlugin('ViewHelper', 'ActivePlugin_ViewHelper');

function ActivePlugin_ViewHelper()
{
    Add_Filter_Plugin('Filter_Plugin_Edit_Response3', 'ViewHelper_AddArticleEditField');

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
    global $article;

    $addViewNum = $article->Metas->HasKey(VIEWHELPER_ARTICLE_VIEW_NUM) ? $article->Metas->GetData(VIEWHELPER_ARTICLE_VIEW_NUM) : '';

    echo '<div class="editmod">
    <label class="editinputname" style="text-overflow:ellipsis;">
        实际浏览量
    </label>
    <input type="number" id="viewHelperArticleRealViewNum" value="' . $article->ViewNums . '" disabled style="width:140px;height: 38px;" />
</div>';

    echo '<div class="editmod">
    <label for="viewHelperArticleAddViewNum" class="editinputname" style="text-overflow:ellipsis;">
        增加浏览量
    </label>
    <input type="number" name="meta_ViewHelper_AddViewNum" id="viewHelperArticleAddViewNum" value="' . $addViewNum . '" style="width:140px;height: 38px;" />
</div>';
}

/**
 * 判断指定文章增加多少浏览量
 * @param $article
 * @return int
 */
function ViewHelper_AddViewNum($article)
{
    global $zbp;

    if ($article->Metas->HasKey(VIEWHELPER_ARTICLE_VIEW_NUM)) {
        return intval($article->Metas->GetData(VIEWHELPER_ARTICLE_VIEW_NUM));
    }

    $keyArr = array(
        'category_' . $article->CateID,
        'global'
    );
    foreach ($keyArr as $key) {
        if ($zbp->Config('ViewHelper')->HasKey($key) && intval($zbp->Config('ViewHelper')->$key) > 0) {
            return intval($zbp->Config('ViewHelper')->$key);
        }
    }

    return 0;
}
