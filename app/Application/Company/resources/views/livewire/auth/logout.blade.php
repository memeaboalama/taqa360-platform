<li><a class="dropdown-item d-flex" wire:click.prevent="logout" href=""><i
            class="{{ LaravelLocalization::getCurrentLocale() == 'ar' ? 'ri-logout-box-r-line' : 'ri-logout-box-line' }} fs-18 me-2 op-7"></i>{{ __('company::home.Log out') }}</a></li>
