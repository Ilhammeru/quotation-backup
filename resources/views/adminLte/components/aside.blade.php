@php
    $materials = \App\Models\Material::all();
    $materialRoute = collect($materials)->pluck('name')->all();
    $materialRoute = collect($materialRoute)->map(function($item) {return 'material.' . $item;})->all();
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
                {{-- <li class="nav-item">
                    <a href=""
                        class="nav-link {{ areActiveRoutes([]) }}">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li> --}}

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
                </li>

                <!--end::Menu-->
            </ul>
            <!--end::Aside Menu-->
        </nav>
        <!--end::Aside menu-->
    </div>
</aside>
<!--end::Aside-->
