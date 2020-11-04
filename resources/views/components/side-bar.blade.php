<div class="main-sidebar">
    <aside id="sidebar-wrapper">
      <div class="sidebar-brand">
        <a href="{{ route('home') }}">{{ getSiteName() }}</a>
      </div>
      <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ route('home') }}">{{ createAcronym(getSiteName()) }}</a>
      </div>
      <ul class="sidebar-menu">
          <li class="menu-header">SI Restoran</li>
          <li class="nav-item {{ __active('HomeController', 'index') }}">
            <a href="{{ route('home') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
          </li>

          @role('admin')
          <li class="menu-header">Makanan</li>
          <li class="nav-item dropdown {{ __active('FoodController', ['index', 'edit', 'create', 'show', 'stock']) }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-hamburger"></i><span>Kelola Makanan</span></a>
            <ul class="dropdown-menu">
              <li class=" {{ __active('FoodController', ['index', 'edit', 'show']) }}"><a class="nav-link" href="{{ route('admin.foods.index') }}">Kelola Makanan</a></li>
              <li class=" {{ __active('FoodController', 'create') }}"><a class="nav-link" href="{{ route('admin.foods.create') }}">Tambah Makanan</a></li>
              <li class=" {{ __active('FoodController', 'stock') }}"><a class="nav-link" href="{{ route('admin.foods.stock') }}">Kelola Stok</a></li>
            </ul>
          </li>
          <li class=" {{ __active('CategoryController', 'index') }}"><a class="nav-link" href="{{ route('admin.categories') }}"><i class="fas fa-list"></i> <span>Kelola Kategori</span></a></li>

          <li class="menu-header">Order</li>
          <li class="{{ __active('OrderController', ['index', 'show']) }}"><a class="nav-link" href="{{ route('orders.index') }}"><i class="fas fa-table"></i> <span>Kelola Order</span></a></li>
          <li class="{{ __active('OrderController', 'queue') }}"><a class="nav-link" href="{{ route('orders.queue') }}"><i class="fas fa-tasks"></i> <span>Dalam Antrian</span></a></li>
          <li class="{{ __active('OrderController', 'ready') }}"><a class="nav-link" href="{{ route('orders.ready') }}"><i class="fas fa-chair"></i> <span>Order Siap</span></a></li>
          <li class="{{ __active('OrderController', 'finish') }}"><a class="nav-link" href="{{ route('orders.finish') }}"><i class="fas fa-check"></i> <span>Order Selesai</span></a></li>

          <li class="menu-header">Pengaturan</li>
          <li class="nav-item dropdown {{ __active('TableController', ['index', 'create', 'show']) }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-chair"></i><span>Meja</span></a>
            <ul class="dropdown-menu">
              <li class=" {{ __active('TableController', ['index', 'show']) }}"><a class="nav-link" href="{{ route('admin.tables.index') }}">Kelola Meja</a></li>
              <li class=" {{ __active('TableController', 'create') }}"><a class="nav-link" href="{{ route('admin.tables.create') }}">Tambah Meja</a></li>
            </ul>
          </li>
          <li class="{{ __active('UserController', 'index') }}"><a class="nav-link" href="{{ route('admin.users') }}"><i class="fas fa-users"></i> <span>Pengaturan User</span></a></li>
          <li class="{{ __active('SettingController', 'index') }}"><a class="nav-link" href="{{ route('admin.settings') }}"><i class="fas fa-cog"></i> <span>Pengaturan Situs</span></a></li>
          @endrole
          @role('cashier')

          @endrole
          @role('waiter')
          <li class="menu-header">Order</li>
          <li class="{{ __active('OrderController', ['index', 'show']) }}"><a class="nav-link" href="{{ route('orders.index') }}"><i class="fas fa-table"></i> <span>Kelola Order</span></a></li>
          <li class="{{ __active('OrderController', 'queue') }}"><a class="nav-link" href="{{ route('orders.queue') }}"><i class="fas fa-tasks"></i> <span>Dalam Antrian</span></a></li>
          <li class="{{ __active('OrderController', 'ready') }}"><a class="nav-link" href="{{ route('orders.ready') }}"><i class="fas fa-chair"></i> <span>Order Siap</span></a></li>
          <li class="{{ __active('OrderController', 'finish') }}"><a class="nav-link" href="{{ route('orders.finish') }}"><i class="fas fa-check"></i> <span>Order Selesai</span></a></li>
          @endrole
          @role('kitchen')
          <li class="menu-header">Order</li>
          <li class="{{ __active('OrderController', ['index', 'show']) }}"><a class="nav-link" href="{{ route('orders.index') }}"><i class="fas fa-table"></i> <span>Kelola Order</span></a></li>
          <li class="{{ __active('OrderController', 'queue') }}"><a class="nav-link" href="{{ route('orders.queue') }}"><i class="fas fa-tasks"></i> <span>Dalam Antrian</span></a></li>
          <li class="{{ __active('OrderController', 'ready') }}"><a class="nav-link" href="{{ route('orders.ready') }}"><i class="fas fa-chair"></i> <span>Order Siap</span></a></li>
          <li class="{{ __active('OrderController', 'finish') }}"><a class="nav-link" href="{{ route('orders.finish') }}"><i class="fas fa-check"></i> <span>Order Selesai</span></a></li>

          <li class="menu-header">Makanan</li>
          <li class=" {{ __active('CategoryController', 'index') }}"><a class="nav-link" href="{{ route('admin.foods.stock') }}"><i class="fas fa-clipboard-check"></i> <span>Kelola Stok</span></a></li>
          @endrole
          
        </ul>
    </aside>
  </div>