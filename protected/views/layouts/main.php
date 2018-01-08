<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta charset="utf-8"/>
    <title><?php
        echo Yii::app()->name;
        if (is_array($this->breadcrumbs)) {
            echo ' - ';
            foreach ($this->breadcrumbs as $k => $b) {
                ($k != 0) && print " - ";
                if (is_integer($k) && !is_array($b)) echo $b; else echo $k;
            }
        } else {
            echo $this->pageTitle;
        }
        ?></title>
    <meta name="description" content="overview &amp; stats"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="/assets/css/font-awesome.min.css"/>
    <!-- page specific plugin styles -->
    <!-- text fonts -->
    <link rel="stylesheet" href="/assets/css/ace-fonts.css"/>
    <!-- ace styles -->
    <link rel="stylesheet" href="/assets/css/ace.min.css"/>
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="/assets/css/ace-part2.min.css"/>
    <![endif]-->
    <link rel="stylesheet" href="/assets/css/ace-skins.min.css"/>
    <link rel="stylesheet" href="/assets/css/ace-rtl.min.css"/>
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="/assets/css/ace-ie.min.css"/>
    <![endif]-->
    <?php if (file_exists(Yii::app()->basePath . '/../css/styles.css')) { ?>
        <link rel="stylesheet" href="/css/styles.css"/>
    <?php } ?>
    <?php if (isset($this->module)) { ?>
        <?php if (file_exists(Yii::app()->basePath . '/../css/' . $this->module->id . '/styles.css')) { ?>
            <link rel="stylesheet" href="/css/<?php echo $this->module->id; ?>/styles.css"/>
        <?php } ?>
        <?php if (file_exists(Yii::app()->basePath . '/../css/' . $this->module->id . '/' . $this->id . '/styles.css')) { ?>
            <link rel="stylesheet" href="/css/<?php echo $this->module->id; ?>/<?php echo $this->id; ?>/styles.css"/>
        <?php } ?>
        <?php if (file_exists(Yii::app()->basePath . '/../css/' . $this->module->id . '/' . $this->id . '/' . $this->action->id . '/styles.css')) { ?>
            <link rel="stylesheet"
                  href="/css/<?php echo $this->module->id; ?>/<?php echo $this->id; ?>/<?php echo $this->action->id; ?>/styles.css"/>
        <?php } ?>
    <?php } else { ?>
        <?php if (file_exists(Yii::app()->basePath . '/../css/' . Yii::app()->controller->id . '/styles.css')) { ?>
            <link rel="stylesheet" href="/css/<?php echo Yii::app()->controller->id; ?>/styles.css"/>
        <?php } ?>
        <?php if (file_exists(Yii::app()->basePath . '/../css/' . Yii::app()->controller->id . '/' . $this->action->id . '/styles.css')) { ?>
            <link rel="stylesheet"
                  href="/css/<?php echo Yii::app()->controller->id; ?>/<?php echo $this->action->id; ?>/styles.css"/>
        <?php } ?>
    <?php } ?>
    <!-- inline styles related to this page -->
    <!-- ace settings handler -->
    <script src="/assets/js/ace-extra.min.js"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lte IE 8]>
    <script src="/assets/js/html5shiv.js"></script>
    <script src="/assets/js/respond.min.js"></script>
    <![endif]-->
</head>

<body class="no-skin">
<!-- #section:basics/navbar.layout -->
<div id="navbar" class="navbar navbar-default">
    <script type="text/javascript">
        try {
            ace.settings.check('navbar', 'fixed')
        } catch (e) {
        }
    </script>
    <div class="navbar-container" id="navbar-container">
        <!-- #section:basics/sidebar.mobile.toggle -->
        <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler">
            <span class="sr-only">Toggle sidebar</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="navbar-header pull-left">
            <a href="/" class="navbar-brand">
                <small>
                    <!--
                    <i class="fa fa-leaf"></i>
                    -->
                    <?php echo Yii::app()->name; ?>
                </small>
            </a>
        </div>
<<<<<<< Updated upstream
        <?php $this->widget('application.components.HeadRbac', ['moduleObj' => $this]); ?>
