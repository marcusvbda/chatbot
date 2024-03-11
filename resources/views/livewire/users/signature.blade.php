<div style="display:flex;color:rgba(0,0,0,0.54);font-family:'Uni Neue',Roboto,'Helvetica Neue',Arial,sans-serif;">
    <div style="margin-right:15px;">
        @if ($avatarUrl)
            <img src="{{ $avatarUrl }}" width="70" height="70" alt="" style="border-radius:100%;" />
        @else
            <img src="{{ url('/images/user-placeholder.png') }}" width="70" height="70" alt=""
                style="border-radius:100%;" />
        @endif
    </div>
    <div>
        <h2 style="font-size:18px;color:rgb(34,34,102);">{{ $name }}</h2>
        @if ($position)
            <h3 style="font-size:16px;color:rgb(86,37,242);text-transform:capitalize;">{{ $position }}</h3>
        @endif
        <p><a href="mailto:{{ $email }}" target="_blank">{{ $email }}</a></p>
        <p style="font-size:14px;color:rgb(63,63,68);">{{ $phone }}</p>
    </div>
</div>
<div
    style="padding-top:7px;background-image:url('https://encurtador.com.br/vKPZ8');background-repeat:no-repeat;width:431px;height:123px;color:rgba(0,0,0,0.54);font-family:'Uni Neue',Roboto,'Helvetica Neue',Arial,sans-serif;">
    <div style="display:flex;gap:1px;display:flex;gap:1px;margin-left:83px;">
        @if ($linkedin)
            <a href="{{ $linkedin }}" title="LinkedIn" target="_blank">
                <img src="https://encurtador.com.br/nCENX" width="24" height="24" alt="LinkedIn"
                    style="margin-right:5px;" />
            </a>
        @endif
        @if ($whatsapp)
            <a href="https://wa.me/55{{ preg_replace('/\D/', '', $whatsapp) }}" title="WhatsApp" target="_blank">
                <img src="https://encurtador.com.br/cdgpY" width="24" height="24" alt="WhatsApp"
                    style="margin-right:5px;" />
            </a>
        @endif
        @if ($instagram)
            <a href="{{ $instagram }}" title="Instagram" target="_blank">
                <img src="https://encurtador.com.br/gyFLZ" width="24" height="24" alt="Instagram"
                    style="margin-right:5px;" />
            </a>
        @endif
    </div>
    <a href="https://diwe.com.br" title="DIWE" target="_blank" style="display:inline-block;height:116px;width:249px;">
    </a>
</div>
