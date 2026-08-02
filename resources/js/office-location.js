import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

window.L = L;

const createOfficeMap = () => ({
    searchQuery: '',
    searchResults: [],
    searching: false,
    searchError: '',
    coordinateText: 'Belum diatur',
    dmsText: '-',
    map: null,
    marker: null,
    mapReady: false,
    dragging: false,
    defaultCenter: { lat: -6.2087634, lng: 106.845599 },

    init() {
        this.$watch('$wire.showOfficeLocationModal', (value) => {
            if (value) {
                this.openMap();
            } else {
                this.closeMap();
            }
        });

        this.$watch('$wire.latitude', () => this.syncFromLivewire());
        this.$watch('$wire.longitude', () => this.syncFromLivewire());

        if (this.$wire && this.$wire.get('showOfficeLocationModal')) {
            this.openMap();
        }
    },

    openMap() {
        this.mapReady = false;
        setTimeout(() => {
            this.initMap();
        }, 300);
    },

    closeMap() {
        const el = this.$refs.mapEl;
        if (this.map) {
            this.map.remove();
            this.map = null;
            this.marker = null;
            this.mapReady = false;
            if (el) {
                el.__officeMap = null;
            }
        }
    },

    currentLat() {
        const value = this.$wire ? this.$wire.get('latitude') : null;
        const n = parseFloat(value);
        return isNaN(n) ? null : n;
    },

    currentLng() {
        const value = this.$wire ? this.$wire.get('longitude') : null;
        const n = parseFloat(value);
        return isNaN(n) ? null : n;
    },

    initMap() {
        const el = this.$refs.mapEl;
        if (!el) return;

        if (el.__officeMap) {
            this.map = el.__officeMap;
            this.mapReady = true;
            this.syncFromLivewire();
            setTimeout(() => this.map.invalidateSize(), 100);
            return;
        }

        let lat = this.currentLat();
        let lng = this.currentLng();
        let isNew = false;
        if (lat === null || lng === null) {
            lat = this.defaultCenter.lat;
            lng = this.defaultCenter.lng;
            isNew = true;
        }

        const map = L.map(el, { scrollWheelZoom: true }).setView([lat, lng], 16);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }).addTo(map);

        const icon = L.divIcon({
            className: '',
            html: '<div style="font-size:28px;line-height:1;text-align:center;filter:drop-shadow(0 1px 2px rgba(0,0,0,.4))">📍</div>',
            iconSize: [30, 34],
            iconAnchor: [15, 32],
            popupAnchor: [0, -32],
        });

        const marker = L.marker([lat, lng], { draggable: true, icon }).addTo(map);

        marker.on('dragstart', () => {
            this.dragging = true;
        });
        marker.on('dragend', (e) => {
            this.dragging = false;
            const ll = e.target.getLatLng();
            this.setCoords(ll.lat, ll.lng);
        });

        map.on('click', (e) => {
            this.setCoords(e.latlng.lat, e.latlng.lng);
        });

        this.map = map;
        this.marker = marker;
        el.__officeMap = map;
        this.mapReady = true;

        if (isNew) {
            this.setCoords(lat, lng);
        } else {
            this.syncFromLivewire();
        }
        setTimeout(() => map.invalidateSize(), 350);
    },

    syncFromLivewire() {
        const lat = this.currentLat();
        const lng = this.currentLng();
        if (lat !== null && lng !== null && this.marker) {
            this.marker.setLatLng([lat, lng]);
        }
        this.updateReadout(lat, lng);
    },

    setCoords(lat, lng) {
        const cleanLat = Number(lat);
        const cleanLng = Number(lng);
        if (isNaN(cleanLat) || isNaN(cleanLng)) return;

        if (this.$wire) {
            this.$wire.set('latitude', cleanLat);
            this.$wire.set('longitude', cleanLng);
        }

        if (this.marker) {
            this.marker.setLatLng([cleanLat, cleanLng]);
        }
        if (this.map) {
            this.map.setView([cleanLat, cleanLng], Math.max(this.map.getZoom(), 16));
        }
        this.updateReadout(cleanLat, cleanLng);
    },

    onManualChange() {
        const latInput = this.$refs.latInput;
        const lngInput = this.$refs.lngInput;
        if (!latInput || !lngInput) return;

        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);

        if (!isNaN(lat) && !isNaN(lng) && this.marker) {
            this.marker.setLatLng([lat, lng]);
        }
        this.updateReadout(isNaN(lat) ? null : lat, isNaN(lng) ? null : lng);
    },

    updateReadout(lat, lng) {
        if (lat === null || lng === null || isNaN(lat) || isNaN(lng)) {
            this.coordinateText = 'Belum diatur';
            this.dmsText = '-';
            return;
        }
        this.coordinateText = `${Number(lat).toFixed(7)}, ${Number(lng).toFixed(7)}`;
        this.dmsText = `${this.toDMS(lat, true)}, ${this.toDMS(lng, false)}`;
    },

    toDMS(coord, isLat) {
        const absolute = Math.abs(Number(coord));
        const degrees = Math.floor(absolute);
        const minutesFloat = (absolute - degrees) * 60;
        const minutes = Math.floor(minutesFloat);
        const seconds = ((minutesFloat - minutes) * 60).toFixed(1);
        const direction = isLat
            ? (Number(coord) >= 0 ? 'U' : 'S')
            : (Number(coord) >= 0 ? 'T' : 'B');
        return `${degrees}° ${minutes}' ${seconds}" ${direction}`;
    },

    async searchLocation() {
        const query = this.searchQuery.trim();
        if (!query) return;

        this.searching = true;
        this.searchError = '';
        this.searchResults = [];

        try {
            const response = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&limit=5&q=${encodeURIComponent(query)}`
            );
            if (!response.ok) throw new Error('Network error');
            const data = await response.json();
            this.searchResults = Array.isArray(data) ? data : [];
        } catch (error) {
            this.searchError = 'Pencarian lokasi gagal. Coba lagi.';
        } finally {
            this.searching = false;
        }
    },

    selectResult(result) {
        this.searchResults = [];
        this.searchQuery = result.display_name;
        this.setCoords(parseFloat(result.lat), parseFloat(result.lon));
    },

    useMyLocation() {
        if (!navigator.geolocation) {
            this.searchError = 'Browser Anda tidak mendukung geolocation.';
            return;
        }

        this.searchError = '';
        navigator.geolocation.getCurrentPosition(
            (position) => {
                this.searchQuery = '';
                this.setCoords(position.coords.latitude, position.coords.longitude);
            },
            () => {
                this.searchError = 'Gagal mendapatkan lokasi Anda. Pastikan GPS diaktifkan.';
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
    },

    resetCoords() {
        this.searchQuery = '';
        this.searchResults = [];
        this.setCoords(this.defaultCenter.lat, this.defaultCenter.lng);
    },
});

if (window.Alpine) {
    window.Alpine.data('officeMap', createOfficeMap);
} else {
    document.addEventListener('alpine:init', () => {
        window.Alpine.data('officeMap', createOfficeMap);
    });
}
