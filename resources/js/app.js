import './bootstrap';

Alpine.store('sidebar', {
    collapsed: localStorage.getItem('sidebarCollapsed') === '1',
    toggle() {
        this.collapsed = !this.collapsed;
        localStorage.setItem('sidebarCollapsed', this.collapsed ? '1' : '0');
    },
});

Alpine.store('darkMode', {
    on: localStorage.getItem('isDark') === 'true',
    init() {
        this.on = localStorage.getItem('isDark') === 'true' ?? window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (this.on) document.documentElement.classList.add('dark');
    },
    toggle() {
        this.on = !this.on;
        localStorage.setItem('isDark', this.on);
        this.on ?
            document.documentElement.classList.add('dark') :
            document.documentElement.classList.remove('dark');
    }
});

Alpine.data('installPrompt', () => ({
    deferredPrompt: null,
    canInstall: false,
    ios: false,
    iosHelp: false,
    show: false,
    init() {
        const isIos = /iphone|ipad|ipod/i.test(navigator.userAgent);
        const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;

        if (isStandalone) {
            this.show = false;
            return;
        }

        this.ios = isIos;

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            this.deferredPrompt = e;
            this.canInstall = true;
            this.show = true;
        });

        window.addEventListener('appinstalled', () => {
            this.show = false;
            this.canInstall = false;
        });

        if (this.ios) {
            this.show = true;
        }
    },
    async install() {
        if (!this.deferredPrompt) return;
        this.deferredPrompt.prompt();
        await this.deferredPrompt.userChoice;
        this.deferredPrompt = null;
        this.canInstall = false;
    },
}));

let map;

window.initializeMap = ({ onUpdate, location }) => {
    // Initialize the map centered at a default location
    let defaultLocation = location ?? [-6.928334121065185, 107.60809121537025];
    map = L.map('map').setView(defaultLocation, 13);

    // Set up the OSM layer
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 21,
    }).addTo(map);

    // Create a marker at the center of the map
    let marker = L.marker(defaultLocation, {
        draggable: true,
    }).addTo(map);

    // Update coordinates when the marker is dragged
    marker.on('dragend', function (event) {
        let position = marker.getLatLng();
        updateCoordinates(position.lat, position.lng);
    });

    // Update coordinates when the map is moved
    map.on('move', function () {
        let center = map.getCenter();
        marker.setLatLng(center);
        updateCoordinates(center.lat, center.lng);
    });

    // Initial coordinates display
    updateCoordinates(defaultLocation[0], defaultLocation[1]);

    function updateCoordinates(lat, lng) {
        onUpdate(lat, lng);
    }
}

window.setMapLocation = ({ location }) => {
    if (location == null) return;

    map.setView(location, 13);
}