=======
        <div class="navbar-buttons navbar-header pull-left" role="navigation">
            <ul class="nav ace-nav">
                <li class="mod-nav grey<?php if (!isset($this->module)) { ?> active<?php } ?>">
                    <a class="global" href="<?php echo $this->createUrl('/'); ?>">全站</a>
                </li>
                <li class="mod-nav grey<?php if (isset($this->module) && $this->module->id == 'weixin') { ?> active<?php } ?>">
                    <a class="weixin" href="<?php echo $this->createUrl('/weixin'); ?>">微信</a>
                </li>
                <li class="mod-nav grey<?php if (isset($this->module) && $this->module->id == 'app') { ?> active<?php } ?>">
                    <a class="app" href="<?php echo $this->createUrl('/app'); ?>">APP</a>
                </li>
                <li class="mod-nav grey<?php if (isset($this->module) && $this->module->id == 'mqq') { ?> active<?php } ?>">
                    <a class="pc" href="<?php echo $this->createUrl('/mqq'); ?>">MQQ</a>
                </li>
                <li class="mod-nav grey<?php if (isset($this->module) && $this->module->id == 'tool') { ?> active<?php } ?>">
                    <a class="pc" href="<?php echo $this->createUrl('/tool'); ?>">营销工具</a>
                </li>
                <li class="mod-nav grey<?php if (isset($this->module) && $this->module->id == 'message') { ?> active<?php } ?>">
                    <a class="pc" href="<?php echo $this->createUrl('/message'); ?>">消息中心</a>
                </li>
                <li class="mod-nav grey<?php if (isset($this->module) && $this->module->id == 'liveshow') { ?> active<?php } ?>">
                    <a class="pc" href="<?php echo $this->createUrl('/liveshow/liveshow/index'); ?>">明星直播</a>
                </li>
            </ul>
        </div>
>>>>>>> Stashed changes
        <div class="navbar-buttons navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav">
                <!-- #section:basics/navbar.user_menu -->
                <li class="light-blue">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <!--
                        <img class="nav-user-photo" src="../assets/avatars/user.jpg" alt="Jason's Photo">
                        -->
                        <span class="user-info">
							<small>你好,</small>
                            <?php echo Yii::app()->getUser()->getName(); ?>
						</span>
                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>
                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        <li>
                            <a href="/user/profile">
                                <i class="ace-icon fa fa-user"></i>
                                个人资料
                            </a>
                        </li>
                        <li>
                            <a href="/site/logout">
                                <i class="ace-icon fa fa-power-off"></i>
                                注销
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- /section:basics/navbar.user_menu -->
            </ul>
        </div>
    </div>
    <!-- /.navbar-container -->
