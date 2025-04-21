<div x-data="{
    url: '',
    generate() {
        {{-- @this.set('url', this.url); --}}
        @this.call('generateQr');
    },
    init(){
    this.generate()
    }
}">
    {{-- The whole world belongs to you. --}}
    @if ($qrCode)
            <img src="{{ $qrCode }}" alt="QR Code" />
        @endif
</div>
