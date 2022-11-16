@php
    $materials = \App\Models\Material::all();
    $materialRoute = collect($materials)->pluck('name')->all();
    $materialRoute = collect($materialRoute)->map(function($item) {return 'material.' . $item;})->all();
    $currency_type = [
        [
            'id' => \App\Models\CurrencyValue::SLIDE_TYPE,
            'name' => 'Slide',
            'slug' => 'slide'
        ],
        [
            'id' => \App\Models\CurrencyValue::NON_SLIDE_TYPE,
            'name' => 'Non Slide',
            'slug' => 'non-slide'
        ]
    ];

    $currency_group = [
        ['name' => 'IDR'],
        ['name' => 'USD'],
        ['name' => 'JPY'],
        ['name' => 'THB'],
    ];

    $currency_route = [];
    foreach ($currency_type as $type) {
        foreach ($currency_group as $group) {
            $currency_route[] = 'currency.' . $type['slug'] . '.' . $group['name'];
        }
    }
@endphp

<!--begin::Aside-->
<aside class="main-sidebar elevation-4" style="margin-bottom: 30px;">
    <!--begin::Brand-->
    <div class="aside-logo flex-column-auto brand-link m-0 p-0" style="background-color: #ddd;" id="kt_aside_logo">
        <!--begin::Logo-->
        <a href="#" style="display: flex;justify-content: center; align-items:center; gap: 20px; text-decoration: none; color: #000;">
            <img src="{{ asset('assets/images/logotbina.png') }}" class="logo" alt="">
            TBINA MKT
        </a>
        <!--end::Logo-->
    </div>
    <!--end::Brand-->

    <div class="sidebar" >
        <!--begin::Aside menu-->
        <nav class="mt-2" style="padding-bottom: 30px !important;">
            <!--begin::Aside Menu-->
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!--begin::Menu-->
                {{-- begin::logout --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ areActiveRoutes([]) }}">
                        {{-- <i class="far fa-circle nav-icon"></i> --}}
                        <p>{{ __('view.dashboard') }}</p>
                    </a>
                </li>
                {{-- end::logout --}}

                <li class="nav-header">{{-- __('view.pages') --}} Master TMMIN</li>

                <li
                    class="nav-item {{ areActiveRoutes($materialRoute, 'menu-is-opening menu-open active') }}">
                    <a href="#"
                        class="nav-link {{ areActiveRoutes([], 'menu-is-opening menu-open active') }}">
                        <i class="fas fa-box-open"></i>
                        <p>
                            {{ __('view.material') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    @if (auth()->user()->can('manage-material'))
                        <ul class="nav nav-treeview">
                            <!-- all type -->
                            @foreach ($materials as $material)
                                <li class="nav-item">
                                    <a href="{{ route('material.' . $material->name, $material->name) }}"
                                        class="nav-link {{ areActiveRoutes(['material.' . $material->name]) }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ ucfirst($material->name) }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>

                {{-- begin::process --}}
                <li class="nav-item">
                    <a href="{{ route('process.index') }}"
                        class="nav-link {{ areActiveRoutes([]) }}">
                        <i class="fas fa-box-open"></i>
                        <p>{{ __('view.process') }}</p>
                    </a>
                </li>
                {{-- end::process --}}

                {{-- begin::currency --}}
                <li
                    class="nav-item {{ areActiveRoutes($currency_route, 'menu-is-opening menu-open active') }}">
                    <a href="#"
                        class="nav-link {{ areActiveRoutes([], 'menu-is-opening menu-open active') }}">
                        <i class="bi bi-cash"></i>
                        <p>
                            {{ __('view.currency') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <!-- all type -->
                        @foreach ($currency_type as $type)
                            <li
                                class="nav-item {{ request()->segment(4) == $type['slug'] ? 'menu-is-opening menu-open active' : '' }} {{ areActiveRoutes([], 'menu-is-opening menu-open active') }}">
                                <a href="#"
                                    class="nav-link {{ areActiveRoutes([], 'menu-is-opening menu-open active') }}">
                                    <i class="bi bi-currency-dollar"></i>
                                    <p>
                                        {{ $type['name'] }}
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
            
                                <ul class="nav nav-treeview">
                                    <!-- all type -->
                                    @foreach ($currency_group as $group)
                                        <li class="nav-item">
                                            <a href="{{ route('currency.' . $type['slug'] . '.' . $group['name']) }}"
                                                class="nav-link {{ request()->segment(5) == $group['name'] && request()->segment(4) == $type['slug'] ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>{{ ucfirst($group['name']) }}</p>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </li>
                {{-- end::currency --}}

                {{-- begin:;setting --}}
                @if (auth()->user()->can('manage-setting'))
                    <li
                        class="nav-item {{ areActiveRoutes(['setting', 'setting.index', 'setting.permissions', 'setting.roles', 'setting.roles.show', 'users.index'], 'menu-is-opening menu-open active') }}">
                        <a href="#"
                            class="nav-link {{ areActiveRoutes([], 'menu-is-opening menu-open active') }}">
                            <i class="bi bi-gear"></i>
                            <p>
                                {{ __('view.setting') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            {{-- User --}}
                            <li class="nav-item">
                                <a href="{{ route('users.index') }}"
                                    class="nav-link {{ areActiveRoutes(['users.index']) }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('view.user') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('setting.permissions') }}"
                                    class="nav-link {{ areActiveRoutes(['setting.permissions']) }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('view.permissions') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('setting.roles') }}"
                                    class="nav-link {{ areActiveRoutes(['setting.roles', 'setting.roles.show']) }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('view.roles') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                {{-- end::setting --}}

                {{-- begin::logout --}}
                <li class="nav-item">
                    <a href="{{ route('logout') }}"
                        class="nav-link {{ areActiveRoutes([]) }}">
                        {{-- <i class="far fa-circle nav-icon"></i> --}}
                        <p>{{ __('view.logout') }}</p>
                    </a>
                </li>
                {{-- end::logout --}}

                <!--end::Menu-->
            </ul>
            <!--end::Aside Menu-->
        </nav>
        <!--end::Aside menu-->
    </div>
</aside>
<!--end::Aside-->
