<div class="grid-xs-12">
    <span class="box box-card">
        <div class="box-content">
            @if($authorUrl)
                <a href="{{ $authorUrl }}">
            @endif
            @if (get_field('post_show_author_image') === true && $profileImage)
                <img src="{{ $profileImage }}" alt="{{ get_the_author_meta('nicename') }}" class="box-image">
            @endif
                @if (!empty(get_the_author_meta('first_name', $author_id)) && !empty(get_the_author_meta('last_name', $author_id)))
                    <h5>{{ get_the_author_meta('first_name', $author_id) }} {{ get_the_author_meta('last_name', $author_id) }}</h5>
                @else
                    <h5>{{ get_the_author() }}</h5>
                @endif
                @if($authorUrl)
                    </a>
                @endif
                <ul>
                    @if ($unit)
                        <li class="card-title"><small>{{ $unit->name }}</small></li>
                    @endif
                    @if (!empty(get_the_author_meta('user_email', $author_id)))
                        <li><a class="link-item" href="mailto:{{ get_the_author_meta('user_email', $author_id) }}">{{ get_the_author_meta('user_email', $author_id) }}</a></li>
                    @endif
			   </ul>
        </div>
    </span>
</div>
