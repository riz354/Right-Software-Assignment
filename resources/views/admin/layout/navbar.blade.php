  <!--begin::Header-->
  <nav class="app-header navbar navbar-expand bg-body">
      <!--begin::Container-->
      <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
              <li class="nav-item">
                  <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                      <i class="bi bi-list"></i>
                  </a>
              </li>
          </ul>
          <!--end::Start Navbar Links-->
          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">

              <!--begin::Fullscreen Toggle-->
              <li class="nav-item">
                  <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                      <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                      <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                  </a>
              </li>
              <!--end::Fullscreen Toggle-->
              <!--begin::User Menu Dropdown-->
              <li class="nav-item dropdown user-menu">
                  <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                      <img src="
            {{ asset('assets/assets/img/emptyUser.jpg') }}"
                          class="user-image rounded-circle shadow" alt="User Image" />
                      <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                      <!--begin::User Image-->
                      <li class="user-header text-bg-primary">
                          <img src="
              {{ asset('assets/assets/img/emptyUser.jpg') }}"
                              class="rounded-circle shadow" alt="User Image" />
                          <p>
                              {{ Auth::user()->name }}
                          </p>
                      </li>
                      <!--end::User Image-->
                      <!--begin::Menu Footer-->
                      <li class="user-footer">
                          {{-- <a href="#" class="btn btn-default btn-flat">Profile</a> --}}
                          <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-default btn-flat float-end">Log out</button>
                        </form>
                        
                      </li>
                      <!--end::Menu Footer-->
                  </ul>
              </li>
              <!--end::User Menu Dropdown-->

              <li class="nav-item">
                <a class="nav-link" href="{{ url('/') }}" >
                    <i class="nav-icon bi bi-house"></i>
                </a>
            </li>
          </ul>
          <!--end::End Navbar Links-->
      </div>
      <!--end::Container-->
  </nav>
  <!--end::Header-->
