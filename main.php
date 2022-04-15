<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';

$zbp->Load();
$action = 'root';

if (!$zbp->CheckRights($action)) {
    $zbp->ShowError(6);
    die();
}
if (!$zbp->CheckPlugin('ViewHelper')) {
    $zbp->ShowError(48);
    die();
}

if (count($_POST) > 0) {
    CheckIsRefererValid();
}

$blogtitle = '浏览量助手';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';


$configArr = $zbp->config('ViewHelper');

?>
    <div id="divMain">
        <div class="divHeader"><?php echo $blogtitle; ?></div>
        <div class="SubMenu">
        </div>
        <div id="divMain2">
            <form method="post" action="save_setting.php">
                <div class="content-box">
                    <!-- Start Content Box -->
                    <div class="content-box-header">
                        <ul class="content-box-tabs">
                            <li><a href="#tab1" class="default-tab"><span>分类设置</span></a></li>
                            <li><a href="#tab2"><span>全局设置</span></a></li>
                            <li><a href="#tab3"><span>使用说明</span></a></li>
                        </ul>
                        <div class="clear"></div>
                    </div>
                    <!-- End .content-box-header -->
                    <div class="content-box-content">
                        <div class="tab-content default-tab" id="tab1">
                            <table class="tableFull table_hover table_striped">
                                <thead>
                                <tr>
                                    <th class="td25">
                                        分类名称
                                    </th>
                                    <th>
                                        增加浏览量
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($zbp->categories_all as $c):
                                    ?>
                                    <tr>
                                        <td class="td25">
                                            <p>
                                                <b><?php echo $c->Name ?></b>
                                                <br>
                                                <span class="note"><?php echo $c->Alias ?></span>
                                            </p>
                                        </td>
                                        <td>
                                            <input type="text" class="text-config" name="category_<?php echo $c->ID ?>" value="<?php $field = 'category_' . $c->ID; echo !empty($configArr->$field) ? $configArr->$field : 0 ?>">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-content" id="tab2">
                            <table class="tableFull table_hover table_striped">
                                <tr>
                                    <td class="td25">
                                        <p><b>全局增加</b></p>
                                    </td>
                                    <td>
                                        <input type="text" class="text-config" name="global" value="<?php echo !empty($configArr->global) ? $configArr->global : 0 ?>">
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="tab-content" id="tab3">
                            <div style="padding: 10px">
                                <p>此插件并不会真实的修改文章的真实浏览量，而是在真实浏览量上增加一定数量的值。</p>
                                <p>如果文章页设置了浏览量字段，那么就以这个值为准，分类和全局的设置就不生效了。</p>
                                <p>显示优先级：</p>
                                <p>
                                    1、文章编辑页面的浏览量字段<br>
                                    2、分类设置下的统一增加值<br>
                                    3、全局设置下的统一增加值<br>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <input type="hidden" name="csrfToken" value="<?php echo $zbp->GetCSRFToken();?>">
                <p><input type="submit" class="button" value="<?php echo $zbp->lang['msg']['submit']; ?>"></p>
            </form>
        </div>
    </div>

<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>