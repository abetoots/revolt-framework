import React from 'react';
import ReactDOM from 'react-dom';
import './index.css';

//Routing
import { BrowserRouter } from 'react-router-dom';

//Redux
import { createStore, compose, applyMiddleware, combineReducers } from 'redux';
import { Provider } from 'react-redux';
import authReducer from './store/reducers/auth';
import taxonomiesReducer from './store/reducers/taxonomies';
import jobsReducer from './store/reducers/jobs';
import profileReducer from './store/reducers/profile';
import candidatesReducer from './store/reducers/candidates';
import thunk from 'redux-thunk';

import './fontawesome';
import App from './App';

//Froala
import 'froala-editor/js/froala_editor.pkgd.min.js';
import 'froala-editor/css/froala_style.min.css';
import 'froala-editor/css/froala_editor.pkgd.min.css';

import * as serviceWorker from './serviceWorker';


const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__({ trace: true, traceLimit: 25 }) || compose;

const rootReducer = combineReducers({
    auth: authReducer,
    jobs: jobsReducer,
    taxonomies: taxonomiesReducer,
    profile: profileReducer,
    candidates: candidatesReducer
});

//Handle resetting of state whenever user logs out
const withLogoutHandlingReducer = (state, action) => {
    if (action.type === 'AUTH_LOGOUT') {
        state = undefined
    }
    return rootReducer(state, action);
}

const store = createStore(withLogoutHandlingReducer, composeEnhancers(
    applyMiddleware(thunk)
));

const baseUrl = window.location.hostname === 'localhost' ? '/flerson' : '/flerson.com'

const app = (
    <Provider store={store}>
        <BrowserRouter basename={baseUrl}>
            <App />
        </BrowserRouter>
    </Provider>
);

const target = document.getElementById('revolt-root');
if (target) { ReactDOM.render(app, target) };

// If you want your app to work offline and load faster, you can change
// unregister() to register() below. Note this comes with some pitfalls.
// Learn more about service workers: https://bit.ly/CRA-PWA
serviceWorker.unregister();
