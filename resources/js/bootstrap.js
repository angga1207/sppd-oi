import axios from 'axios';
// jQuery loaded via CDN in layout

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
