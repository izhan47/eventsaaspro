{{-- Load third party plugins in seperate file (node modules) --}}
<script type="text/javascript" src="{{ eventmie_asset('js/manifest.js') }}"></script>
<script src="{{ eventmie_asset('js/bootstrap.bundle.min.js') }}"></script>

{{-- localization --}}
<script type="text/javascript" src="{{ route('eventsaaspro.eventmie_lang') }}"></script>


{{-- VueJs Global Constants --}}
<script type="text/javascript">
    const my_lang = {!! json_encode(session('my_lang', \Config::get('app.locale'))) !!};
    const timezone_conversion = {!! json_encode(!empty(setting('regional.timezone_conversion')) ? 1 : 0) !!};
    const timezone_default = {!! json_encode(setting('regional.timezone_default')) !!};


    const date_format = {
        vue_date_format: '{!! format_js_date() !!}',
        vue_time_format: '{!! format_js_time() !!}'
    };
</script>



{{-- Javascript Global Listener --}}
<script type="text/javascript">
    /**
     * Header menu onscroll
     */
    var lastScrollTop = 0;

    if(document.getElementById('navbar_vue').classList.contains('nav-dashboard')) {
        oldSource = document.getElementById('brand-logo').src;
        console.log("BRAND: ", oldSource.replace("Logo.png", "Logo-black.png"));

        document.getElementById('brand-logo').src = oldSource.replace("Logo.png", "Logo-black.png")
    }

    function handleScroll() {
        let el = document.getElementById('navbar_vue');
        let st = window.pageYOffset || document.documentElement.scrollTop;

        lastScrollTop = st <= 0 ? 0 : st; // For Mobile or negative scrolling
        if (window.scrollY > 1) {
            el.classList.add('shadow');
            // el.classList.add('menu-fixed');
        } else {
            el.classList.remove('shadow');
            // el.classList.remove('menu-fixed');

            if (el.classList.contains('is-active')) {
                el.classList.add('is-mobile');
            }
        }
    };

    function scrollListener(obj, type, fn) {
        if (obj.attachEvent) {
            obj['e' + type + fn] = fn;
            obj[type + fn] = function() {
                obj['e' + type + fn](window.event);
            };
            obj.attachEvent('on' + type, obj[type + fn]);
        } else {
            obj.addEventListener(type, fn, false);
        }
    }

    scrollListener(window, 'scroll', function(e) {
        handleScroll();
    });

    // dashboard  Toggle
    function clickToggle() {
        let dbWrapperTwo = document.getElementById("db-wrapper-two");
        let dbWrapper = document.getElementById("db-wrapper");
        sideToggle(dbWrapperTwo, dbWrapper);
    }

    //dashboard Toggle
    sideToggle = (dbWrapperTwo, dbWrapper) => {
        if (dbWrapper.classList == '' || dbWrapperTwo == '') {
            dbWrapperTwo.classList.add('toggled');
            dbWrapper.classList.add('toggled');
        } else {
            dbWrapperTwo.classList.remove('toggled');
            dbWrapper.classList.remove('toggled');
        }
    }

    // Copy to clipboard
    function copyToClipboard() {
        var dummy = document.createElement('input'),
            text = window.location.href;

        document.body.appendChild(dummy);
        dummy.value = text;
        dummy.select();
        document.execCommand('copy');
        document.body.removeChild(dummy);

        alert('Event URL Copied!')
    }


    // set local timezone
    var local_timezone = {!! json_encode(route('eventsaaspro.local_timezone')) !!};

    function setLocalTimezone() {
        // Making our request
        fetch(local_timezone, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    local_timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
                })
            })
            .then(Result => Result.json())
            .then(string => {
                console.log('lang', string);
            })
            .catch(errorMsg => {
                console.log(errorMsg);
            });
    }

    setLocalTimezone();


    // hide browse event link for Nest Hub and  Ipad pro
    var nest_hub_x = window.matchMedia("(max-width: 1024px) and (min-width: 600px)");
    if (nest_hub_x.matches) {
        document.getElementById('mobile-nest_hub').style.cssText = 'display:none !important';
    }




    if (!navigator.serviceWorker.controller) {
        navigator.serviceWorker.register("/sw.js").then(function(reg) {
            console.log("Service worker has been registered for scope: " + reg.scope);
        });
    }
</script>

<script>
    window.config = {
        s3Url: @json(config('filesystems.disks.s3.url')),
    };
</script>
