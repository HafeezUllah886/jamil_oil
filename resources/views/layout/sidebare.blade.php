 <nav class="app-navbar">
     <div class="semi-side-nav">
         <div class="py-4">
             <span class="bg-white h-40 w-40 d-flex-center b-r-12 mx-auto">
                 <span class="f-w-600">{{ projectNameShort() }}</span>
             </span>
         </div>
         @php
             $routeName = request()->route() ? request()->route()->getName() : '';
             $activeMenu = 'homePage';

             if (
                 \Illuminate\Support\Str::startsWith($routeName, [
                     'account.',
                     'deposit_withdraw.',
                     'transfers.',
                     'expensesCategories.',
                     'expenses.',
                     'receivings.',
                     'expense.',
                     'receiving.',
                 ])
             ) {
                 $activeMenu = 'financePage';
             } elseif (
                 \Illuminate\Support\Str::startsWith($routeName, ['products.', 'product_stock.', 'stockDetails'])
             ) {
                 $activeMenu = 'productPage';
             } elseif (\Illuminate\Support\Str::startsWith($routeName, ['sale', 'shift'])) {
                 $activeMenu = 'salePage';
             } elseif (\Illuminate\Support\Str::startsWith($routeName, ['purchase'])) {
                 $activeMenu = 'purchasePage';
             } elseif (\Illuminate\Support\Str::startsWith($routeName, ['reports', 'profit', 'daily'])) {
                 $activeMenu = 'reportsPage';
             } elseif (\Illuminate\Support\Str::startsWith($routeName, ['settings', 'profile', 'users'])) {
                 $activeMenu = 'settingsPage';
             }
         @endphp
         <ul class="navbar-menu-list" role="tablist">
             <li class="nav-item" title="Home">
                 <a href="#" class="nav-link {{ $activeMenu == 'homePage' ? 'active' : '' }}"
                     data-target="homePage">
                     <i class="ti ti-smart-home"></i>
                 </a>
             </li>
             <li class="nav-item" title="Sale">
                 <a href="#" class="nav-link {{ $activeMenu == 'salePage' ? 'active' : '' }}"
                     data-target="salePage">
                     <i class="ti ti-shopping-cart"></i>
                 </a>
             </li>
             <li class="nav-item" title="Purchase">
                 <a href="#" class="nav-link {{ $activeMenu == 'purchasePage' ? 'active' : '' }}"
                     data-target="purchasePage">
                     <i class="ti ti-truck"></i>
                 </a>
             </li>
             <li class="nav-item" title="Finance Management">
                 <a href="#" class="nav-link {{ $activeMenu == 'financePage' ? 'active' : '' }}"
                     data-target="financePage">
                     <i class="ti ti-cash"></i>
                 </a>
             </li>
             <li class="nav-item" title="Product Management">
                 <a href="#" class="nav-link {{ $activeMenu == 'productPage' ? 'active' : '' }}"
                     data-target="productPage">
                     <i class="ti ti-box"></i>
                 </a>
             </li>
             <li class="nav-item" title="Reports">
                 <a href="#" class="nav-link {{ $activeMenu == 'reportsPage' ? 'active' : '' }}"
                     data-target="reportsPage">
                     <i class="ti ti-report"></i>
                 </a>
             </li>
             <li class="nav-item" title="Settings">
                 <a href="#" class="nav-link {{ $activeMenu == 'settingsPage' ? 'active' : '' }}"
                     data-target="settingsPage">
                     <i class="ti ti-settings"></i>
                 </a>
             </li>
         </ul>

         <span class="bg-primary-800 h-45 w-45 d-flex-center b-r-30 position-relative mx-auto ">
             <img alt="avatar" class="img-fluid b-r-30" src="{{ asset('assets/images/avatar/01.png') }}">
             <span
                 class="position-absolute top-0 end-0 p-1 bg-gradient-success border border-light rounded-circle"></span>
         </span>
     </div>
     <div class="main-side-nav">
         <div>

             <span class="w-30 h-30 d-none bg-gradient-danger b-r-8 cursor-pointer side-toggle">
                 <i class="ti ti-x f-s-18"></i>
             </span>
             <div class="side-search p-3">
                 <div class="position-relative">
                     <input aria-label="Search" class="form-control py-2 b-r-18" placeholder="Search..." type="search"
                         id="sidebar-menu-search">
                     <i class="ti ti-command f-s-20 text-secondary"></i>
                 </div>
             </div>
         </div>

         <div class="nav-wrapper app-scroll app-simple-bar">

             <div class="main-side-menu">
                 <!-- Home -->
                 <ul class="main-menu {{ $activeMenu == 'homePage' ? 'active' : '' }}" id="homePage"
                     style="display: {{ $activeMenu == 'homePage' ? 'block' : 'none' }};">
                     <li class="no-sub {{ $routeName == 'dashboard' ? 'active' : '' }}">
                         <a href="{{ route('dashboard') }}" class="{{ $routeName == 'dashboard' ? 'active' : '' }}">
                             Dashboard
                         </a>
                     </li>
                 </ul>

                 <!-- Sale -->
                 <ul class="main-menu {{ $activeMenu == 'salePage' ? 'active' : '' }}" id="salePage"
                     style="display: {{ $activeMenu == 'salePage' ? 'block' : 'none' }};">
                     <li class="no-sub {{ $routeName == 'sale.create' ? 'active' : '' }}"><a
                             href="{{ route('sale.create') }}"
                             class="{{ $routeName == 'sale.create' ? 'active' : '' }}">Create Sales</a></li>
                     <li class="no-sub {{ $routeName == 'sale.index' ? 'active' : '' }}"><a
                             href="{{ route('sale.index') }}"
                             class="{{ $routeName == 'sale.index' ? 'active' : '' }}">Sales History</a></li>
                 </ul>

                 <!-- Purchase -->
                 <ul class="main-menu {{ $activeMenu == 'purchasePage' ? 'active' : '' }}" id="purchasePage"
                     style="display: {{ $activeMenu == 'purchasePage' ? 'block' : 'none' }};">
                     <li class="no-sub {{ $routeName == 'purchase.create' ? 'active' : '' }}"><a
                             href="{{ route('purchase.create') }}"
                             class="{{ $routeName == 'purchase.create' ? 'active' : '' }}">Create Purchase</a></li>
                     <li class="no-sub {{ $routeName == 'purchase.index' ? 'active' : '' }}"><a
                             href="{{ route('purchase.index') }}"
                             class="{{ $routeName == 'purchase.index' ? 'active' : '' }}">Purchase History</a></li>
                 </ul>

                 <!-- Finance -->
                 <ul class="main-menu {{ $activeMenu == 'financePage' ? 'active' : '' }}" id="financePage"
                     style="display: {{ $activeMenu == 'financePage' ? 'block' : 'none' }};">
                     <li class="no-sub {{ $routeName == 'account.create' ? 'active' : '' }}"><a
                             href="{{ route('account.create') }}"
                             class="{{ $routeName == 'account.create' ? 'active' : '' }}">Create Account</a></li>
                     <li class="no-sub {{ request('filter') == 'Business' ? 'active' : '' }}"><a
                             href="{{ route('account.index', ['filter' => 'Business']) }}"
                             class="{{ request('filter') == 'Business' ? 'active' : '' }}">Business
                             Accounts</a></li>
                     <li class="no-sub {{ request('filter') == 'Customer' ? 'active' : '' }}"><a
                             href="{{ route('account.index', ['filter' => 'Customer']) }}"
                             class="{{ request('filter') == 'Customer' ? 'active' : '' }}">Customer
                             Accounts</a></li>
                     <li class="no-sub {{ request('filter') == 'Supplier' ? 'active' : '' }}"><a
                             href="{{ route('account.index', ['filter' => 'Supplier']) }}"
                             class="{{ request('filter') == 'Supplier' ? 'active' : '' }}">Supplier
                             Accounts</a></li>
                     <li class="no-sub {{ $routeName == 'receivings.index' ? 'active' : '' }}"><a
                             href="{{ route('receivings.index') }}"
                             class="{{ $routeName == 'receivings.index' ? 'active' : '' }}">Receive
                             Payments</a></li>
                     <li class="no-sub {{ $routeName == 'issue.index' ? 'active' : '' }}"><a
                             href="{{ route('issue.index') }}"
                             class="{{ $routeName == 'issue.index' ? 'active' : '' }}">Issue Payments</a></li>
                     <li class="no-sub {{ $routeName == 'transfers.index' ? 'active' : '' }}"><a
                             href="{{ route('transfers.index') }}"
                             class="{{ $routeName == 'transfers.index' ? 'active' : '' }}">Transfers</a></li>
                     <li class="no-sub {{ $routeName == 'adjustments.index' ? 'active' : '' }}"><a
                             href="{{ route('adjustments.index') }}"
                             class="{{ $routeName == 'adjustments.index' ? 'active' : '' }}">Accounts
                             Adjustment</a></li>
                     <li
                         class="no-sub {{ $routeName == 'expenses.index' || $routeName == 'expensesCategories.index' ? 'active' : '' }}">
                         <a href="{{ route('expenses.index') }}"
                             class="{{ $routeName == 'expenses.index' || $routeName == 'expensesCategories.index' ? 'active' : '' }}">Expenses</a>
                     </li>
                 </ul>

                 <!-- Product -->
                 <ul class="main-menu {{ $activeMenu == 'productPage' ? 'active' : '' }}" id="productPage"
                     style="display: {{ $activeMenu == 'productPage' ? 'block' : 'none' }};">
                     <li class="no-sub {{ $routeName == 'products.index' ? 'active' : '' }}"><a
                             href="{{ route('products.index') }}"
                             class="{{ $routeName == 'products.index' ? 'active' : '' }}">Products List</a></li>
                     <li class="no-sub {{ $routeName == 'product_stock' ? 'active' : '' }}"><a
                             href="{{ route('product_stock') }}"
                             class="{{ $routeName == 'product_stock' ? 'active' : '' }}">Stocks</a></li>
                     <li class="no-sub {{ $routeName == 'stock-adjustments.index' ? 'active' : '' }}"><a
                             href="{{ route('stock-adjustments.index') }}"
                             class="{{ $routeName == 'stock-adjustments.index' ? 'active' : '' }}">Stock
                             Adjustment</a></li>
                 </ul>

                 <!-- Reports -->
                 <ul class="main-menu {{ $activeMenu == 'reportsPage' ? 'active' : '' }}" id="reportsPage"
                     style="display: {{ $activeMenu == 'reportsPage' ? 'block' : 'none' }};">
                     <li
                         class="no-sub {{ $routeName == 'reportProfitDetails' || $routeName == 'reports.profit_loss' ? 'active' : '' }}">
                         <a href="{{ route('reports.profit_loss') }}"
                             class="{{ $routeName == 'reportProfitDetails' || $routeName == 'reports.profit_loss' ? 'active' : '' }}">Profit
                             / Loss
                             Report</a>
                     </li>
                     <li
                         class="no-sub {{ $routeName == 'reportCashbook' || $routeName == 'reportCashbookData' ? 'active' : '' }}">
                         <a href="{{ route('reportCashbook') }}"
                             class="{{ $routeName == 'reportCashbook' || $routeName == 'reportCashbookData' ? 'active' : '' }}">Daily
                             Cashbook</a>
                     </li>
                     <li
                         class="no-sub {{ $routeName == 'reportExpense' || $routeName == 'reportExpenseData' ? 'active' : '' }}">
                         <a href="{{ route('reportExpense') }}"
                             class="{{ $routeName == 'reportExpense' || $routeName == 'reportExpenseData' ? 'active' : '' }}">Expenses
                             Report</a>
                     </li>
                     <li
                         class="no-sub {{ $routeName == 'reportDailySheet' || $routeName == 'reportDailySheetData' ? 'active' : '' }}">
                         <a href="{{ route('reportDailySheet') }}"
                             class="{{ $routeName == 'reportDailySheet' || $routeName == 'reportDailySheetData' ? 'active' : '' }}">Daily
                             Sheet</a>
                     </li>
                 </ul>

                 <!-- Settings -->
                 <ul class="main-menu {{ $activeMenu == 'settingsPage' ? 'active' : '' }}" id="settingsPage"
                     style="display: {{ $activeMenu == 'settingsPage' ? 'block' : 'none' }};">
                     <li class="no-sub {{ $routeName == 'profile' ? 'active' : '' }}"><a
                             href="{{ route('profile') }}"
                             class="{{ $routeName == 'profile' ? 'active' : '' }}">Profile</a></li>
                     <li class="no-sub {{ $routeName == 'attendants.index' ? 'active' : '' }}"><a
                             href="{{ route('attendants.index') }}"
                             class="{{ $routeName == 'attendants.index' ? 'active' : '' }}">Pump Attendants</a></li>
                     {{--   <li class="no-sub"><a href="#">Users</a></li> --}}
                     <li class="no-sub"><a href="{{ route('logout') }}">Logout</a></li>
                 </ul>
             </div>
         </div>

         <div class="horizontal-action d-none">
             <span class="h-40 w-40 d-flex-center b-r-50 horizontal-left">
                 <i class="ti ti-chevron-left"></i>
             </span>
             <span class="h-40 w-40 d-flex-center b-r-50 horizontal-right">
                 <i class="ti ti-chevron-right"></i>
             </span>
         </div>


     </div>
 </nav>

 <script>
     document.addEventListener('DOMContentLoaded', function() {
         const searchInput = document.getElementById('sidebar-menu-search');
         const menuContainers = document.querySelectorAll('.main-menu');
         if (!searchInput || menuContainers.length === 0) return;

         const allLis = document.querySelectorAll('.main-menu li');
         const allLinks = document.querySelectorAll('.main-menu a');

         searchInput.addEventListener('input', function(e) {
             const term = e.target.value.toLowerCase().trim();

             if (term === '') {
                 allLis.forEach(li => li.style.display = '');
                 const activeNav = document.querySelector('.semi-side-nav .nav-link.active');
                 const targetId = activeNav ? activeNav.getAttribute('data-target') : 'homePage';
                 menuContainers.forEach(container => {
                     container.style.display = (container.id === targetId) ? 'block' : 'none';
                 });
                 return;
             }

             // Show all menus during search
             menuContainers.forEach(container => {
                 container.style.display = 'block';
             });

             // Hide all LIs initially
             allLis.forEach(li => li.style.display = 'none');

             // Check each link
             allLinks.forEach(link => {
                 const text = link.textContent.toLowerCase();
                 if (text.includes(term)) {
                     // Reveal this LI and its ancestors
                     let currentLi = link.closest('li');
                     while (currentLi && currentLi.closest('.main-menu')) {
                         currentLi.style.display = '';

                         // Expand parent ul.collapse if needed
                         let parentUl = currentLi.closest('ul.collapse');
                         if (parentUl) {
                             parentUl.classList.add('show');
                             let toggleLink = parentUl.previousElementSibling;
                             if (toggleLink && toggleLink.hasAttribute('data-bs-toggle')) {
                                 toggleLink.setAttribute('aria-expanded', 'true');
                             }
                         }
                         currentLi = currentLi.parentElement.closest('li');
                     }
                 }
             });
         });
     });
 </script>
