<nav id="sidebar">
    <div class="sidebar-header">
        <span class="fs-4 fw-bold" style="color: var(--accent-color);">C</span> 
        <span class="ms-2 fs-5 text-dark fw-bold">CleopatraCoffee</span>
    </div>
    
    <ul class="list-unstyled components">
        <div class="menu-label">Platform</div>
        <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
            <a href="{{ url('admin/dashboard') }}"><i class="bi bi-house-door me-2"></i> Dashboard</a>
        </li>
        <li class="{{ Request::is('admin/kasir') ? 'active' : '' }}">
            <a href="{{ url('admin/kasir') }}"><i class="bi bi-cart2 me-2"></i> Kasir</a>
        </li>
        
        <div class="menu-label mt-3">Manajemen</div>
        <li class="{{ Request::is('admin/kategori*') ? 'active' : '' }}">
            <a href="{{ url('admin/kategori') }}"><i class="bi bi-grid me-2"></i> Kategori</a>
        </li>
        <li class="{{ Request::is('admin/menu*') ? 'active' : '' }}">
            <a href="{{ url('admin/menu') }}"><i class="bi bi-card-list me-2"></i> Menu</a>
        </li>
        <li class="{{ Request::is('admin/meja*') ? 'active' : '' }}">
            <a href="{{ url('admin/meja') }}"><i class="bi bi-layout-text-window me-2"></i> Meja</a>
        </li>
        <li class="{{ Request::is('admin/status-meja') ? 'active' : '' }}">
            <a href="{{ url('admin/status-meja') }}"><i class="bi bi-eye me-2"></i> Status Meja</a>
        </li>
        <li class="{{ Request::is('admin/status-pesanan') ? 'active' : '' }}">
            <a href="{{ url('admin/status-pesanan') }}"><i class="bi bi-clock-history me-2"></i> Status Pesanan</a>
        </li>
        
        <div class="menu-label mt-3">Laporan</div>
        <li class="{{ Request::is('admin/transaksi*') ? 'active' : '' }}">
            <a href="{{ url('admin/transaksi') }}"><i class="bi bi-file-earmark-text me-2"></i> Transaksi</a>
        </li>
        <li class="{{ Request::is('admin/laporan*') ? 'active' : '' }}">
            <a href="{{ url('admin/laporan') }}"><i class="bi bi-bar-chart me-2"></i> Laporan Penjualan</a>
        </li>
    </ul>

    
</nav>