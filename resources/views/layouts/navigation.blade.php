<!-- Sidebar -->
<div class="sidebar">
  <!-- Sidebar user panel (optional) -->
  <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
          <img src="{{ asset('images/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
          <a href="{{ route('profile.show') }}" class="d-block">{{ Auth::user()->name }}</a>
      </div>
  </div>

  <!-- Sidebar Menu -->
  <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu"
          data-accordion="false">
          
          
          <li class="nav-item">
              <a href="{{ route('dashboard') }}" class="nav-link {{activeSegment('dashboard')}}" >
                  <i class="nav-icon fas fa-th"></i>
                  <p>
                      {{ __('Dashboard') }}
                  </p>
              </a>
          </li>

          <li class="nav-item">
              <a href="{{ route('customers.index') }}" class="nav-link {{activeSegment('customers', 1, 'active' )}}" >
                  <i class="nav-icon fas fa-users"></i>
                  <p>
                      {{ __('Customers') }}
                  </p>
              </a>
          </li>

            <li class="nav-item {{activeSegment('invoices', 1, 'menu-open menu-is-opening')}}">
              <a href="#" class="nav-link {{activeSegment('invoices', 1, 'active')}}">
                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                <p>
                  Invoices
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('invoices.create') }}" class="nav-link {{activeSegment('create', 2, 'active')}}">
                    <i class="fa fa-cart-plus nav-icon"></i>
                    <p>New Invoice</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('invoices.index') }}" class="nav-link {{activeSegment('all', 2, 'active')}}">
                    <i class="far fa-file-alt nav-icon"></i>
                    <p>All Invoices</p>
                  </a>
                </li>
                
              </ul>
            </li>


            <li class="nav-item {{activeSegment('expenses', 1, 'menu-open menu-is-opening')}}">
              <a href="#" class="nav-link {{activeSegment('expenses', 1, 'active')}}">
                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                <p>
                  Expenses
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('expenses.create') }}" class="nav-link {{activeSegment('create', 2, 'active')}}">
                    <i class="fa fa-cart-plus nav-icon"></i>
                    <p>New Expense</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('expenses.index') }}" class="nav-link {{activeSegment('all', 2, 'active')}}">
                    <i class="far fa-file-alt nav-icon"></i>
                    <p>Expenses List</p>
                  </a>
                </li>

                <li class="nav-item">
                  <a href="{{ route('expenses.category') }}" class="nav-link {{activeSegment('category', 2, 'active')}}">
                    <i class="far fa-file-alt nav-icon"></i>
                    <p>Expenses Categories</p>
                  </a>
                </li>
                
              </ul>
            </li>
          
        


      </ul>
  </nav>
  <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->