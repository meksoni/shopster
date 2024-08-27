<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Shopster | neodigital.pro')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/logo/alfasoft-black.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/node_modules/css/simplebar.min.css')}}">



    @vite(['resources/portal/scss/portal.scss', 'resources/portal/js/portal.js'])

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid">

            @if(!isset($hideSidebar) || !$hideSidebar)
            <!-- SIDEBAR/start -->
                @include('admin.layouts.sidebar')
            @endif

            <main class="{{ isset($isLoginPage) && $isLoginPage ? 'd-flex flex-column flex-row-fluid align-items-center justify-content-center vh-100' : 'page-content d-flex flex-column flex-row-fluid' }}">
                @if(!isset($hideNavbar) || !$hideNavbar)
                    <!-- NAVBAR/start -->
                    @include('admin.layouts.navbar')
                @endif
            
                <!-- Toolbar -->
                @if(!isset($hideToolbar) || !$hideToolbar)
                    @include('admin.components.toolbar')
                @endif

              
            
                <!-- PAGE CONTENT/start -->
                @yield('content')
            
                @if(!isset($isLoginPage) || !$isLoginPage)
                <footer class="p-2 px-3 px-lg-6 pt-0">
                    <div class="container-fluid px-0">
                        <span class="d-block lh-sm small text-body-secondary text-end">&copy;
                            <script>
                                document.write(new Date().getFullYear())
                            </script>. Copyright <a href="https://alfasoft.rs" target="_blank">alfasoft.rs</a> - AlfaSoft | Business eCommerce Platform
                        </span>
                    </div>
                </footer>
                @endif
            </main>
        </div>
    </div>

    @yield('customJS')

    <script src="{{ asset('assets/vendor/node_modules/js/inputmask.min.js')}}"></script>
    <script>
        Inputmask().mask(document.querySelectorAll("[data-inputmask]"));
    </script>

<script>



// Notifikacije
const notificationsIndexUrl = '{{ route("notifications.getNotifications") }}';
const markAsReadUrl = '{{ route("notifications.markAllAsRead") }}';


// Fotografije korisnika
const baseUrl = '{{ asset('storage/user_images')}}';
const fallbackImageUrl = '{{ asset('storage/icons/administrator.png') }}';

document.addEventListener("DOMContentLoaded", function() {
    loadNotifications();

    $('a[data-bs-toggle="dropdown"]').on('shown.bs.dropdown', function() {
        markAllAsRead();
    });

    function loadNotifications() {
        $.ajax({
            url: notificationsIndexUrl,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const notifications = response.notifications;
                const storeName = response.store.cp_name;
                const notificationList = $('#notificationList');
                const notificationIndicator = $('#notificationIndicator');
                notificationList.empty(); // Očisti prethodne notifikacije

                // Proveri da li ima notifikacija
                if (notifications.length === 0) {
                    notificationList.append('<span class="text-muted text-center mt-2">Nema novih notifikacija</span>');
                    notificationIndicator.removeClass('d-flex').addClass('d-none'); // Sakrij indikator
                    return;
                }

                // Promenljiva za proveru nepročitanih notifikacija
                let hasUnread = false;

                // Petlja kroz sve notifikacije
                notifications.forEach(notification => {
                    const timeAgo = getTimeAgo(notification.created_at);
                    const url = notification.url; // Ako URL nije definisan, koristi '#'
                
                    let userId, userImageUrl, userName;
                    let userLabel; // Dodajemo varijablu za oznaku korisnika
                
                    if (notification.user) {
                        userId = notification.user.id;
                        userImageUrl = `${baseUrl}/user_${userId}.png`;
                        userName = notification.user.first_name;
                        userLabel = `Korisnik <span class="fw-medium">${userName}</span>`; // Ako postoji korisnik
                    } else {
                        userImageUrl = fallbackImageUrl; // Podrazumevana slika ako korisnik ne postoji
                        userName = storeName; // Ime prodavnice ako korisnik ne postoji
                        userLabel = `<span class="fw-medium">${userName}</span>`; // Ako ne postoji korisnik
                    }
                
                    const listItem = $(`
                        <a href="${url}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center">
                            <div class="d-block me-3">
                                <div class="avatar">
                                    <img src="${userImageUrl}" class="img-fluid rounded-circle w-auto" onerror="this.onerror=null;this.src='${fallbackImageUrl}';" />                                
                                </div>
                            </div>
                            <div class="flex-grow-1 flex-wrap pe-3">
                                <span class="mb-0 d-block">${userLabel} - ${notification.message}</span>
                                <small class="text-body-secondary">${timeAgo}</small>
                            </div>
                        </a>
                    `);
                
                    listItem.data('id', notification.id); // Sačuvaj ID notifikacije
                
                    // Proveri da li je is_read 0 (konvertuj u broj)
                    if (parseInt(notification.is_read) === 0) {
                        hasUnread = true; // Ako imamo nepročitanu notifikaciju
                    }
                
                    notificationList.append(listItem);
                });

                // Prikaži ili sakrij indikator
                if (hasUnread === true) {
                    notificationIndicator.removeClass('d-none').addClass('d-flex');
                } else {
                    notificationIndicator.removeClass('d-flex').addClass('d-none');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Greška:', textStatus, errorThrown);
            }
        });
    }

    function markAllAsRead() {
        $.ajax({
            url: markAsReadUrl,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function(data) {
                if (data.success) {
                    loadNotifications();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Greška:', textStatus, errorThrown);
            }
        });
    }

    function getTimeAgo(createdAt) {
        const now = new Date();
        const createdAtDate = new Date(createdAt);
        const diff = Math.floor((now - createdAtDate) / 1000);

        const minutes = Math.floor(diff / 60);
        const hours = Math.floor(diff / 3600);
        const days = Math.floor(diff / 86400);

        if (diff < 60) {
            return `Pre ${diff} sekundi`;
        } else if (minutes < 60) {
            return `Pre ${minutes} minuta`;
        } else if (hours < 24) {
            return `Pre ${hours} sati`;
        } else {
            return `Pre ${days} dana`;
        }
    }
});






    
</script>
</body>
</html>