<div id="faceribbon">
  <div id="faceribbon-wrapper">
    <div id="faceribbon-inner">
          <div id="faceribbon-nav-prev" class="trFaceribbonNav" data-link="<?php echo J::url('/site/ribbon.loadFaces')?>" data-direction="prev"></div>
          <div id="faceribbon-faces-wrapper" data-page="<?php echo $page?>" data-lastpage="<?php echo $isLastPage ?>">
              <?php $this->render('theme.views.widgets.jmainribbon._faces', array(
                      'users' => $users,
                      'page' => $page,
                      'pageSize' => $pageSize,
                      'isLastPage' => $isLastPage,
              ));?>
          </div>
          <div id="faceribbon-nav-next" class="trFaceribbonNav" data-link="<?php echo J::url('/site/ribbon.loadFaces')?>" data-direction="next"></div>
    </div>
  </div>
</div>