<div id="menu{{ $menuItem->menu_id }}" class="menu-item">
    <div class="d-flex flex-row">
        @if ($showMenuImages == 1 && $menuItemObject->hasThumb)
            <div
                class="col-3 p-0 me-3 menu-item-image align-self-center"
                style="
                    background: url('{{ $menuItem->getThumb() }}') no-repeat center center;
                    background-size: cover;
                    width: {{$menuImageWidth}}px;
                    height: {{$menuImageHeight}}px;
                    ">
            </div>
        @endif

        <div class="menu-content flex-grow-1 me-3">
            <h6 class="menu-name">{{ $menuItem->name }}</h6>
            <p class="menu-desc text-muted mb-0">
                {!! nl2br($menuItem->description) !!}
            </p>
        </div>
        <div class="menu-detail d-flex justify-content-end col-3 p-0">

            <div class="menu-price pe-3">
                <b>{!! currency_format($menuItem->price) !!}</b>
            </div>

            @isset ($updateCartItemEventHandler)
                <div class="menu-button">
                    @partial('@button', ['menuItem' => $menuItem, 'menuItemObject' => $menuItemObject ])
                </div>
            @endisset
        </div>
    </div>
    
</div>
