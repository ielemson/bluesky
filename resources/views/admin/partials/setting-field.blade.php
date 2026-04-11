<div class="form-group row">
    <label for="{{ $setting->key }}" class="col-sm-3 col-form-label">
        {{ $setting->display_name }}
        @if($setting->description)
            <br>
            <small class="text-muted">{{ $setting->description }}</small>
        @endif
    </label>
    <div class="col-sm-9">
        @if($setting->type === 'text')
            <input type="text" class="form-control" id="{{ $setting->key }}" 
                   name="{{ $setting->key }}" value="{{ old($setting->key, $setting->value) }}"
                   placeholder="Enter {{ strtolower($setting->display_name) }}">

        @elseif($setting->type === 'textarea')
            <textarea class="form-control" id="{{ $setting->key }}" name="{{ $setting->key }}" 
                      rows="4" placeholder="Enter {{ strtolower($setting->display_name) }}">{{ old($setting->key, $setting->value) }}</textarea>

        @elseif($setting->type === 'image')
            <div class="mb-2">
                @if($setting->value)
                    <img src="{{ $setting->image_url }}" alt="{{ $setting->display_name }}" 
                         class="setting-image-preview img-thumbnail">
                    <div class="mt-2">
                        <a href="{{ route('admin.settings.clear-image', $setting->key) }}" 
                           class="btn btn-sm btn-danger clear-image" data-key="{{ $setting->key }}">
                            <i class="fa fa-trash"></i> Remove Image
                        </a>
                    </div>
                @else
                    <img src="#" alt="Preview" class="setting-image-preview img-thumbnail d-none">
                @endif
            </div>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="{{ $setting->key }}" 
                       name="{{ $setting->key }}" accept="image/*">
                <label class="custom-file-label" for="{{ $setting->key }}">
                    {{ $setting->value ? 'Change image' : 'Choose image' }}
                </label>
            </div>

        @elseif($setting->type === 'color')
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text color-preview" 
                          style="background-color: {{ $setting->value }}"></span>
                </div>
                <input type="color" class="form-control" id="{{ $setting->key }}" 
                       name="{{ $setting->key }}" value="{{ old($setting->key, $setting->value) }}"
                       style="height: 45px;">
            </div>
        @endif

        <!-- Reset Button -->
        <div class="mt-2">
            <a href="{{ route('admin.settings.reset', $setting->key) }}" 
               class="btn btn-sm btn-outline-secondary reset-setting" data-key="{{ $setting->key }}">
                <i class="fa fa-undo"></i> Reset to Default
            </a>
        </div>
    </div>
</div>
<hr>