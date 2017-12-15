@if ($showAuthor === true)
    @include('partials.idea-author-box')
@endif

@foreach ($form_fields as $field)
    @if ($field['acf_fc_layout'] == 'file_upload' && is_array($field['value']))
        @include('partials.idea-docs-box')
    @endif
@endforeach

@if ($showSocial === true)
    @include('partials.idea-social-box')
@endif