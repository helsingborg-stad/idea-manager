<div class="grid-xs-12">
    <h4 class="box-title"><?php _e('Related ideas', 'idea-manager'); ?></h4>
    <span class="box box-panel">
         <ul>
             @foreach($relatedIdeas as $post)
                 <li>
                     <a href="{{ get_the_permalink($post->ID) }}" rel="bookmark" title="{{ get_the_title($post->ID) }}">{{ get_the_title($post->ID) }}</a>
                 </li>
             @endforeach
        </ul>
    </span>
</div>
