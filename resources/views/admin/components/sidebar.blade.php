<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">Onelito</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">Ol</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="nav-item {{ $type_menu === 'dashboard' ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ url('admin/dashboard') }}"><i class="fas fa-desktop"></i> <span>Dashboard</span></a>
            </li>
            <li class="menu-header">New</li>
            <li class="nav-item {{ Route::is('admin.pesanan.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.pesanan.index') }}"><i class="fas fa-box"></i> <span>Pesanan</span></a>
            </li>
            <li class="menu-header">Master</li>
            <li class="nav-item {{ $type_menu === 'manage-admin' ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ url('admin/admins') }}"><i class="fas fa-user-group"></i> <span>Admin</span></a>
            </li>
            <li class="nav-item {{ $type_menu === 'manage-member' ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ url('admin/members') }}"><i class="fas fa-users"></i> <span>Member</span></a>
            </li>
            <li class="nav-item {{ Route::is('admin.kategori.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.kategori.index') }}"><i class="fas fa-box"></i> <span>Kategori</span></a>
            </li>
            <li class="nav-item {{ $type_menu === 'manage-currency' ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/currencies') }}"><i class="fas fa-coins"></i> <span>Mata Uang</span></a>
            </li>
            
            <li class="menu-header">Management</li>
            <li class="nav-item {{ $type_menu === 'manage-product' ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ url('admin/products') }}"><i class="fas fa-warehouse"></i> <span>Product</span></a>
            </li>
            <li class="nav-item {{ $type_menu === 'manage-fish' ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ url('admin/fishes') }}"><i class="fas fa-fish"></i> <span>Koi</span></a>
            </li>
            <li class="nav-item {{ $type_menu === 'manage-champion-fish' ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ url('admin/champion-fishes') }}"><i class="fas fa-fish"></i> <span>Champion Koi</span></a>
            </li>
            {{-- <li class="nav-item {{ $type_menu === 'manage-order' ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ url('admin/orders') }}"><i class="fas fa-store"></i> <span>Transaksi Order</span></a>
            </li> --}}
            <li class="nav-item {{ $type_menu === 'manage-banner' ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ url('admin/banners') }}"><i class="fas fa-user-group"></i> <span>Banner</span></a>
            </li>
            <li class="nav-item {{ $type_menu === 'manage-auction-product' ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ url('admin/auction-products') }}"><i class="fas fa-boxes-stacked"></i> <span>Barang Lelang</span></a>
            </li>
            <li class="nav-item {{ $type_menu === 'manage-auction' ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ url('admin/auctions') }}"><i class="fas fa-calendar-days"></i> <span>Auction</span></a>
            </li>
            <li class="nav-item {{ $type_menu === 'current-auction' ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ url('admin/current-auctions') }}"><i class="fas fa-calendar-days"></i> <span>Current Auction</span></a>
            </li>
            <li class="nav-item {{ $type_menu === 'manage-auction-winner' ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ url('admin/auction-winners') }}"><i class="fas fa-user"></i> <span>Pemenang Lelang</span></a>
            </li>
            <li class="menu-header">News</li>
            <li class="nav-item {{ Route::is('admin.tag.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.tag.index') }}"><i class="fas fa-tag"></i> <span>Tag</span></a>
            </li>
            <li class="nav-item {{ Route::is('admin.news.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.news.index') }}"><i class="fas fa-newspaper"></i> <span>News</span></a>
            </li>
            {{-- <li class="menu-header">Lelang Bot</li>
            <li class="nav-item {{ $type_menu === 'bot-member' ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ url('admin/bot/member') }}"><i class="fas fa-users"></i> <span>Data Member</span></a>
            </li>
            <li class="nav-item {{ $type_menu === 'bot-user' ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ url('admin/bot/user') }}"><i class="fas fa-users"></i> <span>Data User</span></a>
            </li>
            <li class="nav-item {{ $type_menu === 'bot-winner' ? 'active' : '' }}">
                <a class="nav-link"
                    href="{{ url('admin/bot/winner') }}"><i class="fas fa-users"></i> <span>Pemenang Lelang</span></a>
            </li> --}}
    </aside>
</div>
