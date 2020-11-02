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
          <li class="nav-item">
            <a href="#" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
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

          <li class="menu-header">Pesanan</li>
          <li><a class="nav-link" href="blank.html"><i class="far fa-square"></i> <span>Kelola Pesanan</span></a></li>
          <li><a class="nav-link" href="blank.html"><i class="far fa-square"></i> <span>Pesanan Dalam Proses</span></a></li>
          <li><a class="nav-link" href="blank.html"><i class="far fa-square"></i> <span>Pesanan Aktif</span></a></li>
          <li><a class="nav-link" href="blank.html"><i class="far fa-square"></i> <span>Pembayaran</span></a></li>

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

          @endrole
          @role('kitchen')

          @endrole
          
        </ul>

        <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
          <a href="https://getstisla.com/docs" class="btn btn-primary btn-lg btn-block btn-icon-split">
            <i class="fas fa-rocket"></i> Documentation
          </a>
        </div>
    </aside>
  </div>