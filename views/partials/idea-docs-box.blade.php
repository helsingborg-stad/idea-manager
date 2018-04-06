<div class="grid-xs-12">
    <h4 class="box-title">{{ $field['label'] }}</h4>
    <span class="box box-card">
        <div class="box-content">
            @foreach ($field['value'] as $file)
                <a href="{{ file_exists($file) ? $uploadFolder . basename($file) : $file }}" class="link-item link" target="_blank">{{ file_exists($file) ? IdeaManager\Helper\File::cleanFileName($file) : $file }}</a><br>
            @endforeach
        </div>
    </span>
</div>
