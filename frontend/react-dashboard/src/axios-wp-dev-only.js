import axios from 'axios';

const instance = axios.create({
    baseURL: 'http://localhost/flerson'
});

export default instance;