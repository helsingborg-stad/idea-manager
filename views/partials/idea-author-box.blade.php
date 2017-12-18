<div class="grid-xs-12">
    <span class="box box-card">
        <div class="box-content">
            @if (get_field('post_show_author_image') === true && get_avatar_url(get_the_author_meta('ID')))
                <img src="{{ get_avatar_url(get_the_author_meta('ID')) }}" alt="{{ get_the_author_meta('nicename') }}" class="box-image">
            @endif
            <div class="box-content">
                @if (!empty(get_the_author_meta('first_name', $author_id)) && !empty(get_the_author_meta('last_name', $author_id)))
                    <h5>{{ get_the_author_meta('first_name', $author_id) }} {{ get_the_author_meta('last_name', $author_id) }}</h5>
                @else
                    <h5>{{ get_the_author() }}</h5>
                @endif
                <ul>
                    @if ($units)
                        @foreach($units as $unit)
                            <li class="card-title">{{ $unit->name }}</li>
                        @endforeach
                    @endif
                    @if (!empty(get_the_author_meta('user_email')))
                        <li><a class="link-item" href="mailto:{{ get_the_author_meta('user_email') }}">{{ get_the_author_meta('user_email') }}</a></li>
                    @endif
			   </ul>
            </div>
        </div>
    </span>
</div>