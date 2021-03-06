<div class="grid-xs-12">
    <h4 class="box-title"><?php _e('Share page', 'idea-manager'); ?></h4>
    <span class="box box-card">
        <div class="box-content">
            <div class="grid share share-social share-social-icon-lg share-no-labels">
                <div class="grid-xs-3">
                    <a class="share-social-facebook" data-action="share-popup" href="https://www.facebook.com/sharer/sharer.php?u={!! urlencode(get_permalink()) !!}" data-tooltip="<?php _e('Share on', 'idea-manager'); ?> Facebook">
                        <i class="pricon pricon-facebook"></i>
                        <span><?php _e('Share on', 'idea-manager'); ?> Facebook</span>
                    </a>
                </div>
                <div class="grid-xs-3">
                    <a class="share-social-twitter" data-action="share-popup" href="http://twitter.com/share?url={!! urlencode(get_permalink()) !!}" data-tooltip="<?php _e('Share on', 'idea-manager'); ?> Twitter">
                        <i class="pricon pricon-twitter"></i>
                        <span><?php _e('Share on', 'idea-manager'); ?> Twitter</span>
                    </a>
                </div>
                <div class="grid-xs-3">
                    <a class="share-social-linkedin" data-action="share-popup" href="https://www.linkedin.com/shareArticle?mini=true&amp;url={!! urlencode(get_permalink()) !!}&amp;title={{ urlencode(get_the_title()) }}" data-tooltip="<?php _e('Share on', 'idea-manager'); ?> LinkedIn">
                        <i class="pricon pricon-linkedin"></i>
                        <span><?php _e('Share on', 'idea-manager'); ?> LinkedIn</span>
                    </a>
                </div>
                <div class="grid-xs-3">
                    <a class="share-social-email" data-action="share-email" href="#modal-target-{{ get_the_ID() }}" data-tooltip="<?php _e('Share with e-mail', 'idea-manager'); ?>">
                        <i class="pricon pricon-email"></i>
                        <span><?php _e('Share with e-mail', 'idea-manager'); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </span>
</div>
