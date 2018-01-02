@if ($showAuthor === true)
    @include('partials.idea-author-box')
@endif

@foreach ($form_fields as $field)
    @if ($field['acf_fc_layout'] == 'file_upload' && is_array($field['value']))
        @include('partials.idea-docs-box')
    @endif
    @if ($field['acf_fc_layout'] == 'sender-address' && is_array($field['value']))
        @include('partials.idea-map-box')
    @endif
@endforeach

@if ($showSocial === true)
    @include('partials.idea-social-box')
@endif

@if (is_array($relatedIdeas) && !empty($relatedIdeas))
    @include('partials.idea-related-box')
@endif
