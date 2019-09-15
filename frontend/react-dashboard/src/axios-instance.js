import axios from 'axios';

const instance = axios.create({
    baseURL: window.location.hostname === 'localhost' ? 'http://localhost/flerson' : 'https://flerson.com'
});

export default instance;