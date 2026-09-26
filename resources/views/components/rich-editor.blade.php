@props(['name', 'value' => '', 'placeholder' => 'Start writing…', 'minHeight' => '200px'])
{{--
    Word-style rich-text field (Quill 2 from jsDelivr). Formatting: headings,
    font, size, bold/italic/underline/strike, text & highlight colour,
    alignment, lists, quote, link. The editor HTML is copied into a hidden
    input on every change; the server sanitizes it (App\Support\HtmlSanitizer).
--}}
<div class="rich-editor @error($name) is-invalid @enderror" data-rich-editor data-placeholder="{{ $placeholder }}">
    <div class="rich-editor-area" style="min-height:{{ $minHeight }}">{!! rich_text(old($name, $value), false) !!}</div>
    <input type="hidden" name="{{ $name }}" value="{{ old($name, $value) }}">
</div>
@error($name)<div class="text-danger small mt-1">{{ $message }}</div>@enderror

@once
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&display=swap" rel="stylesheet">
        <style>
            .rich-editor{background:#fff;border-radius:.5rem;}
            .rich-editor.is-invalid .ql-toolbar,.rich-editor.is-invalid .ql-container{border-color:#dc3545;}
            .rich-editor .ql-toolbar{border-radius:.5rem .5rem 0 0;background:#f8f9fb;}
            .rich-editor .ql-container{border-radius:0 0 .5rem .5rem;font-size:15px;font-family:inherit;}
            .rich-editor .ql-editor{min-height:inherit;line-height:1.6;}
            .rich-editor-area{height:auto;}
            .rich-editor .ql-editor h2{font-size:1.6rem;} .rich-editor .ql-editor h3{font-size:1.3rem;} .rich-editor .ql-editor h4{font-size:1.1rem;}
            /* Picker labels for the style-based font & size options */
            @foreach(['Fraunces', 'Georgia', 'Arial', 'Verdana', 'Courier'] as $font)
                .ql-snow .ql-picker.ql-font .ql-picker-label[data-value="{{ $font }}"]::before,
                .ql-snow .ql-picker.ql-font .ql-picker-item[data-value="{{ $font }}"]::before{content:"{{ $font }}";font-family:"{{ $font }}";}
            @endforeach
            .ql-snow .ql-picker.ql-font{width:112px;}
            @foreach(['12px', '14px', '18px', '22px', '28px', '36px'] as $size)
                .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="{{ $size }}"]::before,
                .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="{{ $size }}"]::before{content:"{{ $size }}";}
            @endforeach
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
        <script>
        (function () {
            if (!window.Quill) return;
            // Inline styles (not Quill classes) so the storefront needs no Quill CSS.
            const Size = Quill.import('attributors/style/size');
            Size.whitelist = ['12px', '14px', '18px', '22px', '28px', '36px'];
            Quill.register(Size, true);
            const Font = Quill.import('attributors/style/font');
            Font.whitelist = ['Fraunces', 'Georgia', 'Arial', 'Verdana', 'Courier'];
            Quill.register(Font, true);
            Quill.register(Quill.import('attributors/style/align'), true);

            const toolbar = [
                [{ header: [2, 3, 4, false] }],
                [{ font: [false, ...Font.whitelist] }, { size: [...Size.whitelist.slice(0, 2), false, ...Size.whitelist.slice(2)] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ color: [] }, { background: [] }],
                [{ align: [] }, { list: 'ordered' }, { list: 'bullet' }],
                ['blockquote', 'link'],
                ['clean'],
            ];

            document.querySelectorAll('[data-rich-editor]').forEach(function (wrap) {
                const input = wrap.querySelector('input[type=hidden]');
                const quill = new Quill(wrap.querySelector('.rich-editor-area'), {
                    theme: 'snow',
                    placeholder: wrap.dataset.placeholder,
                    modules: { toolbar: toolbar },
                });
                const sync = function () {
                    input.value = quill.getLength() > 1 ? quill.getSemanticHTML().replace(/&nbsp;/g, ' ') : '';
                };
                quill.on('text-change', sync);
                sync();
            });
        })();
        </script>
    @endpush
@endonce
