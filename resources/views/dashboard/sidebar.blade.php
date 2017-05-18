<div class="side-menu sidebar-inverse">
    <nav class="navbar navbar-default" role="navigation">
        <div class="side-menu-container">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{ route('dola.dashboard') }}">
                    <div class="logo-icon-container">
                        <?php $admin_logo_img = Dola::setting('admin_icon_image', ''); ?>
                        @if($admin_logo_img == '')
                            <img src="{{ dola_asset('images/logo-icon-light.png') }}" alt="Logo Icon">
                        @else
                            <img src="{{ Dola::image($admin_logo_img) }}" alt="Logo Icon">
                        @endif
                    </div>
                    <div class="title">{{Dola::setting('admin_title', 'DOLA')}}</div>
                </a>
            </div><!-- .navbar-header -->

            <div class="panel widget center bgimage"
                 style="background-image:url({{ Dola::image( Dola::setting('admin_bg_image'), config('dola.assets_path') . '/images/bg.jpg' ) }});">
                <div class="dimmer"></div>
                <div class="panel-content">
                    <img src="{{ $user_avatar }}" class="avatar" alt="{{ Auth::user()->name }} avatar">
                    <h4>{{ ucwords(Auth::user()->name) }}</h4>
                    <p>{{ Auth::user()->email }}</p>

                    <a href="{{ route('dola.profile') }}" class="btn btn-primary">Profile</a>
                    <div style="clear:both"></div>
                </div>
            </div>

        </div>

        {!! menu('admin', 'admin_menu') !!}
    </nav>
</div>
