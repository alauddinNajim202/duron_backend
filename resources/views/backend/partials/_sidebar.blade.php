<!--APP-SIDEBAR-->
<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar" style="overflow: scroll">
        <div class="side-header">
            <a class="header-brand1" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset(settings()->logo ?? 'default/logo.png') }}" id="header-brand-logo" alt="logo"
                    width="{{ settings()->logo_width ?? 200 }}" height="{{ settings()->logo_height ?? 100 }}">
            </a>
        </div>
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg>
            </div>
            <ul class="side-menu mt-2">
                <li>
                    <h3>Menu</h3>
                </li>
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('dashboard') ? 'has-link active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <i class="fa-solid fa-gauge-high side-menu__icon"></i>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li>

                {{-- Manage Tags --}}
                @if(auth()->user()?->hasRole('admin') || auth()->user()?->can('manage tags') || auth()->user()?->can('view tags') || auth()->user()?->can('view categories') || auth()->user()?->can('view languages'))
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('admin.tags.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.languages.*') ? 'has-link active' : '' }}"
                        data-bs-toggle="slide" href="#">
                        <i class="side-menu__icon fa-solid fa-tag"></i>
                        <span class="side-menu__label">Manage Tags</span>
                        <i class="angle fa fa-angle-right"></i>
                    </a>
                    <ul class="slide-menu">
                        @if(auth()->user()?->hasRole('admin') || auth()->user()?->can('view tags') || auth()->user()?->can('manage tags'))
                        <li><a href="{{ route('admin.tags.index') }}"
                                class="slide-item {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}"> Gig Tags
                            </a></li>
                        @endif
                        @if(auth()->user()?->hasRole('admin') || auth()->user()?->can('view categories') || auth()->user()?->can('manage categories'))
                        <li><a href="{{ route('admin.categories.index') }}"
                                class="slide-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"> Gig
                                Categories </a></li>
                        @endif
                        @if(auth()->user()?->hasRole('admin') || auth()->user()?->can('view languages') || auth()->user()?->can('manage languages'))
                        <li><a href="{{ route('admin.languages.index') }}"
                                class="slide-item {{ request()->routeIs('admin.languages.*') ? 'active' : '' }}">
                                Supported Languages </a></li>
                        @endif
                    </ul>
                </li>
                @endif

                {{-- Manage Users --}}
                @if(auth()->user()?->hasRole('admin') || auth()->user()?->can('view users') || auth()->user()?->can('manage users'))
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('admin.experts.*') || request()->routeIs('admin.clients.*') ? 'has-link active' : '' }}"
                        data-bs-toggle="slide" href="#">
                        <i class="side-menu__icon fa-solid fa-users"></i>
                        <span class="side-menu__label">Manage Users</span>
                        <i class="angle fa fa-angle-right"></i>
                    </a>
                    <ul class="slide-menu">
                        <li><a href="{{ route('admin.experts.index') }}"
                                class="slide-item {{ request()->routeIs('admin.experts.*') ? 'active' : '' }}"> Experts
                            </a></li>
                        <li><a href="{{ route('admin.clients.index') }}"
                                class="slide-item {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}"> Clients
                            </a></li>
                    </ul>
                </li>
                @endif

                {{-- Access Control (Roles & Permissions) --}}
                @if(auth()->user()?->hasRole('admin') || auth()->user()?->can('view access control') || auth()->user()?->can('manage access control') || auth()->user()?->can('view roles') || auth()->user()?->can('view permissions') || auth()->user()?->can('view admin users'))
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*') || request()->routeIs('admin.users.*') ? 'has-link active' : '' }}"
                        data-bs-toggle="slide" href="#">
                        <i class="side-menu__icon fa-solid fa-user-shield"></i>
                        <span class="side-menu__label">Access Control</span>
                        <i class="angle fa fa-angle-right"></i>
                    </a>
                    <ul class="slide-menu">
                        @if(auth()->user()?->hasRole('admin') || auth()->user()?->can('view roles') || auth()->user()?->can('manage roles'))
                        <li><a href="{{ route('admin.roles.index') }}"
                                class="slide-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"> Roles </a>
                        </li>
                        @endif
                        @if(auth()->user()?->hasRole('admin') || auth()->user()?->can('view permissions') || auth()->user()?->can('manage permissions'))
                        <li><a href="{{ route('admin.permissions.index') }}"
                                class="slide-item {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}"> Permissions </a>
                        </li>
                        @endif
                        @if(auth()->user()?->hasRole('admin') || auth()->user()?->can('view admin users') || auth()->user()?->can('manage admin users'))
                        <li><a href="{{ route('admin.users.index') }}"
                                class="slide-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"> Admin Users </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                {{-- Manage Gigs --}}
                @if(auth()->user()?->hasRole('admin') || auth()->user()?->can('view gigs') || auth()->user()?->can('manage gigs'))
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('admin.gigs.*') ? 'has-link active' : '' }}"
                        data-bs-toggle="slide" href="#">
                        <i class="side-menu__icon fa-solid fa-gem"></i>
                        <span class="side-menu__label">Manage Gigs</span>
                        <i class="angle fa fa-angle-right"></i>
                    </a>
                    <ul class="slide-menu">
                        <li><a href="{{ route('admin.gigs.index') }}"
                                class="slide-item {{ request()->routeIs('admin.gigs.*') ? 'active' : '' }}"> Gigs </a>
                        </li>
                    </ul>
                </li>
                @endif

                {{-- Manage Order --}}
                @if(auth()->user()?->hasRole('admin') || auth()->user()?->can('view orders') || auth()->user()?->can('manage orders') || auth()->user()?->can('view qa requests') || auth()->user()?->can('view extension requests'))
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('admin.qa.*') || request()->routeIs('admin.orders.*') || request()->routeIs('admin.extension-requests.*') ? 'has-link active' : '' }}"
                        data-bs-toggle="slide" href="#">
                        <i class="side-menu__icon fa-brands fa-first-order"></i>
                        <span class="side-menu__label">Manage Order</span>
                        <i class="angle fa fa-angle-right"></i>
                    </a>
                    <ul class="slide-menu">
                        @if(auth()->user()?->hasRole('admin') || auth()->user()?->can('view qa requests') || auth()->user()?->can('view orders') || auth()->user()?->can('manage orders'))
                        <li><a href="{{ route('admin.qa.index') }}"
                                class="slide-item {{ request()->routeIs('admin.qa.index') ? 'active' : '' }}"> QA
                                Request
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()?->hasRole('admin') || auth()->user()?->can('view orders') || auth()->user()?->can('manage orders'))
                        <li><a href="{{ route('admin.orders.index') }}"
                                class="slide-item {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}"> All
                                Orders
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()?->hasRole('admin') || auth()->user()?->can('view extension requests') || auth()->user()?->can('view orders') || auth()->user()?->can('manage orders'))
                        <li><a href="{{ route('admin.extension-requests.index') }}"
                                class="slide-item {{ request()->routeIs('admin.extension-requests.index') ? 'active' : '' }}">
                                Extension Requests
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                {{-- Payment Controle --}}
                @if(auth()->user()?->hasRole('admin') || auth()->user()?->can('view payments') || auth()->user()?->can('manage payments'))
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('admin.payments.index') ? 'has-link active' : '' }}"
                        href="{{ route('admin.payments.index') }}">
                        <i class="side-menu__icon fa-solid fa-dollar-sign"></i>
                        <span class="side-menu__label">Payments</span>
                    </a>
                </li>
                @endif

                {{-- Contact Order --}}
                @if(auth()->user()?->hasRole('admin') || auth()->user()?->can('view supports') || auth()->user()?->can('manage supports'))
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('admin.contact.index') ? 'has-link active' : '' }}"
                        href="{{ route('admin.contact.index') }}">
                        <i class="side-menu__icon fa-solid fa-circle-question"></i>
                        <span class="side-menu__label">Support Corner</span>
                    </a>
                </li>
                @endif

                {{-- Terms & Privacy --}}
                @if(auth()->user()?->hasRole('admin') || auth()->user()?->can('view terms and privacy') || auth()->user()?->can('manage terms and privacy') || auth()->user()?->can('view cms') || auth()->user()?->can('manage cms'))
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('admin.cms.privecyandterms.*') ? 'has-link active' : '' }}"
                        data-bs-toggle="slide" href="#">
                        <i class="side-menu__icon fa fa-file-contract"></i>
                        <span class="side-menu__label">Terms & Privacy</span>
                        <i class="angle fa fa-angle-right"></i>
                    </a>
                    <ul class="slide-menu">
                        <li><a href="{{ route('admin.cms.privecyandterms.terms') }}"
                                class="slide-item {{ request()->routeIs('admin.cms.privecyandterms.terms') ? 'active' : '' }}">
                                Terms & Condition </a></li>
                        <li><a href="{{ route('admin.cms.privecyandterms.privacy') }}"
                                class="slide-item {{ request()->routeIs('admin.cms.privecyandterms.privacy') ? 'active' : '' }}">
                                Privacy Policy </a></li>
                    </ul>
                </li>
                @endif

                {{-- CMS --}}
                @if(auth()->user()?->hasRole('admin') || auth()->user()?->can('view cms') || auth()->user()?->can('manage cms'))
                <li>
                    <h3>CMS</h3>
                </li>

                {{-- Home Page --}}
                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('admin.cms.home*') ? 'has-link active' : '' }}"
                        data-bs-toggle="slide" href="#">
                        <i class="side-menu__icon fa fa-home"></i>
                        <span class="side-menu__label">Home Page</span>
                        <i class="angle fa fa-angle-right"></i>
                    </a>
                    <ul class="slide-menu">
                        <li><a href="{{ route('admin.cms.home.hero.section') }}"
                                class="slide-item {{ request()->routeIs('admin.cms.home.hero') ? 'active' : '' }}">
                                Hero Section </a></li>
                        <li><a href="{{ route('admin.cms.home.tags.section') }}"
                                class="slide-item {{ request()->routeIs('admin.cms.home.tags.section') ? 'active' : '' }}">
                                Tags Section </a></li>
                    </ul>
                </li>
                @endif

                {{-- Settings --}}
                @if(auth()->user()?->hasRole('admin') || auth()->user()?->can('view settings') || auth()->user()?->can('manage settings'))
                <li>
                    <h3>Settings</h3>
                </li>

                <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('admin.setting.*') ? 'has-link active' : '' }}"
                        data-bs-toggle="slide" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 512 512">
                            <path
                                d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z" />
                        </svg>
                        <span class="side-menu__label">Settings</span>
                        <i class="angle fa fa-angle-right"></i>
                    </a>
                    <ul class="slide-menu">
                        <li><a href="{{ route('admin.setting.general.index') }}"
                                class="slide-item {{ request()->routeIs('admin.setting.general*') ? 'active' : '' }}">
                                General Settings </a></li>
                        <li><a href="{{ route('admin.setting.logo.index') }}"
                                class="slide-item {{ request()->routeIs('admin.setting.logo*') ? 'active' : '' }}">
                                Logo Settings </a></li>
                        <li><a href="{{ route('admin.setting.profile.index') }}"
                                class="slide-item {{ request()->routeIs('admin.setting.profile*') ? 'active' : '' }}">
                                Profile Settings </a></li>
                        <li><a href="{{ route('admin.setting.mail.index') }}"
                                class="slide-item {{ request()->routeIs('admin.setting.mail*') ? 'active' : '' }}">
                                Mail Settings </a></li>
                        <li><a href="{{ route('admin.setting.stripe.index') }}"
                                class="slide-item {{ request()->routeIs('admin.setting.stripe*') ? 'active' : '' }}">
                                Stripe Settings </a></li>
                        <li><a href="{{ route('admin.social.links.index') }}"
                                class="slide-item {{ request()->routeIs('admin.social.links.index') ? 'active' : '' }}">
                                Social Links </a></li>
                    </ul>
                </li>
                @endif
            </ul>

            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg>
            </div>
        </div>
    </div>
</div>
<!--/APP-SIDEBAR-->

<style>
    /* ── Logo ── */
    .side-header {
        width: 100%;
        height: 75px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #header-brand-logo {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    /* ── Parent nav items: hover + active ── */
    .side-menu__item {
        transition: background-color 0.25s ease, color 0.25s ease, border-radius 0.25s ease;
        border-radius: 6px;
        margin: 1px 8px;
        display: flex;
        align-items: center;
        padding: 10px 15px;
        color: #333;
        font-weight: 500;
        text-decoration: none;
    }
</style>
