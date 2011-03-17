        <h2>标签 :</h2>
        <?php if (!Doo::cache('front')->getPart('sidebarTag', 300)): ?>
<?php Doo::cache('front')->start('sidebarTag'); ?>
        <?php foreach($data['randomTags'] as $k1=>$v1): ?>
        <span class="tag"><a href="<?php echo $data['rootUrl']; ?>blog/tag/<?php echo $v1->name; ?>"><?php echo $v1->name; ?></a></span>
        <?php endforeach; ?>
        
<?php Doo::cache('front')->end(); ?>
<?php endif; ?>

        <h2>归档</h2>
        <ul>
          <?php if (!Doo::cache('front')->getPart('sidebarArchive', 31536000)): ?>
<?php Doo::cache('front')->start('sidebarArchive'); ?>
          <?php foreach($data['archives'] as $k1=>$v1): ?>
              <?php foreach($v1 as $k2=>$v2): ?>
                <li>
                    <a href="<?php echo $data['rootUrl']; ?>blog/archive/<?php echo $k1; ?>/<?php echo $k2; ?>">
                        <span><?php echo month($k2); ?></span> <?php echo $k1; ?>
                    </a>
                    (<?php echo $v2; ?>)
                </li>
              <?php endforeach; ?>
          <?php endforeach; ?>
          
<?php Doo::cache('front')->end(); ?>
<?php endif; ?>
        </ul>

        <br/>
		<img src="<?php echo $data['rootUrl']; ?>global/img/ads.png" alt="advertisement"/>