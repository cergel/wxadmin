<nav class="pagination" role="navigation" style="margin-top: 0px; margin-bottom: 0px;">
    <?php if ($paginator->onFirstPage()) { ?>
        <a type="button" class="btn btn-white" href="<?php echo $paginator->firstUrl() ?>">
            <i class="fa fa-angle-double-left"></i>
        </a>
        <a type="button" class="btn btn-white" href="<?php echo $paginator->previousPageUrl() ?>">
            <i class="fa fa-angle-left"></i>
        </a>
    <?php } ?>
    <a class="btn btn-white" readonly="true">第<?php echo $paginator->currentPage() ?>页 ⁄ 共 <?php echo $paginator->lastPage() ?> 页
    </a>
    <?php if ($paginator->hasMorePages()) { ?>
        <a type="button" class="btn btn-white" href="<?php echo $paginator->nextPageUrl() ?>">
            <i class="fa fa-angle-right"></i>
        </a>
        <a type="button" class="btn btn-white" href="<?php echo $paginator->lastUrl() ?>">
            <i class="fa fa-angle-double-right"></i>
        </a>
    <?php } ?>
</nav>