</div>
<!-- /section:basics/navbar.layout -->
<div class="main-container" id="main-container">
    <script type="text/javascript">
        try {
            ace.settings.check('main-container', 'fixed')
        } catch (e) {
        }
    </script>
    <!-- #section:basics/sidebar -->
    <div id="sidebar" class="sidebar responsive">
        <script type="text/javascript">
            try {
                ace.settings.check('sidebar', 'fixed')
            } catch (e) {
            }
        </script>
        <ul class="nav nav-list">
            <li class="<?php echo $this->id == 'site' && $this->getAction()->getId() == 'index' ? 'active' : ''; ?>">
                <a href="/">
                    <i class="menu-icon fa fa-tachometer"></i>
                    <span class="menu-text"> Dashboard </span>
                </a>
                <b class="arrow"></b>
            </li>
            <li>
                <!--<a data-toggle="modal" data-target="#myModalMap" >-->
                <a onclick="ModuleMapShow()">
                    <i class="menu-icon fa fa-map-marker"></i>
                    <span class="menu-text"> 模块地图 </span>
                </a>
                <b class="arrow"></b>
            </li>
            <?php if (isset($this->module) && $this->module->id == 'weixin') { ?>
                <?php $this->widget('application.components.HeadRbac', ['moduleObj' => $this, 'parentIndex' => 'weixin']); ?>
            <?php } else if (isset($this->module) && $this->module->id == 'app') { ?>
                <?php $this->widget('application.components.HeadRbac', ['moduleObj' => $this, 'parentIndex' => 'app']); ?>
            <?php } else if (isset($this->module) && $this->module->id == 'mqq') { ?>
                <?php $this->widget('application.components.HeadRbac', ['moduleObj' => $this, 'parentIndex' => 'mqq']); ?>
            <?php } else if (isset($this->module) && $this->module->id == 'tool') { ?>
                <?php $this->widget('application.components.HeadRbac', ['moduleObj' => $this, 'parentIndex' => 'tool']); ?>
            <?php } else if (isset($this->module) && $this->module->id == 'message') { ?>
<<<<<<< Updated upstream
                <?php $this->widget('application.components.HeadRbac', ['moduleObj' => $this, 'parentIndex' => 'message']); ?>
            <?php } else if (isset($this->module) && $this->module->id == 'message') { ?>
                <?php $this->widget('application.components.HeadRbac', ['moduleObj' => $this, 'parentIndex' => 'message']); ?>
            <?php }else if (isset($this->module) && $this->module->id == 'show') { ?>
                <?php $this->widget('application.components.HeadRbac', ['moduleObj' => $this, 'parentIndex' => 'show']); ?>
            <?php } else if (isset($this->module) && $this->module->id == 'weixinsp') { ?>
                <?php $this->widget('application.components.HeadRbac', ['moduleObj' => $this, 'parentIndex' => 'weixinsp']); ?>
=======

                <li class="<?php echo $this->id == 'resource' ? 'active open' : ''; ?>">
                    <a href="/message/message/create">
                        <i class="menu-icon glyphicon glyphicon-picture"></i>
                        <span class="menu-text"> 新建消息 </span>
                    </a>
                </li>
                <li class="<?php echo $this->id == 'resource' ? 'active open' : ''; ?>">
                    <a href="/message/usertag/index">
                        <i class="menu-icon glyphicon glyphicon-picture"></i>
                        <span class="menu-text"> 标签管理 </span>
                    </a>
                </li>
                <li class="<?php echo $this->id == 'resource' ? 'active open' : ''; ?>">
                    <a href="/message/message/status">
                        <i class="menu-icon glyphicon glyphicon-picture"></i>
                        <span class="menu-text"> 发送状态 </span>
                    </a>
                </li>

>>>>>>> Stashed changes
            <?php } else { ?>
                <?php $this->widget('application.components.HeadRbac', ['moduleObj' => $this, 'parentIndex' => '/']); ?>
            <?php } ?>
        </ul>
        <!-- 板块地图-->
        <div class="modal inmodal fade" id="myModalMap" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                                class="sr-only">Close</span></button>
                        <h4 class="modal-title">bayMax模块地图</h4>
                    </div>
                    <div class="modal-body" >
                        <div class="panel-group" id="MapAccordion">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.nav-list -->
        <!-- #section:basics/sidebar.layout.minimize -->
        <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
            <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left"
               data-icon2="ace-icon fa fa-angle-double-right"></i>
        </div>
        <!-- /section:basics/sidebar.layout.minimize -->
        <script type="text/javascript">
            //try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
        </script>
    </div>
    <!-- /section:basics/sidebar -->
    <div class="main-content">
        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try {
                    ace.settings.check('breadcrumbs', 'fixed')
                } catch (e) {
                }
            </script>
            <!-- 面包屑 -->
            <?php
            $this->widget('zii.widgets.CBreadcrumbs', array(
                'homeLink' => '<li><i class="ace-icon fa fa-home home-icon"></i>' . CHtml::link('首页', Yii::app()->homeUrl) . '</li>',
                'links' => $this->breadcrumbs,
                'tagName' => 'ul',
                'activeLinkTemplate' => '<li><a href="{url}">{label}</a></li>',
                'inactiveLinkTemplate' => '<li>{label}</li>',
                'separator' => '',
                'htmlOptions' => array('class' => 'breadcrumb')
            )); ?>
            <!-- /面包屑 -->
        </div>
        <div class="page-content">
            <!-- /.page-header -->
            <?php foreach (Yii::app()->user->getFlashes() as $key => $message) { ?>
                <?php $key = str_replace('error', 'danger', $key); ?>
                <div class="alert alert-<?php echo $key; ?>">
                    <button type="button" class="close" data-dismiss="alert">
                        <i class="ace-icon fa fa-times"></i>
                    </button>
                    <?php if ($key == 'danger') { ?>
                        <i class="ace-icon fa fa-exclamation-triangle red"></i>
                    <?php } else if ($key == 'warning') { ?>
                        <i class="ace-icon fa fa-exclamation-triangle yellow"></i>
                    <?php } else if ($key == 'success') { ?>
                        <i class="ace-icon fa fa-check green"></i>
                    <?php } else if ($key == 'info') { ?>
                        <i class="ace-icon fa fa-exclamation-triangle yellow"></i>
                    <?php } ?>
                    <?php echo $message; ?>
                    <br>
                </div>
            <?php } ?>
            <?php echo $content; ?>
        </div>
        <!-- /.page-content -->
    </div>
    <!-- /.main-content -->
    <div class="footer">
        <div class="footer-inner">
            <!-- #section:basics/footer -->
            <div class="footer-content">
				<span class="bigger-120">
					<span class="blue bolder"><?php echo Yii::app()->name; ?></span>
                    <?php echo Yii::app()->params['company']; ?> &copy; 2014-<?php echo date('Y'); ?>
				    </span>
                </span>
            </div>
            <!-- /section:basics/footer -->
        </div>
    </div>
    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
    </a>
