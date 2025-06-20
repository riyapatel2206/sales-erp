<nav class="navbar navbar-expand-lg navbar-light bg-primary border-bottom">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <h2>Sales ERP</h2>
        </a>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto ">
                 @if(auth()->user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                       href="{{ route('dashboard') }}">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('products.index') ? 'active' : '' }}" 
                       href="{{ route('products.index') }}">
                        Product List
                    </a>
                </li>
                 @endif
                @if(auth()->user()->isSalesperson() || auth()->user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('sales.list') ? 'active' : '' }}" 
                       href="{{ route('sales.list') }}">
                        Sales List
                    </a>
                </li>
                @endif
            </ul>

            <!-- User Dropdown -->
            <div class="navbar-nav">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" 
                       href="#" 
                       id="navbarDropdown" 
                       role="button" 
                       data-bs-toggle="dropdown" 
                       aria-expanded="false">
                        <span class="me-1">{{ Auth::user()->name }}</span>
                      
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <a class="dropdown-item" 
                                   href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); this.closest('form').submit();">
                                    Log Out
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</nav>