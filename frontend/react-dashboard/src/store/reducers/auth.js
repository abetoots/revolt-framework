import * as actionTypes from '../actions/actionTypes';
import { updateObject } from '../../utility/utility';

const initialState = {
    token: '',
    userId: '',
    userName: '',
    userRole: '',
    error: '',
    dev: '',
    valid: false,
    loading: false
}

const authenticationSuccess = (state, action) => {
    return updateObject(state, {
        token: action.token,
        userId: action.userId,
        userName: action.userName,
        userRole: action.userRole,
        loading: false,
        valid: true,
        error: null
    });
}

const authenticationFailed = (state, action) => {
    return updateObject(state, {
        token: '',
        userId: '',
        userName: '',
        userRole: '',
        loading: false,
        valid: false,
        error: action.error
    });
}

const authenticationFailedDev = (state, action) => {
    return updateObject(state, {
        token: '',
        userId: '',
        userName: '',
        userRole: '',
        loading: false,
        valid: false,
        dev: action.environment
    });
}

const authLogout = (state, action) => {
    return updateObject(state, {
        token: '',
        userId: '',
        userName: '',
        userRole: ''
    });
}
const authLogoutWithError = (state, action) => {
    return updateObject(state, {
        token: '',
        userId: '',
        userName: '',
        userRole: '',
        error: action.error
    });
}

const reducer = (state = initialState, action) => {
    switch (action.type) {
        case actionTypes.AUTHENTICATION_START: return updateObject(state, { loading: true, error: null });
        case actionTypes.AUTHENTICATION_SUCCESS: return authenticationSuccess(state, action);
        case actionTypes.AUTHENTICATION_FAILED: return authenticationFailed(state, action);
        case actionTypes.AUTHENTICATION_FAILED_DEV: return authenticationFailedDev(state, action);
        case actionTypes.AUTH_LOGOUT: return authLogout(state, action);
        case actionTypes.AUTH_LOGOUT_WITH_ERROR: return authLogoutWithError(state, action);
        default:
            return state;
    }
}

export default reducer;