</div>
<!-- /.main-container -->
<!-- basic scripts -->
<!--[if !IE]> -->
<script type="text/javascript">
    window.jQuery || document.write("<script src='/assets/js/jquery.min.js'>" + "<" + "/script>");
</script>
<!-- <![endif]-->
<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='/assets/js/jquery1x.min.js'>" + "<" + "/script>");
</script>
<![endif]-->
<script type="text/javascript">
    if ('ontouchstart' in document.documentElement) document.write("<script src='/assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
</script>
<script src="/assets/js/bootstrap.min.js"></script>
<!-- ace scripts -->
<script src="/assets/js/ace-elements.min.js"></script>
<script src="/assets/js/ace.min.js"></script>
<script type="text/javascript"> ace.vars['base'] = '..'; </script>
<script src="/assets/js/ace/ace.onpage-help.js"></script>

<?php if (file_exists(Yii::app()->basePath . '/../js/scripts.js')) { ?>
    <script src="/js/scripts.js"></script>
    <?php Yii::app()->getClientScript()->registerScriptFile("/js/scripts.js", CClientScript::POS_END); ?>
<?php } ?>
<?php if (isset($this->module)) { ?>
    <?php if (file_exists(Yii::app()->basePath . '/../js/' . $this->module->id . '/scripts.js')) { ?>
        <?php Yii::app()->getClientScript()->registerScriptFile("/js/" . $this->module->id . "/scripts.js", CClientScript::POS_END); ?>
    <?php } ?>
    <?php if (file_exists(Yii::app()->basePath . '/../js/' . $this->module->id . '/' . $this->id . '/scripts.js')) { ?>
        <?php Yii::app()->getClientScript()->registerScriptFile("/js/" . $this->module->id . "/" . $this->id . "/scripts.js", CClientScript::POS_END); ?>
    <?php } ?>
    <?php if (file_exists(Yii::app()->basePath . '/../js/' . $this->module->id . '/' . $this->id . '/' . $this->action->id . '/scripts.js')) { ?>
        <?php Yii::app()->getClientScript()->registerScriptFile("/js/" . $this->module->id . "/" . $this->id . "/" . $this->action->id . "/scripts.js", CClientScript::POS_END); ?>
    <?php } ?>
<?php } else { ?>
    <?php if (file_exists(Yii::app()->basePath . '/../js/' . Yii::app()->controller->id . '/scripts.js')) { ?>
        <?php Yii::app()->getClientScript()->registerScriptFile("/js/" . Yii::app()->controller->id . "/scripts.js", CClientScript::POS_END); ?>
    <?php } ?>
    <?php if (file_exists(Yii::app()->basePath . '/../js/' . Yii::app()->controller->id . '/' . $this->action->id . '/scripts.js')) { ?>
        <?php Yii::app()->getClientScript()->registerScriptFile("/js/" . Yii::app()->controller->id . "/" . $this->action->id . "/scripts.js", CClientScript::POS_END); ?>
    <?php } ?>
<?php } ?>
</body>
<script type="text/javascript">
    function ModuleMapShow() {
        $('#MapAccordion').load('/ModuleMap/mapIndex', function () {
            $('#myModalMap').modal('show');
        })

    }
</script>
</html>

