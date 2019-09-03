import * as actionTypes from '../actions/actionTypes';
import { updateObject } from '../../utility/utility';

const initialState = {
    token: null,
    userId: null,
    userName: null,
    userRole: null,
    error: null,
    loading: false
}

const authenticationSuccess = (state, action) => {
    return updateObject(state, {
        token: action.token,
        userId: action.userId,
        userName: action.userName,
        userRole: action.userRole,
        loading: false,
        error: null
    })
}

const authLogout = (state, action) => {
    return updateObject(state, {
        token: null,
        userId: null,
        userName: null,
        userRole: null
    })
}
const authLogoutWithError = (state, action) => {
    return updateObject(state, {
        token: null,
        userId: null,
        userName: null,
        userRole: null,
        error: action.error
    })
}

const reducer = (state = initialState, action) => {
    switch (action.type) {
        case actionTypes.AUTHENTICATION_START: return updateObject(state, { loading: true, error: null });
        case actionTypes.AUTHENTICATION_SUCCESS: return authenticationSuccess(state, action);
        case actionTypes.AUTHENTICATION_FAILED: return updateObject(state, { error: action.error, loading: false });
        case actionTypes.AUTH_LOGOUT: return authLogout(state, action);
        case actionTypes.AUTH_LOGOUT_WITH_ERROR: return authLogoutWithError(state, action);
        default:
            return state;
    }
}

export default reducer;