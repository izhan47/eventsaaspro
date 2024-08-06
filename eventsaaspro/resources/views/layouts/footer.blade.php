<div class="footer pt-10">
    <div class="container">
        <div class="row">
            <div class="col-lg-2 col-12">
                <img src="{{ config('filesystems.disks.s3.url').'logos/Logo.png' }}" alt="logo">
            </div>
            @php $footerMenuItems = footerMenu() @endphp
            <div class="col-lg-6 col-12">
                @if (!empty($footerMenuItems))
                    @php $key = 1; @endphp
                    <ul class="footer-menu">
                    @foreach ($footerMenuItems as $parentItem)
                        <li>
                            <a href="{{$parentItem->url}}">{{ $parentItem->title }}</a>
                        </li>
                        @php $key++; @endphp
                    @endforeach
                </ul>
                @endif
            </div>
            <div class="col-lg-4 col-md-7 col-12">
                <form class="subscribtion-form" style="">
                    <input type="email" placeholder="Enter your Email">
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>

        <!-- Divider -->
        <hr style="margin: 32px 0; border-color: #9CA3AF;">
        <!-- Divider -->
        
        <div class="row">
            <div class="col-md-6 col-12">
                <p class="cb_copyright">
                    <span>©</span> {{ date('Y') }} <span>Comicbook</span>, INC. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 col-12">
                <ul class="social-links">

                    <li>
                        <a href="{{ 'https://github.com/' }}" target="_blank">
                            <img src="{{ config('filesystems.disks.s3.url').'icons/github.png' }}" alt="icon">
                        </a>
                    </li>

                    @if (setting('social.twitter'))
                    <li>
                    <!-- href="{{ 'https://twitter.com/' . setting('social.twitter') }}" -->
                        <a href="{{ 'https://twitter.com/' }}" target="_blank" class="text-white lh-lg">
                            <img src="{{ config('filesystems.disks.s3.url').'icons/twitter.png' }}" alt="icon">
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ 'https://dribble.com/' }}" target="_blank">
                            <img src="{{ config('filesystems.disks.s3.url').'icons/dribbble.png' }}" alt="icon">
                        </a>
                    </li>
                    @if (setting('social.facebook'))
                    <li>
                    <!-- href="{{ 'https://www.facebook.com/' . setting('social.facebook') }}" -->
                        <a href="{{ 'https://www.facebook.com/' }}" target="_blank" class="text-white lh-lg">
                            <img src="{{ config('filesystems.disks.s3.url').'icons/facebook-f.png' }}" alt="icon">
                        </a>
                    </li>
                    @endif

                </ul>
            </div>
        </div>
    </div>
</div>