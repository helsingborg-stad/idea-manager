<div class="grid-xs-12">
    <span class="box box-card">
        <div class="box-content">
            @if (get_field('post_show_author_image') === true && $profileImage)
                <img src="{{ $profileImage }}" alt="{{ get_the_author_meta('nicename') }}" class="box-image">
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
                    @if (!empty(get_the_author_meta('user_email', $author_id)))
                        <li><a class="link-item" href="mailto:{{ get_the_author_meta('user_email', $author_id) }}">{{ get_the_author_meta('user_email', $author_id) }}</a></li>
                    @endif
			   </ul>
            </div>
        </div>
    </span>
</div>