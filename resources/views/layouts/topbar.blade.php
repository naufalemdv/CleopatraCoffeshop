<div class="topbar d-flex justify-content-between align-items-center">
    
    <div>
        <h4 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.5px;">@yield('title', 'Dashboard')</h4>
    </div>
    
    <div class="d-flex align-items-center gap-4">
        
        <div>
            @yield('header_action')
        </div>

        <div class="vr d-none d-md-flex" style="opacity: 0.1; height: 30px;"></div>

        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none text-dark dropdown-toggle" id="dropdownAdmin" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width: 38px; height: 38px; border: 1px solid #e9ecef;">
                    <i class="bi bi-person-fill fs-5" style="color: var(--accent-color);"></i>
                </div>
                <div class="d-none d-md-block text-start">
                    <span class="fw-semibold d-block" style="font-size: 0.9rem; line-height: 1;">{{ Auth::user()->nama }}</span>
                    <small class="text-muted" style="font-size: 0.75rem;">{{ ucfirst(Auth::user()->role) }}</small>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="dropdownAdmin">
                <li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    
                    <a class="dropdown-item py-2 text-danger fw-medium" href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </a>
                </li>
            </ul>
        </div>

    </div>
</div>